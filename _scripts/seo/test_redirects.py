# -*- coding: utf-8 -*-
"""Tests inc/redirects.php against the REAL generated rules (redirects-extra.php),
the real 404 template (pages/404.html) and the real page-title index, in php-cgi
with the few WordPress functions it needs stubbed.

  python _scripts/wp/build_static_theme.py && python _scripts/seo/test_redirects.py

Checks: every kind of legacy URL resolves to the right 301/410; nothing that
should not be redirected is; every 301 target really exists (no redirect to a
404, no chains); the 404 page is a real 404 with noindex, no canonical,
suggestions, popular links, and gets logged; the 410 page says "removed".
"""
import glob
import json
import os
import re
import subprocess
import sys
import tempfile

HERE = os.path.dirname(os.path.abspath(__file__))
ROOT = os.path.normpath(os.path.join(HERE, '..', '..'))
THEME = os.path.join(ROOT, 'wp-theme', 'ucan').replace('\\', '/')
PHP = (glob.glob(os.path.expanduser('~/AppData/Roaming/Local/lightning-services/php-*/bin/win64/php-cgi.exe')) or ['php-cgi'])[0]

STUB = r"""<?php
define('ABSPATH', '/');
$GLOBALS['opt'] = []; $GLOBALS['hooks'] = [];
function add_action($h, $cb, $p = 10, $n = 1) { $GLOBALS['hooks'][$h][] = $cb; }
function add_filter($h, $cb, $p = 10, $n = 1) { $GLOBALS['hooks'][$h][] = $cb; }
function is_ssl() { return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'; }
function wp_parse_url($u, $c = -1) { return parse_url($u, $c); }
function wp_doing_cron() { return false; }
function status_header($c) { http_response_code($c); }
function nocache_headers() { header('Cache-Control: no-cache'); }
function esc_attr($s) { return htmlspecialchars($s, ENT_QUOTES); }
function esc_html($s) { return htmlspecialchars($s, ENT_QUOTES); }
function esc_url_raw($s) { return $s; }
function untrailingslashit($s) { return rtrim($s, '/'); }
function home_url($p = '') { return 'https://www.urban.org.in' . $p; }
function rest_url($p = '') { return 'https://www.urban.org.in/wp-json/' . $p; }
function get_template_directory() { return '%(theme)s'; }
function get_template_directory_uri() { return 'https://www.urban.org.in/wp-content/themes/ucan'; }
function get_option($k, $d = false) { return $GLOBALS['opt'][$k] ?? $d; }
function update_option($k, $v, $a = null) { $GLOBALS['opt'][$k] = $v; return true; }
function ucan_security_headers() { header('X-Content-Type-Options: nosniff'); }
function wp_safe_redirect($u, $c = 302) { header('Location: ' . $u, true, $c); exit; }
%(server)s
require '%(theme)s/inc/canonical.php';
require '%(theme)s/inc/redirects.php';
%(body)s
"""


def run(server, body):
    srv = ''.join('$_SERVER[%s] = %s;\n' % (json.dumps(k), json.dumps(v)) for k, v in server.items())
    code = STUB % dict(theme=THEME, server=srv, body=body)
    with tempfile.NamedTemporaryFile('w', suffix='.php', delete=False, encoding='utf-8') as f:
        f.write(code)
    try:
        r = subprocess.run([PHP, '-n', f.name], capture_output=True, timeout=60)
        return (r.stdout + r.stderr).decode('utf-8', 'replace')
    finally:
        os.unlink(f.name)


SERVER = dict(HTTP_HOST='www.urban.org.in', HTTPS='on', REQUEST_METHOD='GET')
bad = 0


def ok(name, cond, detail=''):
    global bad
    print(('ok    ' if cond else 'FAIL  ') + name + ('' if cond else '\n      ' + str(detail)[:400]))
    if not cond:
        bad += 1


# ---------------------------------------------------------------- resolver table
first_upload = re.search(r"'(wp-content/uploads/[^']+)' => '([^']+)'", open(os.path.join(THEME, 'redirects-extra.php'), encoding='utf-8').read())
TABLE = {
    'about-us-2': [301, '/about'],
    'About-Us-2/': [301, '/about'],
    'category/fellow-blogs/page/3': [301, '/fellow-blogs'],
    'category/manisha': [301, '/profile-fellow-manisha-bisht'],
    'tag/air-pollution': [301, '/blog-tag-air-quality'],
    'tag/jaipur': [301, '/fellow-blogs'],
    'member/some-removed-person': [301, '/our-people'],
    'member/viraj-tyagi': [301, '/profile-viraj-tyagi'],
    'shreya/pilots-as-proof-of-possibility': [301, '/blog-pilots-as-proof-of-possibility-2'],
    'past-sessions/thinking-better-alone-together': [301, '/ld-thinking-better-alone-together'],
    'past-sessions/something-else': [301, '/ld-calendar'],
    'etn_category/policy-webinar': [301, '/policy-webinars'],
    'etn_category/whatever': [301, '/city-mixers'],
    'roots-and-horizons': [301, '/impact'],
    'social-media-feed': [301, '/'],
    'sitemap_index.xml': [301, '/sitemap.xml'],
    'timeline_slider_post/december-2025': [301, '/about'],
    'careers': [410, None],
    'author/sucharitha-venkatesh/page/2': [410, None],
    'case-study/post-4-lorem-ipsum-dolor-sit-amet-consectetur-adipiscing-elit': [410, None],
    '2024/08/14': [410, None],
    'etn/event-oct': [410, None],
    'etn/never-heard-of-it': [410, None],
    'feed': [410, None],
    'wp-content/plugins/elementor/assets/css/x.css': [410, None],
    'wp-content/themes/twentytwentyfive/style.css': [410, None],
    'wp-content/uploads/2099/01/never-existed.png': [410, None],
    ':80/woods/price-list.html': [410, None],
    'totally-unknown-page': None,
    'wp-content/themes/ucan/assets/img/missing.png': None,   # our own theme is never blanket-410'd
    'about': None,                                           # real pages are not the resolver's business
}
if first_upload:
    TABLE[first_upload.group(1)] = [301, first_upload.group(2)]
keys = json.dumps(list(TABLE))
php = '$r = ucan_legacy_rules(); $o = array(); foreach (json_decode(%s, true) as $k) { $o[$k] = ucan_resolve_legacy($k, $r); } echo json_encode($o);' % json.dumps(keys)
raw = run(SERVER, php)
try:
    got = json.loads(raw[raw.index('{'):])
except Exception:
    print('FAIL  resolver produced no JSON:', raw[:300])
    sys.exit(1)
for k, exp in TABLE.items():
    ok('resolve %-70s -> %s' % (k[:70], exp), got.get(k) == exp, 'got %r' % (got.get(k),))

# ---------------------------------------------------------------- data integrity
ex = open(os.path.join(THEME, 'redirects-extra.php'), encoding='utf-8').read()
canon = open(os.path.join(THEME, 'redirects.php'), encoding='utf-8').read()
pages = {f[:-5] for f in os.listdir(os.path.join(ROOT, 'standalone')) if f.endswith('.html')}
targets = set(re.findall(r"^\s*'[^']*' => '(/[^']*)',", ex, re.M))
missing = []
for t in targets:
    if t in ('/', '/sitemap.xml'):
        continue
    if t.startswith('/wp-content/themes/ucan/'):
        if not os.path.exists(os.path.join(ROOT, 'standalone', t[len('/wp-content/themes/ucan/'):])):
            missing.append(t)
    elif t[1:] not in pages:
        missing.append(t)
ok('every 301 target exists (%d targets)' % len(targets), not missing, missing[:5])
keys_ex = set(re.findall(r"^\s*'([^']*)' => '/", ex, re.M))
keys_canon = set(re.findall(r"^\s*'([^']*)' =>", canon, re.M))
ok('no rule shadows a canonical-map redirect', not (keys_ex & keys_canon), sorted(keys_ex & keys_canon)[:5])
ok('no rule key is a live page', not (keys_ex & pages), sorted(keys_ex & pages)[:5])
chain = [k for k in keys_ex if k in keys_canon or ('/' + k) in {'/' + p for p in pages}]
ok('no redirect chains (a target is never itself redirected)', not [t for t in targets if t[1:] in keys_ex or t[1:] in keys_canon], '')

# ---------------------------------------------------------------- the pages
def page(uri, code, extra=None, host='www.urban.org.in'):
    s = dict(SERVER, HTTP_HOST=host, REQUEST_URI=uri)
    s.update(extra or {})
    return run(s, 'ucan_serve_error_page(%d); echo "AFTER";' % code)


o = page('/urban-reforms-collectiv', 404)
ok('404: status 404', 'Status: 404' in o, o[:200])
ok('404: heading + popular pages', "This page isn't here" in o and 'Popular pages' in o and 'href="/about"' in o)
ok('404: noindex header + meta', 'X-Robots-Tag: noindex' in o and 'name="robots" content="noindex, follow"' in o)
ok('404: no canonical, no JSON-LD, no unresolved placeholders', 'rel="canonical"' not in o and 'ld+json' not in o and '%%UCAN' not in o)
ok('404: "did you mean" suggests the right page for a typo', 'Did you mean' in o and 'href="/urban-reforms-collective"' in o, re.findall(r'e404-sugg.{0,300}', o)[:1])
ok('404: stops after rendering (exit)', 'AFTER' not in o)
ok('404: assets/links resolved to the www origin', 'https://www.urban.org.in/wp-content/themes/ucan/assets' in o)
ok('404: pending (unpublished) pages are not linked or suggested', 'urban-perspectives' not in o[o.find('<main'):o.find('</main>')] and 'urban-collaboration-checklist' not in o[o.find('<main'):o.find('</main>')])  # the menu comes from the shell page

o = page('/zzzqqq-nothing-like-this', 404)
ok('404: no bogus suggestion for junk', 'Did you mean' not in o)
o = page('/annual-forum', 404)
ok('404: partial slug suggests annual-forum-2025', 'href="/annual-forum-2025"' in o)

o = page('/careers', 410)
ok('410: status 410 + "removed" wording', 'Status: 410' in o and 'This page has been removed' in o and 'Page removed | U-CAN' in o, o[:200])
lg410 = run(dict(SERVER, REQUEST_URI='/careers'), 'ucan_serve_error_page(410);')
lg410 = run(dict(SERVER, REQUEST_URI='/careers'), 'register_shutdown_function(function () { echo "\nLOG:" . json_encode(get_option("ucan_404_log", array())); }); ucan_serve_error_page(410);')
ok('410: not logged as a broken link (only true 404s are)', 'LOG:[]' in lg410, lg410[-120:])

log = run(dict(SERVER, REQUEST_URI='/some-old-page', HTTP_REFERER='https://example.org/blog/post?token=secret#x'),
          'ucan_log_404("/some-old-page"); ucan_log_404("/some-old-page"); ucan_log_404("/wp-login.php"); ucan_log_404("/.env"); ucan_log_404("/x.php?a=1");'
          ' $_SERVER["REQUEST_METHOD"]="POST"; ucan_log_404("/posted"); echo json_encode(get_option("ucan_404_log"));')
try:
    lg = json.loads(log[log.index('{'):])
except Exception:
    lg = {}
ok('404 log: counts hits, keeps referrer HOST only, ignores scanners and POSTs',
   lg.get('/some-old-page', {}).get('n') == 2 and lg['/some-old-page'].get('r') == 'example.org'
   and '/wp-login.php' not in lg and '/.env' not in lg and '/x.php' not in lg and '/posted' not in lg and 'secret' not in log, log[:300])

cap = run(SERVER, '$l = array(); for ($i = 0; $i < 305; $i++) { $l["/p$i"] = array("n" => 1, "t" => 1, "r" => ""); } update_option("ucan_404_log", $l); ucan_log_404("/one-more"); echo count(get_option("ucan_404_log"));')
ok('404 log: capped at 300 entries (no unbounded growth from crawlers)', cap.strip().endswith('305'), cap[:100])

o = run(dict(SERVER, REQUEST_URI='/x'), 'ucan_legacy_redirect("about-us-2", "https://www.urban.org.in");')
ok('legacy 301 goes to the www origin, one hop', 'Status: 301' in o and 'Location: https://www.urban.org.in/about' in o, o[:200])
o = run(dict(SERVER, REQUEST_URI='/x'), 'ucan_legacy_redirect("unknown-thing", "https://www.urban.org.in"); echo "NO-RULE";')
ok('no rule: returns so the 404 page can render', 'NO-RULE' in o)

print('\n%d failing' % bad)
sys.exit(1 if bad else 0)
