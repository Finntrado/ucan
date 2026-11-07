/* U-CAN Collaboration Toolkit - the form, the partner link and the joint results.
   Content comes from #tk-data (inc/toolkit-data.json); scores come from the server
   (inc/toolkit.php), so the page, the emails and wp-admin always agree. */
(function () {
  "use strict";
  var root = document.getElementById("tk-app");
  var dataEl = document.getElementById("tk-data");
  if (!root || !dataEl) return;

  var D = JSON.parse(dataEl.textContent);
  var API = root.getAttribute("data-api") || "/wp-json/ucan/v1/toolkit";
  var ART = root.getAttribute("data-art") || "";
  var qs = new URLSearchParams(location.search);
  var PAIR = (qs.get("pair") || "").replace(/[^A-Za-z0-9]/g, "").toUpperCase();
  var KEY = (qs.get("results") || "").replace(/[^a-f0-9]/g, "");
  var DRAFT = "ucan_tk_draft_" + (PAIR || "start");

  var SEC = {};
  D.parts.forEach(function (p) {
    p.sections.forEach(function (s, i) { SEC[s.id] = { part: p, i: i, s: s }; });
  });
  var BANDNAME = { g: "Strong", a: "Emerging", r: "Needs attention" };

  /* ------------------------------------------------------------ helpers */
  function esc(s) {
    return String(s == null ? "" : s).replace(/[&<>"']/g, function (c) {
      return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c];
    });
  }
  function rich(s) { return esc(s).replace(/\*\*(.+?)\*\*/g, "<strong>$1</strong>"); }
  function $(sel, ctx) { return (ctx || root).querySelector(sel); }
  function $$(sel, ctx) { return Array.prototype.slice.call((ctx || root).querySelectorAll(sel)); }
  function load(k) { try { return JSON.parse(localStorage.getItem(k) || "null"); } catch (e) { return null; } }
  function save(k, v) { try { localStorage.setItem(k, JSON.stringify(v)); } catch (e) {} }
  function drop(k) { try { localStorage.removeItem(k); } catch (e) {} }
  function num(v) { return v == null ? "–" : Number(v).toFixed(1); }
  function bandLabel(part, b) {
    var t = part.snapshot.bands[b] || BANDNAME[b] || "";
    return t.charAt(0) + t.slice(1).toLowerCase();
  }
  function api(path, body) {
    var opt = body
      ? { method: "POST", headers: { "Content-Type": "application/json", Accept: "application/json" }, body: JSON.stringify(body) }
      : { headers: { Accept: "application/json" } };
    return fetch(API + path, opt).then(function (r) {
      return r.json().catch(function () { return { ok: false }; }).then(function (j) {
        if (!r.ok || !j.ok) throw new Error(j && j.error ? j.error : "Something went wrong (" + r.status + "). Please try again.");
        return j;
      });
    }, function () { throw new Error("We could not reach the server. Please check your connection and try again."); });
  }
  function show(html) { root.innerHTML = html; }
  function message(title, text) {
    show('<div class="tk-wrap"><div class="tk-card"><h2>' + esc(title) + '</h2><p class="tk-p">' + text + "</p></div></div>");
  }
  function top() { var y = root.getBoundingClientRect().top + window.pageYOffset - 90; window.scrollTo(0, Math.max(0, y)); }

  /* --------------------------------------------------- the PDF's pages */
  function steps(list) {
    return '<ol class="tk-steps">' + list.map(function (t, i) { return '<li data-n="' + (i + 1) + '">' + esc(t) + "</li>"; }).join("") + "</ol>";
  }
  function scaleTable(sc) {
    return '<p class="tk-label">' + esc(sc.label) + '</p><table class="tk-tbl"><thead><tr><th>' + esc(sc.head[0]) + "</th><th>" + esc(sc.head[1]) + "</th></tr></thead><tbody>" +
      sc.rows.map(function (r) {
        return '<tr class="r-' + r.band + '"><td>' + esc(r.range) + ' <span class="tk-dot ' + r.band + '"></span>' + esc(r.name) + "</td><td>" + esc(r.text) + "</td></tr>";
      }).join("") + "</tbody></table>" + '<p class="tk-note">' + esc(sc.note) + "</p>";
  }
  function partCover(p) {
    return '<div class="tk-cover" style="--art:url(\'' + esc(ART) + '\')"><div><p class="tk-kick">' + esc(p.kicker) + "</p><h2>" + esc(p.title) + "</h2><p>" + esc(p.sub) + "</p></div></div>";
  }
  function partIntro(p) {
    var h = "";
    p.intro.forEach(function (b) {
      h += '<p class="tk-label">' + esc(b.label) + "</p>" + b.paras.map(function (t) { return '<p class="tk-p">' + rich(t) + "</p>"; }).join("");
    });
    h += '<p class="tk-label">' + esc(p.how.label) + "</p>" + p.how.paras.map(function (t) { return '<p class="tk-p">' + esc(t) + "</p>"; }).join("") + steps(p.how.steps);
    if (p.how.note) h += '<p class="tk-note">' + esc(p.how.note) + "</p>";
    if (p.stages) {
      h += '<p class="tk-label">' + esc(p.stages.label) + '</p><table class="tk-tbl"><thead><tr><th>' + esc(p.stages.head[0]) + "</th><th>" + esc(p.stages.head[1]) + "</th></tr></thead><tbody>" +
        p.stages.rows.map(function (r) { return "<tr><td>" + esc(r[0]) + "</td><td>" + esc(r[1]) + "</td></tr>"; }).join("") + '</tbody></table><p class="tk-note">' + esc(p.stages.note) + "</p>";
    }
    h += scaleTable(p.scale);
    return '<div class="tk-intro">' + h + "</div>";
  }
  function sectionForm(sid) {
    var m = SEC[sid], s = m.s, p = m.part, a = state.answers[sid] || {};
    var c = D.common;
    var qs = s.statements.map(function (t, j) {
      var name = sid + "-" + j, v = a.r ? a.r[j] : null, opts = "";
      for (var n = 1; n <= 5; n++) {
        opts += '<label title="' + (n === 1 ? "Strongly Disagree" : n === 5 ? "Strongly Agree" : n) + '"><input type="radio" name="' + name + '" value="' + n + '" data-sid="' + sid + '" data-j="' + j + '"' + (v === n ? " checked" : "") + '><span>' + n + "</span></label>";
      }
      return '<fieldset class="tk-q" id="q-' + name + '"><legend>' + esc(t) + '</legend><div><div class="tk-scale" role="radiogroup">' + opts + '</div><div class="tk-ends"><span>Strongly disagree</span><span>Strongly agree</span></div></div></fieldset>';
    }).join("");
    return '<section class="tk-sec" id="sec-' + sid + '"><div class="tk-band"><p class="tk-kick">' + esc(p.unit) + " " + (m.i + 1) + " OF " + p.sections.length + "</p><h3>" + esc(s.name) + "</h3><p>" + esc(s.question) + "</p></div>" +
      '<div class="tk-body"><div class="tk-rate"><b>' + esc(c.rate.title) + "</b><span>" + esc(c.rate.text) + '</span></div><div class="tk-qhead"><span>' + esc(c.head[0]) + "</span><span>" + esc(c.head[1]) + "</span></div>" + qs +
      '<div class="tk-disc"><h4>' + esc(c.discuss) + '</h4><ol class="tk-ol">' + s.discuss.map(function (q) { return "<li>" + esc(q) + "</li>"; }).join("") + "</ol></div>" +
      '<div class="tk-act"><label for="act-' + sid + '">' + esc(c.action) + ' <small>(optional: the action your organisation would suggest)</small></label><textarea class="tk-ta" id="act-' + sid + '" data-sid="' + sid + '" data-f="action" maxlength="2000">' + esc(a.action || "") + "</textarea></div></div></section>";
  }
  function learningsForm(p, sids) {
    var sn = p.snapshot;
    var rows = sids.map(function (sid) {
      var a = state.answers[sid] || {};
      return "<tr><td>" + esc(SEC[sid].s.name) + '</td><td data-h="' + esc(sn.learnings.head[1]) + '"><textarea class="tk-ta" aria-label="' + esc(SEC[sid].s.name + ": " + sn.learnings.head[1]) + '" data-sid="' + sid + '" data-f="right" maxlength="2000">' + esc(a.right || "") + '</textarea></td><td data-h="' + esc(sn.learnings.head[2]) + '"><textarea class="tk-ta" aria-label="' + esc(SEC[sid].s.name + ": " + sn.learnings.head[2]) + '" data-sid="' + sid + '" data-f="diff" maxlength="2000">' + esc(a.diff || "") + "</textarea></td></tr>";
    }).join("");
    return '<section class="tk-sec"><div class="tk-body"><p class="tk-label">' + esc(sn.kicker) + '</p><h3 style="font:800 clamp(23px,2.8vw,30px)/1.15 var(--display,Archivo,sans-serif);color:var(--teal-deep,#0E5348);margin:0 0 10px">' + esc(sn.title) + '</h3><p class="tk-p tk-muted">' + esc(sn.intro) + ' <em>(Optional.)</em></p><table class="tk-learn"><thead><tr><th>' + esc(sn.learnings.head[0]) + "</th><th>" + esc(sn.learnings.head[1]) + "</th><th>" + esc(sn.learnings.head[2]) + "</th></tr></thead><tbody>" + rows + "</tbody></table></div></section>";
  }

  /* ------------------------------------------------------------ form */
  var state = { mode: "A", sections: [], answers: {}, profile: { org: "", person: "", partner: "", email: "" }, consent: false };

  function chosenSections() {
    if (state.mode === "B") return state.sections;
    var out = [];
    $$('input[name="tk-part"]').forEach(function (cb) {
      if (!cb.checked) return;
      var part = D.parts.filter(function (p) { return p.id === cb.value; })[0];
      part.sections.forEach(function (s) {
        if (part.optional) {
          var st = $('input[name="tk-stage"][value="' + s.id + '"]');
          if (st && !st.checked) return;
        }
        out.push(s.id);
      });
    });
    return out;
  }

  function renderParts() {
    var want = chosenSections(), html = "";
    state.sections = want;
    D.parts.forEach(function (p) {
      var sids = p.sections.map(function (s) { return s.id; }).filter(function (id) { return want.indexOf(id) > -1; });
      if (!sids.length) return;
      html += '<div class="tk-part" id="part-' + p.id + '">' + partCover(p) + partIntro(p) + '<div style="height:22px"></div>' + sids.map(sectionForm).join("");
      if (p.snapshot.learnings) html += learningsForm(p, sids);
      html += "</div>";
    });
    $("#tk-parts").innerHTML = html || '<div class="tk-card"><p class="tk-p">Choose at least one part above to begin.</p></div>';
    progress();
  }

  function progress() {
    var total = 0, done = 0;
    state.sections.forEach(function (sid) {
      var n = SEC[sid].s.statements.length, r = (state.answers[sid] || {}).r || [];
      total += n;
      for (var j = 0; j < n; j++) if (r[j]) done++;
    });
    var bar = $("#tk-prog-i"), txt = $("#tk-prog-t");
    if (bar) bar.style.width = total ? (100 * done / total) + "%" : "0";
    if (txt) txt.textContent = done + " of " + total + " statements rated";
  }

  var saveTimer;
  function persist() {
    clearTimeout(saveTimer);
    saveTimer = setTimeout(function () {
      var picks = $$('input[name="tk-part"],input[name="tk-stage"]').map(function (c) { return [c.value, c.checked]; });
      save(DRAFT, { answers: state.answers, profile: state.profile, picks: picks });
    }, 300);
  }

  function renderForm(pairInfo) {
    var invited = !!pairInfo;
    state.mode = invited ? "B" : "A";
    var draft = load(DRAFT) || {};
    state.answers = draft.answers || {};
    state.profile = draft.profile || state.profile;
    if (invited) state.sections = pairInfo.sections;

    var picker = "";
    if (!invited) {
      picker = '<div class="tk-card"><p class="tk-label">Choose what to complete</p><p class="tk-p tk-muted">Your partner organisation will be asked to complete the same parts.</p><div class="tk-pick">' +
        D.parts.map(function (p, i) {
          var on = p.optional ? "" : " checked";
          return '<label class="tk-opt"><input type="checkbox" name="tk-part" value="' + p.id + '"' + on + "><b>PART " + (i + 1) + "</b><span>" + esc(p.title) + "</span><small>" + esc(p.sub) + "</small></label>";
        }).join("") + "</div>" +
        D.parts.filter(function (p) { return p.optional; }).map(function (p) {
          return '<div class="tk-stagepick" id="stages-' + p.id + '" hidden><p>' + esc(p.how.paras[1].split(", and then")[0]) + ".</p>" +
            p.sections.map(function (s) { return '<label><input type="checkbox" name="tk-stage" value="' + s.id + '" checked> ' + esc(s.name) + "</label>"; }).join("") + "</div>";
        }).join("") + "</div>";
    }
    var who = invited
      ? '<div class="tk-card tk-invited"><p class="tk-label">You have been invited</p><h2>' + esc(pairInfo.org) + " has asked you to complete the toolkit as its partner organisation</h2><p class=\"tk-p\" style=\"color:rgba(255,255,255,.9)\">Please rate each statement independently, from your organisation’s own perspective. You will not see " + esc(pairInfo.org) + "’s answers until you submit; then the joint results appear here and are emailed to both of you.</p></div>"
      : "";
    var p = state.profile;
    show('<div class="tk-wrap tk-noprint">' + who +
      '<div class="tk-card"><p class="tk-label">About your organisation</p><div class="tk-grid2">' +
      '<div class="tk-field"><label for="tk-org">Organisation name</label><input class="tk-in" id="tk-org" data-p="org" maxlength="200" autocomplete="organization" value="' + esc(p.org || (invited ? pairInfo.partner_org : "")) + '"></div>' +
      '<div class="tk-field"><label for="tk-person">Your name</label><input class="tk-in" id="tk-person" data-p="person" maxlength="200" autocomplete="name" value="' + esc(p.person) + '"></div>' +
      (invited ? "" : '<div class="tk-field"><label for="tk-partner">Partner organisation <small>(optional)</small></label><input class="tk-in" id="tk-partner" data-p="partner" maxlength="200" value="' + esc(p.partner) + '"></div>') +
      "</div></div>" + picker +
      '<div class="tk-prog"><div class="tk-prog-in"><span id="tk-prog-t"></span><div class="tk-prog-bar"><i id="tk-prog-i"></i></div></div></div>' +
      '<div id="tk-parts"></div>' +
      '<div class="tk-card" id="tk-final"><p class="tk-label">' + (invited ? "See your joint results" : "Get your results") + "</p><h2>Where should we send the results?</h2>" +
      '<p class="tk-p tk-muted">' + (invited
        ? "As soon as you submit, the joint results appear on this page and are emailed to you and to " + esc(pairInfo.org) + ", with a copy to U-CAN."
        : "When you submit, you get a private link to send to your partner organisation. As soon as they finish, the joint results are emailed to both of you, with a copy to U-CAN.") + "</p>" +
      '<div class="tk-grid2"><div class="tk-field"><label for="tk-email">Your email address</label><input class="tk-in" id="tk-email" data-p="email" type="email" maxlength="191" autocomplete="email" value="' + esc(p.email) + '"></div></div>' +
      '<div class="tk-hp" aria-hidden="true"><label>Website <input type="text" id="tk-website" tabindex="-1" autocomplete="off"></label></div>' +
      '<label class="tk-consent"><input type="checkbox" id="tk-consent"><span>I agree that U-CAN may store my organisation’s answers, my name and email address to calculate and send these results, share them with ' + (invited ? esc(pairInfo.org) : "the partner organisation I invite") + ', and follow up about collaboration. To have them deleted, write to <a href="mailto:privacy@urban.org.in">privacy@urban.org.in</a>. See our <a href="/privacy-policy">privacy policy</a> and <a href="/data-rights">your data rights</a>.</span></label>' +
      '<div class="tk-actions"><button type="button" class="tk-btn" id="tk-submit">' + (invited ? "Submit and see our joint results" : "Submit and get our partner link") + ' <span aria-hidden="true">→</span></button></div><p class="tk-err" id="tk-err" role="alert"></p></div></div>');

    if (!invited && draft.picks) {
      draft.picks.forEach(function (pk) {
        var el = $('input[name="tk-part"][value="' + pk[0] + '"]') || $('input[name="tk-stage"][value="' + pk[0] + '"]');
        if (el) el.checked = pk[1];
      });
    }
    syncStagePickers();
    renderParts();
  }

  function syncStagePickers() {
    D.parts.forEach(function (p) {
      var box = $("#stages-" + p.id), cb = $('input[name="tk-part"][value="' + p.id + '"]');
      if (box && cb) box.hidden = !cb.checked;
    });
  }

  root.addEventListener("change", function (e) {
    var t = e.target;
    if (t.type === "radio" && t.dataset.sid) {
      var a = state.answers[t.dataset.sid] = state.answers[t.dataset.sid] || {};
      a.r = a.r || [];
      a.r[+t.dataset.j] = +t.value;
      var fs = t.closest(".tk-q");
      if (fs) fs.classList.remove("miss");
      progress(); persist();
    } else if (t.name === "tk-part" || t.name === "tk-stage") {
      syncStagePickers(); renderParts(); persist();
    }
  });
  root.addEventListener("input", function (e) {
    var t = e.target;
    if (t.dataset.p) { state.profile[t.dataset.p] = t.value; persist(); }
    else if (t.dataset.sid && t.dataset.f) {
      (state.answers[t.dataset.sid] = state.answers[t.dataset.sid] || {})[t.dataset.f] = t.value; persist();
    }
  });
  root.addEventListener("click", function (e) {
    var b = e.target.closest("button,a");
    if (!b) return;
    if (b.id === "tk-submit") submit(b);
    else if (b.dataset.copy) {
      var inp = document.getElementById(b.dataset.copy);
      var done = function () { b.textContent = "Copied"; setTimeout(function () { b.textContent = "Copy link"; }, 2000); };
      if (navigator.clipboard) navigator.clipboard.writeText(inp.value).then(done, function () { inp.select(); });
      else { inp.select(); try { document.execCommand("copy"); done(); } catch (x) {} }
    } else if (b.id === "tk-print") window.print();
  });

  function submit(btn) {
    var err = $("#tk-err"), p = state.profile;
    // read the fields themselves: prefilled and browser-autofilled values never fire "input"
    $$("[data-p]").forEach(function (el) { p[el.dataset.p] = el.value; });
    err.textContent = "";
    function fail(msg, el) {
      err.textContent = msg;
      if (el) { el.scrollIntoView({ block: "center" }); if (el.focus) el.focus({ preventScroll: true }); }
    }
    if (!p.org.trim()) return fail("Please give your organisation’s name.", $("#tk-org"));
    if (!p.person.trim()) return fail("Please give your name.", $("#tk-person"));
    if (!state.sections.length) return fail("Please choose at least one part of the toolkit.", $(".tk-pick"));
    var missing = null;
    state.sections.forEach(function (sid) {
      var r = (state.answers[sid] || {}).r || [];
      SEC[sid].s.statements.forEach(function (t, j) {
        if (!r[j]) {
          var fs = $("#q-" + sid + "-" + j);
          if (fs) fs.classList.add("miss");
          if (!missing) missing = fs;
        }
      });
    });
    if (missing) return fail("Please rate every statement - the ones still missing are highlighted.", missing);
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(p.email.trim())) return fail("Please give a valid email address so we can send you the results.", $("#tk-email"));
    if (!$("#tk-consent").checked) return fail("Please tick the consent box to continue.", $("#tk-consent"));

    var answers = {};
    state.sections.forEach(function (sid) { answers[sid] = state.answers[sid]; });
    btn.disabled = true;
    btn.firstChild.textContent = "Saving… ";
    api("/submit", {
      pair: PAIR, org: p.org.trim(), person: p.person.trim(), partner_org: (p.partner || "").trim(),
      email: p.email.trim(), consent: "yes", sections: state.sections, answers: answers,
      website: $("#tk-website").value
    }).then(function (j) {
      drop(DRAFT);
      if (window.history && history.replaceState) history.replaceState(null, "", "?results=" + j.result_url.split("results=")[1]);
      if (j.role === "A") renderWaiting(j.result, j.invite_url, j.result_url, true);
      else renderResults(j.result, j.result_url, j.emailed);
      top();
    }).catch(function (e) {
      btn.disabled = false;
      btn.firstChild.textContent = state.mode === "B" ? "Submit and see our joint results " : "Submit and get our partner link ";
      fail(e.message);
    });
  }

  /* ---------------------------------------------------------- results */
  function shareBlock(invite, orgA, partner) {
    var subj = "Collaboration Toolkit: please complete it with " + orgA;
    var body = orgA + " has started the U-CAN Collaboration Toolkit and would like your organisation to complete it as our partner. Please rate the statements independently here:\n\n" + invite + "\n\nWhen you finish, the joint results are shown and emailed to both organisations.";
    return '<div class="tk-share"><input class="tk-in" id="tk-invite" readonly value="' + esc(invite) + '" aria-label="Partner link"><button type="button" class="tk-btn" data-copy="tk-invite">Copy link</button></div>' +
      '<div class="tk-actions" style="margin-top:8px"><a class="tk-btn line" href="mailto:?subject=' + encodeURIComponent(subj) + "&body=" + encodeURIComponent(body) + '">Email the link</a><a class="tk-btn line" href="https://wa.me/?text=' + encodeURIComponent(body) + '" target="_blank" rel="noopener noreferrer">Share on WhatsApp</a></div>';
  }

  function renderWaiting(res, invite, resultUrl, fresh) {
    var a = res.a.org, partner = res.b && res.b.org ? res.b.org : "your partner organisation";
    var html = '<div class="tk-wrap"><div class="tk-dark"><p class="tk-label">' + (fresh ? "Thank you" : "Waiting for your partner") + "</p><h2>" + (fresh ? "Your answers are saved. Now send this link to " + esc(partner) + "." : esc(partner) + " has not completed the toolkit yet.") + "</h2>" +
      "<p>The joint results are emailed to both organisations, with a copy to U-CAN, as soon as " + esc(partner) + " finishes. We have also emailed this link to you.</p></div>" +
      '<div class="tk-card tk-noprint"><p class="tk-label">Partner link</p><p class="tk-p">Send this private link to the person who will answer for ' + esc(partner) + ". They will not see " + esc(a) + "’s answers until they submit their own.</p>" + shareBlock(invite, a, partner) + "</div>" +
      '<div class="tk-card tk-noprint"><p class="tk-label">Come back later</p><p class="tk-p">This page shows the joint results once both organisations have finished. Keep this link:</p><div class="tk-share"><input class="tk-in" id="tk-self" readonly value="' + esc(resultUrl) + '" aria-label="Your results link"><button type="button" class="tk-btn line" data-copy="tk-self">Copy link</button></div></div>' +
      '<p class="tk-label" style="margin-top:28px">' + esc(a) + "’s answers at a glance</p>" + resultParts(res, true) + "</div>";
    show(html);
  }

  function track(v1, v2) {
    var pos = function (v) { return ((v - 1) / 4 * 100) + "%"; };
    var t = "";
    for (var n = 1; n <= 5; n++) t += '<span class="tick" style="left:' + pos(n) + '"><em>' + n + "</em></span>";
    if (v2 == null) return '<div class="tk-track">' + t + '<span class="pt a" style="left:' + pos(v1) + '" title="' + v1 + '"></span></div>';
    if (v1 === v2) return '<div class="tk-track">' + t + '<span class="pt same" style="left:' + pos(v1) + '" title="Both: ' + v1 + '"></span></div>';
    return '<div class="tk-track">' + t + '<span class="pt a" style="left:' + pos(v1) + '" title="' + v1 + '"></span><span class="pt b" style="left:' + pos(v2) + '" title="' + v2 + '"></span></div>';
  }

  function snapshotTable(part, pr, res) {
    var sn = part.snapshot, both = res.complete;
    var rows = pr.sections.map(function (s) {
      var cells = ["g", "a", "r"].map(function (b) {
        var who = [], tag = [];
        if (s.bandA === b) { who.push(res.a.org); tag.push('<i class="wa">A</i>'); }
        if (both && s.bandB === b) { who.push(res.b.org); tag.push('<i class="wb">B</i>'); }
        return '<td><span class="tk-ring ' + b + (s.band === b ? " on" : "") + '"' + (s.band === b ? ' role="img" aria-label="Result: ' + esc(sn.bands[b].toLowerCase()) + '"' : "") + "></span>" +
          (both && who.length ? '<span class="tk-who"><span class="wl">' + esc(who.join(" · ")) + '</span><span class="ws" aria-hidden="true">' + tag.join("") + "</span></span>" : "") + "</td>";
      }).join("");
      return "<tr><td>" + esc(SEC[s.id].s.name) + "</td>" + cells + "</tr>";
    }).join("");
    return '<table class="tk-snap"><thead><tr><th>' + esc(sn.col) + "</th><th>" + esc(sn.bands.g) + "</th><th>" + esc(sn.bands.a) + "</th><th>" + esc(sn.bands.r) + "</th></tr></thead><tbody>" + rows + "</tbody></table>";
  }

  function tellBlock(part, pr) {
    var t = part.snapshot.tell;
    if (!t) return "";
    var dot = function (b) { return '<span class="tk-dot ' + b + '"></span>'; };
    return '<div class="tk-tell"><h4>' + esc(t.title) + "</h4>" + t.rows.map(function (r) {
      var lead = "<b>" + esc(r.lead) + " " + r.dots.map(dot).join(r.dots.length > 1 ? " and " : "") + (r.lead2 ? " " + esc(r.lead2) + " " + r.dots2.map(dot).join("") : "") + "</b>";
      return '<p class="' + (pr.pattern === r.key ? "hit" : "") + '">' + lead + " — " + esc(r.text) + "</p>";
    }).join("") + "</div>";
  }

  function resultParts(res, solo) {
    var html = "";
    res.parts.forEach(function (pr) {
      var part = D.parts.filter(function (p) { return p.id === pr.id; })[0], sn = part.snapshot;
      html += '<div class="tk-part-res"><section class="tk-sec"><div class="tk-body"><p class="tk-label">' + esc(sn.kicker) + '</p><h3 style="font:800 clamp(23px,2.8vw,32px)/1.15 var(--display,Archivo,sans-serif);color:var(--teal-deep,#0E5348);margin:0 0 10px">' +
        esc(part.snapshot.learnings ? "Navigating the Collaboration Journey" : sn.title) + '</h3><p class="tk-p tk-muted">' + esc(part.snapshot.learnings ? part.scale.note : sn.intro) + "</p>" +
        snapshotTable(part, pr, res) + (solo ? "" : tellBlock(part, pr)) +
        (!solo && sn.reflect ? '<div class="tk-ref"><h4>' + esc(sn.reflect.title) + '</h4><ul class="' + (sn.reflect.numbered ? "tk-ol" : "tk-ul") + '">' + sn.reflect.items.map(function (i) { return "<li>" + esc(i) + "</li>"; }).join("") + "</ul></div>" : "") +
        "</div></section>";
      if (!solo) {
        pr.sections.forEach(function (s) {
          var m = SEC[s.id];
          html += '<section class="tk-sec"><div class="tk-band"><p class="tk-kick">' + esc(part.unit) + " " + s.n + " OF " + part.sections.length + "</p><h3>" + esc(m.s.name) + "</h3><p>" + esc(m.s.question) + '</p></div><div class="tk-body">' +
            '<div class="tk-bars"><div class="tk-bar a"><span>' + esc(res.a.org) + '</span><span class="tr"><i style="width:' + (s.avgA / 5 * 100) + '%"></i></span><b>' + num(s.avgA) + '</b></div><div class="tk-bar b"><span>' + esc(res.b.org) + '</span><span class="tr"><i style="width:' + (s.avgB / 5 * 100) + '%"></i></span><b>' + num(s.avgB) + "</b></div></div>" +
            '<p class="tk-p"><span class="tk-dot ' + s.band + '"></span><b>' + esc(bandLabel(part, s.band)) + "</b> <span class=\"tk-muted\">(average of both organisations: " + num(s.avg) + ")</span></p>" +
            '<div class="tk-qhead"><span>' + esc(D.common.head[0]) + "</span><span>" + esc(res.a.org) + " · " + esc(res.b.org) + "</span></div>" +
            s.stmts.map(function (st, j) {
              return '<div class="tk-res"><p>' + esc(m.s.statements[j]) + (st.gap >= 2 ? '<span class="tk-gap">' + st.gap + "-point difference</span>" : "") + "</p>" + track(st.a, st.b) + "</div>";
            }).join("") +
            '<div class="tk-disc"><h4>' + esc(D.common.discuss) + '</h4><ol class="tk-ol">' + m.s.discuss.map(function (q) { return "<li>" + esc(q) + "</li>"; }).join("") + "</ol></div>" +
            (s.actionA || s.actionB ? '<div class="tk-act"><label>' + esc(D.common.action) + '</label><div class="tk-sugg">' + (s.actionA ? "<div><b>" + esc(res.a.org) + "</b>" + esc(s.actionA) + "</div>" : "") + (s.actionB ? "<div><b>" + esc(res.b.org) + "</b>" + esc(s.actionB) + "</div>" : "") + "</div></div>" : "") +
            "</div></section>";
        });
        if (part.snapshot.learnings) {
          var lr = pr.sections.filter(function (s) { return s.rightA || s.diffA || s.rightB || s.diffB; });
          if (lr.length) {
            html += '<section class="tk-sec"><div class="tk-body"><p class="tk-label">' + esc(sn.kicker) + '</p><h3 style="font:800 clamp(23px,2.8vw,30px)/1.15 var(--display,Archivo,sans-serif);color:var(--teal-deep,#0E5348);margin:0 0 14px">' + esc(sn.title) + "</h3>" +
              lr.map(function (s) {
                var cell = function (org, t) { return t ? "<div><b>" + esc(org) + "</b>" + esc(t) + "</div>" : ""; };
                return '<p class="tk-label" style="margin-top:18px">' + esc(SEC[s.id].s.name) + '</p><p class="tk-p"><b>' + esc(sn.learnings.head[1].charAt(0) + sn.learnings.head[1].slice(1).toLowerCase()) + '</b></p><div class="tk-sugg">' + cell(res.a.org, s.rightA) + cell(res.b.org, s.rightB) + '</div><p class="tk-p" style="margin-top:12px"><b>' + esc(sn.learnings.head[2].charAt(0) + sn.learnings.head[2].slice(1).toLowerCase()) + '</b></p><div class="tk-sugg">' + cell(res.a.org, s.diffA) + cell(res.b.org, s.diffB) + "</div>";
              }).join("") + "</div></section>";
          }
        }
        html += '<div class="tk-before"><div><b class="l">' + esc(sn.before.label) + "</b><p>" + esc(sn.before.text) + "</p><p>" + esc(sn.before.proceed[0]) + "<b>" + esc(sn.before.proceed[1]) + "</b></p></div></div>";
      } else {
        html += pr.sections.map(function (s) {
          return '<div class="tk-bar a" style="margin:0 0 8px"><span>' + esc(SEC[s.id].s.name) + '</span><span class="tr"><i style="width:' + (s.avgA / 5 * 100) + '%"></i></span><b>' + num(s.avgA) + "</b></div>";
        }).join("");
      }
      html += "</div>";
    });
    return html;
  }

  function renderResults(res, resultUrl, emailed) {
    var s = res.summary, name = function (id) { return SEC[id] ? SEC[id].s.name : ""; };
    var sent = "";
    if (emailed) {
      sent = emailed.a || emailed.b
        ? '<p class="tk-ok tk-noprint" style="margin:0 0 22px">We have emailed these results to both organisations, with a copy to U-CAN.</p>'
        : '<p class="tk-err tk-noprint" style="margin:0 0 22px">Your answers are saved, but the results email could not be sent just now. U-CAN will send it to you shortly - you can also keep this page’s link.</p>';
    }
    var stats = '<div class="tk-stats">' +
      '<div class="tk-stat"><b>' + (s.alignment == null ? "–" : s.alignment + "%") + "</b><span>of statements rated within one point of each other</span></div>" +
      '<div class="tk-stat"><b>' + s.gapCount + "</b><span>statement" + (s.gapCount === 1 ? "" : "s") + " with a difference of two points or more</span></div>" +
      '<div class="tk-stat"><b class="txt">' + esc(s.strengths.length ? name(s.strengths[0]) : "–") + "</b><span>strongest area</span></div>" +
      '<div class="tk-stat"><b class="txt">' + esc(s.attention.length ? name(s.attention[0]) : "None") + "</b><span>" + (s.attention.length ? "most in need of attention" : "area needing attention") + "</span></div></div>";
    var gaps = s.gaps.length
      ? '<div class="tk-card"><p class="tk-label">Where your organisations differed most</p><p class="tk-p tk-muted">' + esc(D.parts[0].scale.note) + '</p><ol class="tk-ol tk-gaps">' + s.gaps.map(function (g) {
          return "<li>" + esc(SEC[g.section].s.statements[g.i]) + "<small>" + esc(SEC[g.section].s.name) + " · " + esc(res.a.org) + ": " + g.a + " · " + esc(res.b.org) + ": " + g.b + "</small></li>";
        }).join("") + "</ol></div>"
      : "";
    show('<div class="tk-wrap"><div class="tk-dark"><p class="tk-label">BRINGING IT TOGETHER</p><h2>' + esc(res.a.org) + " &amp; " + esc(res.b.org) + "</h2><p>Joint results of the U-CAN Collaboration Toolkit · " + esc(res.date) + "</p>" +
      '<div class="tk-legend"><span><i class="ka">A</i>' + esc(res.a.org) + (res.a.person ? " (" + esc(res.a.person) + ")" : "") + '</span><span><i class="kb">B</i>' + esc(res.b.org) + (res.b.person ? " (" + esc(res.b.person) + ")" : "") + "</span></div>" +
      '<div class="tk-actions tk-noprint"><button type="button" class="tk-btn lime" id="tk-print">Download as PDF</button>' + (resultUrl ? '<input class="tk-hp" id="tk-self" value="' + esc(resultUrl) + '"><button type="button" class="tk-btn line" style="color:#fff;border-color:rgba(255,255,255,.6)" data-copy="tk-self">Copy link</button>' : "") + "</div></div>" +
      sent + stats + gaps + resultParts(res, false) + "</div>");
  }

  /* ------------------------------------------------------------- start */
  if (KEY) {
    show('<p class="tk-loading">Loading results…</p>');
    api("/result/" + KEY).then(function (j) {
      if (j.result.complete) renderResults(j.result, location.href, null);
      else renderWaiting(j.result, j.invite_url, location.href, false);
    }).catch(function (e) { message("Results not available", esc(e.message)); });
  } else if (PAIR) {
    show('<p class="tk-loading">Loading…</p>');
    api("/pair/" + PAIR).then(function (j) {
      if (j.complete) message("This toolkit is already complete", "Both organisations have already submitted their answers, and the joint results were emailed to both. If you need them again, please write to <a href=\"mailto:connect@urban.org.in\">connect@urban.org.in</a>.");
      else renderForm(j);
    }).catch(function (e) { message("This link does not work", esc(e.message)); });
  } else {
    renderForm(null);
  }
})();
