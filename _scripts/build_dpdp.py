# -*- coding: utf-8 -*-
"""Build standalone/data-rights.html - U-CAN's DPDP Act 2023 data-rights page.

Shell (head, header, footer, cookie banner, scripts) comes from
privacy-policy.html so it matches the rest of the site. The request form
composes an email to privacy@urban.org.in in the visitor's own mail app: the
site has no backend, and the form deliberately asks for no email address
(the mail app sends from the visitor's own), so nothing is collected here.
Also adds a "Your Data Rights" link to every page's footer and a pointer at
the top of the Privacy Policy. Re-runnable.
"""
import glob, io, os, re, time

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'standalone')
SLUG = 'data-rights'
URL = 'https://urban.org.in/%s/' % SLUG
TITLE = 'Your Data Rights (DPDP Act, 2023) | U-CAN'
DESC = ('What U-CAN holds when you subscribe, why, for how long, and how to withdraw consent, '
        'access, correct or erase your data under the Digital Personal Data Protection Act, 2023.')
CONSENT = ('I agree that U-CAN may use my email address to send its newsletter, event invitations and '
           'programme details. I can withdraw this consent at any time by writing to privacy@urban.org.in.')


def ic(path):
    return ('<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" '
            'stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">%s</svg>' % path)


ICONS = {
    'withdraw': ic('<path d="M3 12a9 9 0 1 0 18 0 9 9 0 0 0-18 0"/><path d="M8 12h8"/>'),
    'access': ic('<path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>'),
    'correct': ic('<path d="M4 20h4L19 9l-4-4L4 16v4z"/><path d="M13.5 6.5l4 4"/>'),
    'erase': ic('<path d="M4 7h16M10 11v6M14 11v6M6 7l1 13h10l1-13M9 7V4h6v3"/>'),
    'nominate': ic('<circle cx="9" cy="8" r="3.2"/><path d="M3 20c.6-3.4 3-5.5 6-5.5s5.4 2.1 6 5.5"/><path d="M18 8v6M15 11h6"/>'),
    'grievance': ic('<path d="M4 5h16v11H9l-5 4V5z"/><path d="M12 8v3M12 13.5v.01"/>'),
}

RIGHTS = [
    ('withdraw', 'Withdraw your consent',
     'You can withdraw your consent at any time, and it is as easy as giving it. We stop sending you '
     'email and erase your subscription data, unless a law requires us to keep it.', 'Section 6(4)'),
    ('access', 'Access your data',
     'Ask for a summary of the personal data we hold about you and how we use it.', 'Section 11'),
    ('correct', 'Correct or update it',
     'Ask us to correct, complete or update anything that is inaccurate or out of date.', 'Section 12'),
    ('erase', 'Erase it',
     'Ask us to erase your data. We delete it and write back to confirm it has been done.', 'Section 12'),
    ('nominate', 'Nominate someone',
     'Name a person who can exercise these rights on your behalf if you die or become unable to.',
     'Section 14'),
    ('grievance', 'Raise a grievance',
     'Tell us about any concern with how we handle your data. If our response does not resolve it, you '
     'can complain to the Data Protection Board of India.', 'Section 13'),
]

REQ_LABEL = {'withdraw': 'Withdraw my consent', 'access': 'Access my data', 'correct': 'Correct my data',
             'erase': 'Erase my data', 'nominate': 'Nominate someone', 'grievance': 'Raise a grievance'}


def rights_cards():
    out = []
    for i, (k, t, d, sec) in enumerate(RIGHTS):
        out.append(
            '<article class="dr-card rv d%d">' % (i % 3 + 1) +
            '<span class="dr-ic">%s</span>' % ICONS[k] +
            '<h3>%s</h3><p>%s</p>' % (t, d) +
            '<div class="dr-foot"><span class="dr-sec">DPDP Act, %s</span>' % sec +
            '<a class="dr-go" href="#request" data-req="%s">Make this request <span aria-hidden="true">&#8594;</span></a>'
            % k + '</div></article>')
    return ''.join(out)


def req_options():
    return ''.join('<label class="dr-opt"><input type="radio" name="req" value="%s"%s><span>%s</span></label>'
                   % (k, ' checked' if k == 'withdraw' else '', v) for k, v in REQ_LABEL.items())


MAIN = ('<main id="main">'
        '<section class="hero" aria-labelledby="pt"><div class="hero-in">'
        '<nav class="crumb" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> '
        '<span>Your Data Rights</span></nav>'
        '<p class="hero-tag">Data protection</p>'
        '<h1 id="pt">Your Data Rights</h1>'
        '<p class="hero-lede">What U-CAN holds when you subscribe, why, and for how long, and how to withdraw '
        'your consent or have your data corrected or erased under the Digital Personal Data Protection Act, 2023.</p>'
        '<div class="hero-actions"><a class="btn on-photo" href="#request">Make a request '
        '<span class="ar" aria-hidden="true">&#8594;</span></a>'
        '<a class="btn ghost-photo" href="#rights">See your rights</a></div>'
        '</div></section>'

        # --- at a glance
        '<section class="sec" aria-labelledby="dr-g"><div class="wrap">'
        '<div class="sec-head rv"><p class="kicker" data-num="&#8212;">At a glance</p>'
        '<h2 id="dr-g">What we hold, and why</h2></div>'
        '<div class="dr-glance">'
        '<dl class="dr-facts rv">'
        '<div><dt>Data Fiduciary</dt><dd>Urban Collective Action Network (U-CAN)</dd></div>'
        '<div><dt>What we hold</dt><dd>Your email address, and a record of your consent: when you gave it and '
        'the version of the notice you agreed to.</dd></div>'
        '<div><dt>How long</dt><dd>Up to 10 years from the date you give consent, or until you withdraw it or ask '
        'us to erase your data, whichever comes first.</dd></div>'
        '<div><dt>Contact</dt><dd><a href="mailto:privacy@urban.org.in">privacy@urban.org.in</a></dd></div>'
        '</dl>'
        '<figure class="dr-consent rv d1">'
        '<p class="dr-consent-k">What you agree to when you subscribe</p>'
        '<blockquote><p>%s</p></blockquote>'
        '<figcaption>U-CAN is the Data Fiduciary for this data under the Digital Personal Data Protection Act, '
        '2023. We use it only for this purpose.</figcaption>'
        '</figure>'
        '</div></div></section>' % CONSENT +

        # --- rights
        '<section class="sec alt" id="rights" aria-labelledby="dr-r"><div class="wrap">'
        '<div class="sec-head rv"><p class="kicker" data-num="&#8212;">Under the DPDP Act, 2023</p>'
        '<h2 id="dr-r">Your rights</h2>'
        '<p class="lead">Each of these is free, and each can be used at any time by writing to '
        '<a href="mailto:privacy@urban.org.in">privacy@urban.org.in</a>, or with the form below.</p></div>'
        '<div class="dr-grid">' + rights_cards() + '</div>'
        '</div></section>'

        # --- retention
        '<section class="sec" aria-labelledby="dr-t"><div class="wrap">'
        '<div class="sec-head rv"><p class="kicker" data-num="&#8212;">Retention</p>'
        '<h2 id="dr-t">How long we keep it</h2></div>'
        '<ol class="dr-time rv">'
        '<li><b>You subscribe</b><span>You tick the consent box and give your email address.</span></li>'
        '<li><b>We use it for one purpose</b><span>Our newsletter, event invitations and programme details.</span></li>'
        '<li><b>Up to 10 years</b><span>The longest we keep your subscription data.</span></li>'
        '<li><b>Erased</b><span>Deleted at the end of that period.</span></li>'
        '</ol>'
        '<p class="dr-branch rv d1"><b>Or sooner, whenever you choose.</b> Withdraw your consent or ask for erasure '
        'at any point and we erase your data then, unless a law requires us to keep it.</p>'
        '</div></section>'

        # --- request
        '<section class="sec alt" id="request" aria-labelledby="dr-q"><div class="wrap">'
        '<div class="sec-head rv"><p class="kicker" data-num="&#8212;">Make a request</p>'
        '<h2 id="dr-q">Tell us what you would like us to do</h2></div>'
        '<div class="dr-req">'
        '<form class="dr-form rv" id="dr-form" novalidate>'
        '<fieldset><legend>Your request</legend><div class="dr-opts">' + req_options() + '</div></fieldset>'
        '<label class="dr-lbl" for="dr-note">Anything we should know <span>(optional)</span></label>'
        '<textarea id="dr-note" rows="4" placeholder="For a correction, what should change? For a nomination, '
        'who, and how we can reach them."></textarea>'
        '<button type="submit" class="btn">Write the email <span class="ar" aria-hidden="true">&#8594;</span></button>'
        '<p class="dr-small">This opens your email app with the request written out, addressed to '
        'privacy@urban.org.in. Nothing is sent until you press send there, and nothing you type here is stored '
        'or sent anywhere else. Please send it from the email address you subscribed with.</p>'
        '<noscript><p class="dr-small">Or write to <a href="mailto:privacy@urban.org.in">privacy@urban.org.in</a> '
        'saying which request you are making.</p></noscript>'
        '</form>'
        '<aside class="dr-next rv d1" aria-labelledby="dr-n">'
        '<h3 id="dr-n">What happens next</h3>'
        '<ol class="dr-steps">'
        '<li><b>We check it is you.</b> We confirm the request comes from the email address it concerns.</li>'
        '<li><b>We act on it.</b> We withdraw, share, correct or erase as you asked, and write back to confirm.</li>'
        '<li><b>If you are not satisfied.</b> Reply and tell us. If we cannot resolve it, you can complain to the '
        'Data Protection Board of India.</li>'
        '</ol>'
        '<p class="dr-small">See also our <a href="privacy-policy">Privacy Policy</a>.</p>'
        '</aside>'
        '</div></div></section>'
        '</main>')

CSS = ('<style data-ucan="dpdp">'
       '.dr-glance{display:grid;grid-template-columns:minmax(0,1.05fr) minmax(0,.95fr);gap:clamp(24px,3.6vw,52px);'
       'align-items:stretch}'
       '.dr-facts{margin:0;display:grid;gap:0;border-top:1px solid var(--line,#DCEAE6)}'
       '.dr-facts>div{display:grid;grid-template-columns:150px minmax(0,1fr);gap:18px;padding:16px 0;'
       'border-bottom:1px solid var(--line,#DCEAE6)}'
       ".dr-facts dt{font-family:var(--sans,'Public Sans',sans-serif);font-size:12.5px;font-weight:700;"
       'letter-spacing:.1em;text-transform:uppercase;color:var(--teal-text,#177A69);padding-top:3px}'
       '.dr-facts dd{margin:0;font-size:16px;line-height:1.6;color:var(--ink,#222120)}'
       '.dr-facts a,.dr-small a,.lead a{color:var(--teal-text,#177A69);font-weight:600}'
       '.dr-consent{margin:0;padding:clamp(26px,3.2vw,40px);background:var(--teal-deep,#0E5348);color:#FBFAF6;'
       'display:flex;flex-direction:column;justify-content:center;gap:18px}'
       ".dr-consent-k{margin:0!important;font-family:var(--sans,'Public Sans',sans-serif);font-size:12px!important;"
       'font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--lime,#CDDE71)}'
       '.dr-consent blockquote{margin:0;padding-left:18px;border-left:3px solid var(--lime,#CDDE71)}'
       '.dr-consent blockquote p{margin:0!important;font-family:var(--display,Archivo,sans-serif)!important;'
       'font-weight:500;font-size:clamp(17px,1.6vw,19px)!important;line-height:1.55!important;color:#FBFAF6}'
       '.dr-consent figcaption{font-size:13.5px;line-height:1.55;color:#B9C4BF}'
       # rights
       '.dr-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}'
       '.dr-card{display:flex;flex-direction:column;gap:10px;padding:clamp(22px,2.4vw,28px);background:var(--paper,#FBFAF6);'
       'border:1px solid var(--line,#DCEAE6);transition:transform .3s ease,box-shadow .3s ease,border-color .3s ease}'
       '.dr-card:hover,.dr-card:focus-within{transform:translateY(-4px);border-color:var(--teal,#1F8F7B);'
       'box-shadow:0 14px 30px -18px rgba(14,83,72,.35)}'
       '.dr-ic{display:inline-flex;width:44px;height:44px;align-items:center;justify-content:center;'
       'background:var(--paper-alt,#E9F5F2);color:var(--teal-deep,#0E5348);margin-bottom:4px}'
       '.dr-card h3{margin:0}'
       '.dr-card p{margin:0;font-size:15px;line-height:1.6;color:var(--ink-soft,#57564F)}'
       '.dr-foot{margin-top:auto;padding-top:14px;display:flex;justify-content:space-between;align-items:center;'
       'gap:10px;flex-wrap:wrap;border-top:1px dashed var(--line,#DCEAE6)}'
       '.dr-sec{font-size:12px;font-weight:600;color:var(--ink-soft,#57564F)}'
       '.dr-go{font-size:14px;font-weight:700;color:var(--teal-text,#177A69);text-decoration:none}'
       '.dr-go:hover,.dr-go:focus-visible{text-decoration:underline}'
       # retention timeline
       '.dr-time{list-style:none;margin:0;padding:0;display:grid;grid-template-columns:repeat(4,minmax(0,1fr));'
       'gap:0;counter-reset:dt}'
       '.dr-time li{position:relative;padding:34px 18px 0 0;counter-increment:dt}'
       '.dr-time li::before{content:"";position:absolute;left:0;right:0;top:9px;height:2px;background:var(--line,#DCEAE6)}'
       '.dr-time li::after{content:"";position:absolute;left:0;top:2px;width:16px;height:16px;border-radius:50%;'
       'background:var(--paper,#FBFAF6);border:3px solid var(--teal,#1F8F7B);box-sizing:border-box}'
       '.dr-time li:last-child::after{background:var(--teal-deep,#0E5348);border-color:var(--teal-deep,#0E5348)}'
       '.dr-time li:last-child::before{right:auto;width:0}'
       '.dr-time b{display:block;font-family:var(--display,Archivo,sans-serif);font-size:18px;color:var(--ink,#222120)}'
       '.dr-time span{display:block;margin-top:6px;font-size:14.5px;line-height:1.55;color:var(--ink-soft,#57564F)}'
       '.dr-branch{margin:clamp(26px,3vw,36px) 0 0!important;padding:18px 22px;background:var(--paper-alt,#E9F5F2);'
       'border-left:3px solid var(--teal,#1F8F7B);font-size:16px!important;color:var(--ink,#222120)}'
       # request
       '.dr-req{display:grid;grid-template-columns:minmax(0,1.15fr) minmax(0,.85fr);gap:clamp(24px,3.6vw,52px);'
       'align-items:stretch}'
       '.dr-form{padding:clamp(24px,3vw,36px);background:var(--paper,#FBFAF6);border:1px solid var(--line,#DCEAE6)}'
       '.dr-form fieldset{margin:0;padding:0;border:0}'
       ".dr-form legend,.dr-lbl{font-family:var(--sans,'Public Sans',sans-serif);font-size:12.5px;font-weight:700;"
       'letter-spacing:.1em;text-transform:uppercase;color:var(--ink,#222120);margin-bottom:12px;padding:0}'
       '.dr-lbl{display:block;margin:22px 0 8px}.dr-lbl span{text-transform:none;letter-spacing:0;font-weight:500;'
       'color:var(--ink-soft,#57564F)}'
       '.dr-opts{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px}'
       '.dr-opt{position:relative;display:flex;align-items:center;gap:10px;padding:12px 14px;cursor:pointer;'
       'border:1px solid var(--line,#DCEAE6);background:#fff;font-size:15px;font-weight:600;color:var(--ink,#222120);'
       'transition:border-color .2s ease,background .2s ease}'
       '.dr-opt input{accent-color:var(--teal,#1F8F7B);width:16px;height:16px;margin:0}'
       '.dr-opt:has(input:checked){border-color:var(--teal,#1F8F7B);background:var(--paper-alt,#E9F5F2)}'
       '.dr-opt:has(input:focus-visible){outline:2px solid var(--teal,#1F8F7B);outline-offset:2px}'
       '.dr-form textarea{width:100%;box-sizing:border-box;padding:12px 14px;border:1px solid var(--line,#DCEAE6);'
       'background:#fff;font:inherit;font-size:15px;line-height:1.55;color:var(--ink,#222120);resize:vertical}'
       '.dr-form textarea:focus-visible{outline:2px solid var(--teal,#1F8F7B);outline-offset:1px}'
       '.dr-form .btn{margin-top:18px}'
       '.dr-small{margin:14px 0 0!important;font-size:13.5px!important;line-height:1.6!important;'
       'color:var(--ink-soft,#57564F)}'
       '.dr-next{padding:clamp(24px,3vw,36px);background:var(--teal-deep,#0E5348);color:#FBFAF6;'
       'display:flex;flex-direction:column}.dr-next .dr-small{margin-top:auto!important;padding-top:18px}'
       '.dr-next h3{margin:0 0 16px;color:#FBFAF6!important}'
       '.dr-next ol{margin:0;padding:0;list-style:none;counter-reset:nx}'
       '.dr-next li{position:relative;padding:14px 0 14px 44px;border-top:1px solid rgba(251,250,246,.16);'
       'font-size:15px;line-height:1.6;color:#DCEAE6;counter-increment:nx}'
       '.dr-next li::before{content:counter(nx,decimal-leading-zero);position:absolute;left:0;top:14px;'
       'font-family:var(--display,Archivo,sans-serif);font-weight:800;color:var(--lime,#CDDE71)}'
       '.dr-next li b{color:#FBFAF6}'
       '.dr-next .dr-small{color:#B9C4BF}.dr-next .dr-small a{color:var(--lime,#CDDE71)}'
       '@media(max-width:980px){.dr-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}'
       '@media(max-width:860px){.dr-glance,.dr-req{grid-template-columns:1fr}'
       '.dr-time{grid-template-columns:1fr 1fr;row-gap:26px}}'
       '@media(max-width:560px){.dr-grid,.dr-opts{grid-template-columns:1fr}'
       '.dr-facts>div{grid-template-columns:1fr;gap:4px}.dr-time{grid-template-columns:1fr}}'
       '@media(prefers-reduced-motion:reduce){.dr-card{transition:none}.dr-card:hover{transform:none}}'
       '</style>')

JS = ('<script data-ucan="dpdp">'
      '(function(){var f=document.getElementById("dr-form");if(!f)return;'
      'var L={withdraw:"Withdraw my consent",access:"Access my data",correct:"Correct my data",'
      'erase:"Erase my data",nominate:"Nominate someone",grievance:"Raise a grievance"};'
      'var B={withdraw:"I withdraw my consent for U-CAN to use my email address to send its newsletter, event '
      'invitations and programme details. Please stop sending me email and erase my subscription data.",'
      'access:"Please send me a summary of the personal data U-CAN holds about me and how it is processed.",'
      'correct:"Please correct or update my personal data as described below.",'
      'erase:"Please erase all personal data U-CAN holds about me and confirm when this is done.",'
      'nominate:"I would like to nominate the person below to exercise my rights under the DPDP Act on my behalf.",'
      'grievance:"I would like to raise a grievance about how U-CAN has handled my personal data."};'
      # the rights cards preselect their request
      'Array.prototype.forEach.call(document.querySelectorAll("[data-req]"),function(a){'
      'a.addEventListener("click",function(){var r=f.querySelector("input[value="+a.getAttribute("data-req")+"]");'
      'if(r)r.checked=true;});});'
      'f.addEventListener("submit",function(e){e.preventDefault();'
      'var k=(f.querySelector("input[name=req]:checked")||{}).value||"withdraw";'
      'var note=document.getElementById("dr-note").value.trim();'
      'var body=B[k]+"\\n\\n"+(note?"Details:\\n"+note+"\\n\\n":"")+'
      '"This request concerns the email address I am writing from.\\n\\n(Sent under the Digital Personal Data '
      'Protection Act, 2023.)";'
      'location.href="mailto:privacy@urban.org.in?subject="+encodeURIComponent("[DPDP request] "+L[k])+'
      '"&body="+encodeURIComponent(body);});})();'
      '</script>')


def write(f, s):
    for _ in range(6):
        try:
            io.open(f, 'w', encoding='utf-8', newline='').write(s)
            return True
        except OSError:
            time.sleep(0.35)
    return False


# ---- the page ---------------------------------------------------------------
src = io.open(os.path.join(ROOT, 'privacy-policy.html'), encoding='utf-8', newline='').read()
s = src.replace('https://urban.org.in/privacy-policy/', URL)
s = s.replace('Privacy Policy | U-CAN', TITLE)
s = s.replace('How U-CAN collects, uses and discloses your information, and your rights over that data.', DESC)
s = re.sub(r'<title>.*?</title>', '<title>%s</title>' % TITLE, s, count=1)
# breadcrumb name in the JSON-LD
s = s.replace('"name": "Privacy Policy"', '"name": "Your Data Rights"')
a = s.index('<main'); b = s.index('</main>') + len('</main>')
s = s[:a] + MAIN + s[b:]
s = re.sub(r'<style data-ucan="dpdp">.*?</style>', '', s, flags=re.S)
s = re.sub(r'<script data-ucan="dpdp">.*?</script>', '', s, flags=re.S)
s = s.replace('</head>', CSS + '</head>', 1).replace('</body>', JS + '</body>', 1)
write(os.path.join(ROOT, SLUG + '.html'), s)
print('built', SLUG + '.html')

# ---- footer link on every page, and a pointer from the Privacy Policy --------
LINK = '<a href="privacy-policy">Privacy Policy</a><a href="%s">Your Data Rights</a>' % SLUG
n = 0
for f in sorted(glob.glob(os.path.join(ROOT, '*.html'))):
    t = io.open(f, encoding='utf-8', newline='').read()
    o = t
    if 'href="%s">Your Data Rights</a>' % SLUG not in t:
        t = t.replace('<a href="privacy-policy">Privacy Policy</a><button', LINK + '<button', 1)
    if os.path.basename(f) == 'privacy-policy.html' and 'class="dr-pointer"' not in t:
        t = t.replace('<div class="post-body rv" style="max-width:80ch">',
                      '<p class="dr-pointer" style="max-width:80ch;margin:0 0 28px;padding:16px 20px;'
                      'background:var(--paper-alt,#E9F5F2);border-left:3px solid var(--teal,#1F8F7B);font-size:16px">'
                      'For your rights under the Digital Personal Data Protection Act, 2023, including how to '
                      'withdraw consent or have your data erased, see <a href="%s" style="color:var(--teal-text,#177A69);'
                      'font-weight:600">Your Data Rights</a>.</p>'
                      '<div class="post-body rv" style="max-width:80ch">' % SLUG, 1)
    if t != o:
        n += write(f, t)
print('footer link / pointer added on %d pages' % n)
