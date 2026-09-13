# -*- coding: utf-8 -*-
"""Teach the shared newsletter gate (inline behaviour JS on every page) to
actually SAVE a signup when the form says where to.

A form carrying data-endpoint="<url>" gets the validated email + consent
POSTed there (the WordPress theme adds it, pointing at its own
/wp-json/ucan/v1/subscribe, which writes the subscriber table). The success
message only shows once the server confirms; a failure shows a retry message
instead of pretending it worked.

A form WITHOUT data-endpoint (the static Vercel build, which has no backend)
keeps the old behaviour. Idempotent.
"""
import glob
import io
import os
import re
import time

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'standalone')
MARK = 'getAttribute("data-endpoint")'

RESET = re.compile(r'(e\.preventDefault\(\);\s*)(toggle\(err, false\); toggle\(ok, false\);)')
SUCCESS = re.compile(
    r'try \{ console\.log\("\[U-CAN\] newsletter consent record", record\); \} catch \(e2\) \{\}'
    r'\s*form\.reset\(\);\s*toggle\(ok, true\);')

NEW_SUCCESS = r'''try { console.log("[U-CAN] newsletter consent record", record); } catch (e2) {}

        var endpoint = form.getAttribute("data-endpoint");
        if (!endpoint || !window.fetch) { form.reset(); toggle(ok, true); return; }

        var btn = $("button[type=submit]", form);
        if (btn) btn.disabled = true;
        var body = new FormData();
        body.append("email", record.email);
        body.append("consent", "yes");
        body.append("notice_version", record.noticeVersion);
        body.append("page", record.page);
        fetch(endpoint, { method: "POST", body: body, credentials: "same-origin", headers: { Accept: "application/json" } })
          .then(function (r) {
            return r.json().catch(function () { return {}; }).then(function (j) {
              if (!r.ok || !j.ok) throw new Error("subscribe failed");
            });
          })
          .then(function () { form.reset(); toggle(ok, true); })
          .catch(function () {
            if (err) {
              if (!err.dataset.msg) err.dataset.msg = err.textContent;
              err.textContent = "Sorry, we couldn't save your subscription just now. Please try again in a moment.";
            }
            toggle(err, true);
          })
          .then(function () { if (btn) btn.disabled = false; });'''

NEW_RESET = r'\1if (err && err.dataset.msg) err.textContent = err.dataset.msg;\n        \2'

done = skipped = 0
for f in sorted(glob.glob(os.path.join(ROOT, '*.html'))):
    s = io.open(f, encoding='utf-8', newline='').read()
    if MARK in s:
        skipped += 1
        continue
    s, a = RESET.subn(NEW_RESET, s)
    s, b = SUCCESS.subn(lambda m: NEW_SUCCESS, s)
    if (a, b) != (1, 1):
        raise SystemExit('%s: expected 1+1 matches in the newsletter gate, got %d+%d' % (os.path.basename(f), a, b))
    for attempt in range(20):  # Windows OSError 22 retry, see localonly.py
        try:
            with io.open(f, 'w', encoding='utf-8', newline='') as fh:
                fh.write(s)
            break
        except OSError:
            if attempt == 19:
                raise
            time.sleep(0.5)
    done += 1
print('newsletter gate patched on %d pages (%d already patched)' % (done, skipped))
