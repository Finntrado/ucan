# -*- coding: utf-8 -*-
"""Tests wp-theme/ucan/inc/canonical.php without WordPress or a web server.

Runs each scenario in php-cgi (so PHP_SAPI is not 'cli' and the CGI engine
prints the real response headers), with the handful of WordPress functions the
module needs stubbed. Scenarios cover every real-world way a visitor, crawler or
the WPVibe connector reaches the site.

  python _scripts/seo/test_canonical.py
"""
import glob
import os
import re
import subprocess
import sys
import tempfile

HERE = os.path.dirname(os.path.abspath(__file__))
ROOT = os.path.normpath(os.path.join(HERE, '..', '..'))
MODULE = os.path.join(ROOT, 'wp-theme', 'ucan', 'inc', 'canonical.php').replace('\\', '/')
PHP = (glob.glob(os.path.expanduser('~/AppData/Roaming/Local/lightning-services/php-*/bin/win64/php-cgi.exe')) or ['php-cgi'])[0]

STUB = r"""<?php
define('ABSPATH', '/');
$GLOBALS['filters'] = [];
function add_action($h, $cb, $p = 10, $n = 1) { $GLOBALS['filters'][$h][] = $cb; }
function add_filter($h, $cb, $p = 10, $n = 1) { $GLOBALS['filters'][$h][] = $cb; }
function is_ssl() { return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'; }
function wp_parse_url($u, $c = -1) { return parse_url($u, $c); }
function wp_doing_cron() { return false; }
function status_header($c) { http_response_code($c); }
function nocache_headers() { header('Cache-Control: no-cache'); }
function esc_attr($s) { return htmlspecialchars($s, ENT_QUOTES); }
%(scenario)s
parse_str((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $_GET);
require '%(module)s';
%(body)s
"""

CASES = []  # (name, server-vars, php body, expectations: list of regex that must match the CGI output)


def case(name, server, body, *expect, forbid=()):
    CASES.append((name, server, body, expect, forbid))


ENF = 'ucan_enforce_https_and_host(); echo "PASSED-THROUGH";'
H = lambda host, https=False, uri='/about', method='GET': dict(
    HTTP_HOST=host, REQUEST_URI=uri, REQUEST_METHOD=method, **({'HTTPS': 'on'} if https else {}))

# ---- public pages -------------------------------------------------------------
case('apex http  -> 301 www https', H('urban.org.in'), ENF, r'Status: 301', r'Location: https://www\.urban\.org\.in/about\r?\n')
case('www  http  -> 301 www https', H('www.urban.org.in'), ENF, r'Status: 301', r'Location: https://www\.urban\.org\.in/about\r?\n')
case('apex https -> 301 www https', H('urban.org.in', True), ENF, r'Status: 301', r'Location: https://www\.urban\.org\.in/about\r?\n')
case('www  https -> served (no redirect)', H('www.urban.org.in', True), ENF, r'PASSED-THROUGH', forbid=(r'Location:',))
case('query string kept', H('urban.org.in', True, '/about/?x=1&y=2'), ENF, r'Location: https://www\.urban\.org\.in/about/\?x=1&y=2')
case('POST on apex -> 308 (keeps body)', H('urban.org.in', True, '/about', 'POST'), ENF, r'Status: 308', r'Location: https://www\.urban\.org\.in/about')
case('behind proxy (X-Forwarded-Proto https)', dict(H('www.urban.org.in'), HTTP_X_FORWARDED_PROTO='https'), ENF, r'PASSED-THROUGH', forbid=(r'Location:',))
case('behind Cloudflare (CF-Visitor https)', dict(H('www.urban.org.in'), HTTP_CF_VISITOR='{"scheme":"https"}'), ENF, r'PASSED-THROUGH', forbid=(r'Location:',))
case('robots.txt on apex http -> www https', H('urban.org.in', False, '/robots.txt'), ENF, r'Location: https://www\.urban\.org\.in/robots\.txt')
case('sitemap.xml on apex https -> www', H('urban.org.in', True, '/sitemap.xml'), ENF, r'Location: https://www\.urban\.org\.in/sitemap\.xml')
# ---- backend: https forced, host NEVER changed ---------------------------------
case('wp-login http  -> https SAME host (apex)', H('urban.org.in', False, '/wp-login.php'), ENF, r'Location: https://urban\.org\.in/wp-login\.php')
case('wp-login https -> untouched', H('urban.org.in', True, '/wp-login.php'), ENF, r'PASSED-THROUGH', forbid=(r'Location:',))
case('wp-admin http  -> https same host', H('urban.org.in', False, '/wp-admin/options.php'), ENF, r'Location: https://urban\.org\.in/wp-admin/options\.php')
case('REST https on apex (WPVibe) -> untouched', H('urban.org.in', True, '/wp-json/ucan/v1/subscribe', 'POST'), ENF, r'PASSED-THROUGH', forbid=(r'Location:',))
case('REST POST over http -> 308 https same host', H('urban.org.in', False, '/wp-json/x', 'POST'), ENF, r'Status: 308', r'Location: https://urban\.org\.in/wp-json/x')
case('?rest_route= counts as REST', H('urban.org.in', True, '/?rest_route=/wp/v2/posts'), ENF, r'PASSED-THROUGH', forbid=(r'Location:',))
case('xmlrpc http -> https same host', H('urban.org.in', False, '/xmlrpc.php', 'POST'), ENF, r'Status: 308', r'Location: https://urban\.org\.in/xmlrpc\.php')
# ---- dev / other hosts are left completely alone -------------------------------
case('LocalWP host untouched', H('ucan-test.local'), ENF, r'PASSED-THROUGH', forbid=(r'Location:',))
case('vercel preview host untouched', H('u-can-puce.vercel.app'), ENF, r'PASSED-THROUGH', forbid=(r'Location:',))
case('lookalike host is NOT production', H('urban.org.in.evil.example'), ENF, r'PASSED-THROUGH', forbid=(r'Location:',))
case('Host header with CRLF is rejected', H('urban.org.in\r\nX-Injected: 1'), ENF, r'PASSED-THROUGH', forbid=(r'X-Injected',))
case('URI with CRLF cannot inject headers', H('urban.org.in', True, '/a\r\nSet-Cookie: x=1'), ENF, r'Location: https://www\.urban\.org\.in/\r?\n', forbid=(r'Set-Cookie',))
# ---- author enumeration --------------------------------------------------------
case('/?author=1 -> 404', H('www.urban.org.in', True, '/?author=1'), ENF, r'Status: 404', forbid=(r'admin',))
case('/author/admin/ -> 404', H('www.urban.org.in', True, '/author/admin/'), ENF, r'Status: 404')
# ---- head canonicalisation -----------------------------------------------------
HTML = ('<head><link rel="canonical" href="http://ucan-test.local/about/?utm=1#x">'
        '<link rel="alternate" hreflang="en-in" href="http://ucan-test.local/about">'
        '<link rel="alternate" hreflang="x-default" href="https://urban.org.in/about">'
        '<meta property="og:url" content="https://urban.org.in/about">'
        '<meta property="og:image" content="http://ucan-test.local/wp-content/themes/ucan/assets/img/og-default.jpg">'
        '<meta name="twitter:image" content="https://www.urban.org.in/x.jpg">'
        '<script type="application/ld+json" data-ucan="schema">{"@id":"http://ucan-test.local/#org","url":"http://ucan-test.local/about","sameAs":["https://www.linkedin.com/company/x"],"logo":"https://urban.org.in/a.svg"}</script>'
        '</head><body><a href="http://ucan-test.local/rfc">nav link stays on the current host</a></body>')
case('canonical/og/hreflang/JSON-LD forced to www', H('ucan-test.local'),
     'echo ucan_canonicalise_head(%s, "http://ucan-test.local");' % ('<<<H\n' + HTML + '\nH'),
     r'rel="canonical" href="https://www\.urban\.org\.in/about/?"',
     r'hreflang="en-in" href="https://www\.urban\.org\.in/about"', r'hreflang="x-default" href="https://www\.urban\.org\.in/about"',
     r'og:url" content="https://www\.urban\.org\.in/about"',
     r'og:image" content="https://www\.urban\.org\.in/wp-content/themes/ucan/assets/img/og-default\.jpg"',
     r'twitter:image" content="https://www\.urban\.org\.in/x\.jpg"',
     r'"@id":"https://www\.urban\.org\.in/#org"', r'"logo":"https://www\.urban\.org\.in/a\.svg"',
     r'"sameAs":\["https://www\.linkedin\.com/company/x"\]',
     r'<a href="http://ucan-test\.local/rfc">', forbid=(r'\?utm', r'#x', r'href="https://urban\.org\.in'))
case('ucan_on_origin swaps host on production only', H('www.urban.org.in', True),
     'echo ucan_on_origin("https://urban.org.in/wp-content/themes/ucan", "https://www.urban.org.in"), "|", ucan_public_origin("https://urban.org.in");',
     r'https://www\.urban\.org\.in/wp-content/themes/ucan\|https://www\.urban\.org\.in')
case('ucan_on_origin leaves dev hosts alone', H('ucan-test.local'),
     'echo ucan_on_origin("http://ucan-test.local/wp-content/themes/ucan", "http://ucan-test.local"), "|", ucan_public_origin("http://ucan-test.local");',
     r'http://ucan-test\.local/wp-content/themes/ucan\|http://ucan-test\.local')
case('allowed redirect hosts include www + apex', H('www.urban.org.in'),
     '$f = $GLOBALS["filters"]["allowed_redirect_hosts"][0]; echo implode(",", $f(["urban.org.in"]));',
     r'www\.urban\.org\.in', r'urban\.org\.in')
# ---- robots.txt ----------------------------------------------------------------
ROBOTS = '$f = $GLOBALS["filters"]["robots_txt"][0]; echo $f("Disallow: /\\n", %s);'
case('robots.txt: public site', H('www.urban.org.in', True), ROBOTS % 'true',
     r'User-agent: \*\r?\nAllow: /\r?\n', r'Disallow: /wp-admin/', r'Allow: /wp-admin/admin-ajax\.php',
     r'User-agent: GPTBot', r'User-agent: OAI-SearchBot', r'User-agent: ChatGPT-User', r'User-agent: ClaudeBot',
     r'User-agent: PerplexityBot', r'User-agent: Google-Extended', r'User-agent: Applebot-Extended', r'User-agent: CCBot',
     r'Sitemap: https://www\.urban\.org\.in/sitemap\.xml', forbid=(r'Disallow: /\r?\n',))
case('robots.txt: "Discourage search engines" is respected', H('www.urban.org.in', True), ROBOTS % 'false',
     r'Disallow: /\r?\n', forbid=(r'GPTBot', r'Sitemap'))
case('robots.txt: no sensitive paths disclosed', H('www.urban.org.in', True), ROBOTS % 'true',
     r'Disallow: /wp-json/', forbid=(r'import-data', r'subscribers', r'/inc/', r'pages/', r'backup'))


# ---- llms.txt ------------------------------------------------------------------
LLMS = ('file_put_contents(sys_get_temp_dir() . "/llms.txt", "# U-CAN test\n"); '
        'function get_template_directory() { return sys_get_temp_dir(); } ucan_serve_llms_txt(); echo "AFTER";')
case('/llms.txt is served as plain text', H('www.urban.org.in', True, '/llms.txt'), LLMS,
     r'Content-Type: text/plain; charset=UTF-8', r'X-Content-Type-Options: nosniff', r'# U-CAN test', forbid=(r'AFTER', r'Status: 4'))
case('other paths are not touched by the llms route', H('www.urban.org.in', True, '/about'), LLMS, r'AFTER', forbid=(r'# U-CAN test',))
case('/llms.txt cannot be used to read other files', H('www.urban.org.in', True, '/llms.txt/../../wp-config.php'), LLMS, r'AFTER', forbid=(r'# U-CAN test',))


def run(server, body):
    def php(v):  # PHP double-quoted string, so CR/LF really are control characters
        return '"' + v.replace('\\', '\\\\').replace('"', '\\"').replace('$', '\\$').replace('\r', '\\r').replace('\n', '\\n') + '"'
    scenario = ''.join('$_SERVER[%s] = %s;\n' % (php(k), php(v)) for k, v in server.items())
    code = STUB % dict(scenario=scenario, module=MODULE, body=body)
    with tempfile.NamedTemporaryFile('w', suffix='.php', delete=False, encoding='utf-8') as f:
        f.write(code)
    try:
        r = subprocess.run([PHP, '-n', f.name], capture_output=True, timeout=60)
        return (r.stdout + r.stderr).decode('utf-8', 'replace')
    finally:
        os.unlink(f.name)


def main():
    bad = 0
    for name, server, body, expect, forbid in CASES:
        out = run(server, body)
        missing = [e for e in expect if not re.search(e, out)]
        present = [x for x in forbid if re.search(x, out)]
        if missing or present:
            bad += 1
            print('FAIL  %s\n      missing: %s  forbidden-present: %s\n      output: %r' % (name, missing, present, out[:300]))
        else:
            print('ok    %s' % name)
    print('\n%d scenarios, %d failing' % (len(CASES), bad))
    sys.exit(1 if bad else 0)


if __name__ == '__main__':
    main()
