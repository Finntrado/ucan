# -*- coding: utf-8 -*-
"""Tests wp-theme/ucan/inc/fastcache.php without WordPress or a web server.

Same approach as test_canonical.py: each scenario runs in php-cgi with the few
WordPress functions it needs stubbed, in its own temp folder standing in for the
site root. Loopback requests are mocked, so what is covered here is the decision
logic and above all the rollback: whatever a self-test answers, the root
.htaccess must end up either correct or byte-identical to how it started.
Whether Apache itself accepts the rules is checked live by the preflight step.

  UCAN_PHP=<path to php-cgi> python _scripts/seo/test_fastcache.py
"""
import glob
import os
import re
import shutil
import subprocess
import sys
import tempfile

HERE = os.path.dirname(os.path.abspath(__file__))
ROOT = os.path.normpath(os.path.join(HERE, '..', '..'))
INC = os.path.join(ROOT, 'wp-theme', 'ucan', 'inc').replace('\\', '/')
PHP = os.environ.get('UCAN_PHP') or (glob.glob(os.path.expanduser('~/AppData/Roaming/Local/lightning-services/php-*/bin/win64/php-cgi.exe')) or ['php-cgi'])[0]

WP_RULES = "# BEGIN WordPress\nRewriteEngine On\nRewriteRule . /index.php [L]\n# END WordPress\n"

STUB = r"""<?php
$S = '%(site)s';
define('ABSPATH', $S . '/');
define('WP_CONTENT_DIR', $S . '/wp-content');
define('DAY_IN_SECONDS', 86400);
$GLOBALS['opt'] = []; $GLOBALS['hooks'] = []; $GLOBALS['urls'] = [];
function add_action($h, $cb, $p = 10, $n = 1) { $GLOBALS['hooks'][$h][] = $cb; }
function add_filter($h, $cb, $p = 10, $n = 1) {}
function is_ssl() { return ($_SERVER['HTTPS'] ?? '') === 'on'; }
function wp_parse_url($u, $c = -1) { return parse_url($u, $c); }
function wp_doing_cron() { return false; }
function esc_attr($s) { return $s; }
function get_option($k, $d = false) { return $GLOBALS['opt'][$k] ?? $d; }
function update_option($k, $v) { $GLOBALS['opt'][$k] = $v; return true; }
function add_option($k, $v, $a = '', $b = false) { if (isset($GLOBALS['opt'][$k])) return false; $GLOBALS['opt'][$k] = $v; return true; }
function delete_option($k) { unset($GLOBALS['opt'][$k]); }
function wp_mkdir_p($d) { return is_dir($d) || mkdir($d, 0777, true); }
function content_url($p = '') { return 'https://urban.org.in/wp-content/' . $p; }
function home_url($p = '') { return 'https://urban.org.in' . $p; }
function get_template_directory() { return ABSPATH . 'theme'; }
function wp_next_scheduled($h) { return 1; }
function wp_schedule_event($a, $b, $c) {}
function is_wp_error($r) { return !is_array($r); }
function wp_remote_retrieve_response_code($r) { return $r['code']; }
function wp_remote_retrieve_header($r, $h) { return $r['h'][$h] ?? ''; }
function wp_remote_retrieve_body($r) { return $r['body'] ?? ''; }
function ok($hit = true) { return ['code' => 200, 'h' => ($hit ? ['x-ucan-cache' => 'HIT'] : []) + ['content-security-policy' => 'x'], 'body' => '<html></html>']; }
// a healthy server; a scenario overrides single URLs through $GLOBALS['mock']
function wp_remote_get($url, $a = []) {
	$GLOBALS['urls'][] = $url;
	foreach ($GLOBALS['mock'] ?? [] as $needle => $resp) { if (false !== strpos($url, $needle)) return $resp; }
	if (false !== strpos($url, '_preflight')) return ['code' => 200, 'h' => [], 'body' => 'ok'];
	if (false !== strpos($url, 'ucan-cache/')) return ['code' => 403, 'h' => []];
	if (false !== strpos($url, 'wp-login')) return ['code' => 200, 'h' => [], 'body' => 'login'];
	if (false !== strpos($url, 'ucanselftest')) return ok(false);
	if (0 === strpos($url, 'https://urban.org.in/') || 0 === strpos($url, 'http://')) return ['code' => 301, 'h' => ['location' => 'https://www.urban.org.in/about']];
	return ok(true);
}
function root() { return file_get_contents(ABSPATH . '.htaccess'); }
function sec_headers() {
	header("Strict-Transport-Security: max-age=63072000; includeSubDomains");
	header("Cross-Origin-Opener-Policy: same-origin");
	header("Content-Security-Policy: default-src 'self'");
	header("X-Content-Type-Options: nosniff");
	header("X-Frame-Options: DENY");
	header("Referrer-Policy: strict-origin-when-cross-origin");
	header("Permissions-Policy: camera=()");
}
mkdir(ABSPATH . 'theme'); mkdir(ABSPATH . 'wp-content');
file_put_contents(ABSPATH . 'theme/functions.php', 'x');
file_put_contents(ABSPATH . '.htaccess', "%(wp_rules)s");
%(scenario)s
require '%(inc)s/canonical.php';
require '%(inc)s/fastcache.php';
%(body)s
"""

CASES = []


def case(name, server, body, *expect, forbid=()):
    CASES.append((name, server, body, expect, forbid))


def www(**over):
    d = dict(HTTP_HOST='www.urban.org.in', HTTPS='on', REQUEST_METHOD='GET', REQUEST_URI='/about',
             SERVER_SOFTWARE='Apache', QUERY_STRING='')
    d.update(over)
    return {k: v for k, v in d.items() if v is not None}


CAN = 'echo ucan_fc_can_save() ? "CAN-SAVE" : "NO-SAVE";'
# ---- what may be saved ---------------------------------------------------------
case('plain anonymous GET of www https: saved', www(), CAN, r'CAN-SAVE')
case('apex: never', www(HTTP_HOST='urban.org.in'), CAN, r'NO-SAVE')
case('lookalike host: never', www(HTTP_HOST='www.urban.org.in.evil.example'), CAN, r'NO-SAVE')
case('plain http: never', www(HTTPS=None), CAN, r'NO-SAVE')
case('spoofed X-Forwarded-Proto on http: never', www(HTTPS=None, HTTP_X_FORWARDED_PROTO='https'), CAN, r'NO-SAVE')
case('POST: never', www(REQUEST_METHOD='POST'), CAN, r'NO-SAVE')
case('HEAD: never (nothing to save)', www(REQUEST_METHOD='HEAD'), CAN, r'NO-SAVE')
case('query string: never', www(QUERY_STRING='x=1'), CAN, r'NO-SAVE')
case('nginx (LocalWP): never', www(SERVER_SOFTWARE='nginx'), CAN, r'NO-SAVE')
case('logged-in cookie: never', www(), '$_COOKIE["wordpress_logged_in_abc"] = "x";' + CAN, r'NO-SAVE')
case('password-post cookie: never', www(), '$_COOKIE["wp-postpass_abc"] = "x";' + CAN, r'NO-SAVE')
case('analytics cookie does not block', www(), '$_COOKIE["_ga"] = "x";' + CAN, r'CAN-SAVE')
case('off switch: never', www(), 'update_option("ucan_fc_off", 1);' + CAN, r'NO-SAVE')

# ---- saving --------------------------------------------------------------------
SAVE = 'sec_headers(); ucan_fc_save("about", "<html>BYTES</html>");'
SHOW = 'echo "|FILE=", @file_get_contents(WP_CONTENT_DIR . "/ucan-cache/about.html"), "|RULES=", @file_get_contents(WP_CONTENT_DIR . "/ucan-cache/.htaccess");'
case('saves exact bytes with the headers PHP sent', www(), SAVE + SHOW,
     r'FILE=<html>BYTES</html>', r'Header set Content-Security-Policy "default-src \'self\'"',
     r'Header set Strict-Transport-Security "max-age=63072000; includeSubDomains"', r'Header set X-Frame-Options "DENY"',
     r'Header set Referrer-Policy', r'Header set Permissions-Policy', r'Header set Cross-Origin-Opener-Policy',
     r'Header set X-Content-Type-Options', r'Header set Cache-Control "no-cache"', r'REDIRECT_STATUS\} \^\$', r'RewriteRule \^ - \[F\]')
case('a missing security header means nothing is saved', www(),
     'header("X-Frame-Options: DENY"); ucan_fc_save("about", "<html>"); echo "|EXISTS=", var_export(is_file(WP_CONTENT_DIR . "/ucan-cache/about.html"), true);',
     r'EXISTS=false')
case('home is stored under a name /index can never match', www(), 'sec_headers(); ucan_fc_save("index", "<html>"); echo "|HOME=", var_export(is_file(WP_CONTENT_DIR . "/ucan-cache/__home.html"), true), "|INDEX=", var_export(is_file(WP_CONTENT_DIR . "/ucan-cache/index.html"), true);',
     r'HOME=true', r'INDEX=false')
case('double quotes in a header value cannot break out of the rule', www(),
     'echo ucan_fc_dir_rules(["X-Test" => "a\\"b"]);', r'Header set X-Test "a\\"b"')

# ---- the root block ------------------------------------------------------------
RULES = 'echo ucan_fc_root_rules();'
case('backend and REST are excluded from the host redirect', www(), RULES, r'REQUEST_URI\} !\^/wp-', r'QUERY_STRING\} !rest_route=')
case('redirect is GET/HEAD only (a POST must stay on PHP for its 308)', www(), RULES, r'REQUEST_METHOD\} \^\(GET\|HEAD\)\$\nRewriteCond %\{HTTP_HOST\} \^\(www')
case('files only for https, www, no query, no login cookie', www(), RULES,
     r'HTTP_HOST\} =www\.urban\.org\.in\nRewriteCond %\{HTTPS\} =on\nRewriteCond %\{QUERY_STRING\} \^\$\nRewriteCond %\{HTTP_COOKIE\} !\(wordpress_logged_in_')
case('only an existing file is ever served', www(), RULES, r'ucan-cache/\$1\.html" -f', r'ucan-cache/__home\.html" -f')
case('slash and dotted URLs fall through to WordPress', www(), RULES, r'RewriteRule \^\(\[a-z0-9-\]\+\)\$ /wp-content/ucan-cache/\$1\.html \[L\]')

# ---- install + rollback --------------------------------------------------------
INSTALL = 'sec_headers(); ucan_fc_save("about", "<html></html>"); ucan_fc_install();'
RESULT = ('$r = root(); echo "|SAME=", var_export($r === "%s", true), "|FIRST=", var_export(0 === strpos($r, UCAN_FC_BEGIN), true), '
          '"|PAGE=", var_export(is_file(WP_CONTENT_DIR . "/ucan-cache/about.html"), true), "|STATE=", json_encode(get_option("ucan_fc_state")), "|LOCK=", var_export(get_option("ucan_fc_lock"), true);'
          % WP_RULES.replace('\n', '\\n'))
BLOCK_OK = (r'SAME=false', r'FIRST=true', r'PAGE=true', r'"rules":"[0-9a-f]{32}"', r'LOCK=false')


def rollback(name, mock, why):
    case(name, www(), mock + INSTALL + RESULT, r'SAME=true', r'PAGE=false', r'"failed":"[0-9a-f]{32}"', r'"why":"' + why, r'LOCK=false', forbid=(r'"rules"',))


case('healthy server: block goes first, WordPress rules kept', www(), INSTALL + RESULT + 'echo "|", root();', *BLOCK_OK, r'# BEGIN WordPress\nRewriteEngine On\nRewriteRule \. /index\.php \[L\]\n# END WordPress')
case('installing twice leaves one block', www(), INSTALL + 'ucan_fc_install(); echo "|COUNT=", substr_count(root(), UCAN_FC_BEGIN);', r'COUNT=1')
rollback('cache folder rules broken (500): root untouched', '$GLOBALS["mock"] = ["ucan-cache/about.html" => ["code" => 500, "h" => []]];', 'cache directory rules')
rollback('Apache rejects the block in the test folder: root untouched', '$GLOBALS["mock"] = ["_preflight" => ["code" => 500, "h" => []]];', 'rules rejected')
rollback('loopback unreachable: root untouched', '$GLOBALS["mock"] = ["ucan-cache/about.html" => "error"];', 'cache directory rules')
rollback('page not from file: restored', '$GLOBALS["mock"] = ["www.urban.org.in/about" => ok(false), "ucanselftest" => ok(false)];', 'page not served')
rollback('page has no CSP: restored', '$GLOBALS["mock"] = ["www.urban.org.in/about" => ["code" => 200, "h" => ["x-ucan-cache" => "HIT"], "body" => "</html>"]];', 'page not served')
rollback('query string served from file: restored', '$GLOBALS["mock"] = ["ucanselftest" => ok(true)];', 'query string')
rollback('login page broken: restored', '$GLOBALS["mock"] = ["wp-login" => ["code" => 500, "h" => []]];', 'login page')
rollback('apex redirect wrong: restored', '$GLOBALS["mock"] = ["https://urban.org.in/about" => ok(false)];', 'apex redirect')
rollback('http redirect loop: restored', '$GLOBALS["mock"] = ["http://www.urban.org.in/about" => ["code" => 301, "h" => ["location" => "http://www.urban.org.in/about"]]];', 'http redirect')
case('a failed install is not retried and saves nothing', www(),
     '$GLOBALS["mock"] = ["wp-login" => ["code" => 500, "h" => []]];' + INSTALL +
     '$GLOBALS["urls"] = []; ucan_fc_save("about", "<html></html>"); echo "|PAGE=", var_export(is_file(WP_CONTENT_DIR . "/ucan-cache/about.html"), true), "|HOOKS=", count($GLOBALS["hooks"]["shutdown"] ?? []);',
     r'PAGE=false', r'HOOKS=1')
case('the test folder is removed afterwards', www(), INSTALL + 'echo "|TRY=", var_export(is_dir(WP_CONTENT_DIR . "/ucan-cache/_preflight"), true);', r'TRY=false')
case('a stuck lock is released, not fatal', www(), 'update_option("ucan_fc_lock", time() - 999); ucan_fc_install(); echo "|LOCK=", var_export(get_option("ucan_fc_lock"), true), "|SAME=", var_export(false === strpos(root(), UCAN_FC_BEGIN), true);', r'LOCK=false', r'SAME=true')
case('a live lock blocks a second run', www(), 'update_option("ucan_fc_lock", time()); ucan_fc_install(); echo "|SAME=", var_export(false === strpos(root(), UCAN_FC_BEGIN), true), "|URLS=", count($GLOBALS["urls"]);', r'SAME=true', r'URLS=0')

# ---- keeping files honest ------------------------------------------------------
case('new theme version drops the files', www(),
     INSTALL + 'update_option("ucan_fc_state", ["build" => "old"] + get_option("ucan_fc_state")); ucan_fc_maintain(); echo "|PAGE=", var_export(is_file(WP_CONTENT_DIR . "/ucan-cache/about.html"), true), "|RULES=", var_export(false !== strpos(root(), UCAN_FC_BEGIN), true);',
     r'PAGE=false', r'RULES=true')
case('same theme version keeps the files', www(),
     INSTALL + 'ucan_fc_maintain(); ucan_fc_save("about", "<html></html>"); ucan_fc_maintain(); echo "|PAGE=", var_export(is_file(WP_CONTENT_DIR . "/ucan-cache/about.html"), true);', r'PAGE=true')
case('off switch restores the original file byte for byte', www(),
     INSTALL + 'update_option("ucan_fc_off", 1); ucan_fc_maintain();' + RESULT, r'SAME=true', r'PAGE=false', r'STATE=false')
case('switching theme restores the original file', www(),
     INSTALL + '$GLOBALS["hooks"]["switch_theme"][0]();' + RESULT, r'SAME=true', r'PAGE=false')
case('content and plugin changes drop the files', www(),
     'foreach (["save_post","activated_plugin","deactivated_plugin","upgrader_process_complete","update_option_wpcode_snippets"] as $h) { echo "|", $h, "=", count($GLOBALS["hooks"][$h] ?? []); }',
     r'save_post=1', r'activated_plugin=1', r'deactivated_plugin=1', r'upgrader_process_complete=1', r'update_option_wpcode_snippets=1')
case('daily job notices when the block was overwritten', www(),
     INSTALL + 'file_put_contents(ABSPATH . ".htaccess", "' + WP_RULES.replace('\n', '\\n') + '"); ucan_fc_daily(); echo "|STATE=", json_encode(get_option("ucan_fc_state"));',
     r'STATE=', forbid=(r'"rules"',))


def run(server, body):
    def php(v):
        return '"' + v.replace('\\', '\\\\').replace('"', '\\"').replace('$', '\\$') + '"'
    site = tempfile.mkdtemp().replace('\\', '/')
    scenario = ''.join('$_SERVER[%s] = %s;\n' % (php(k), php(v)) for k, v in server.items())
    code = STUB % dict(site=site, inc=INC, wp_rules=WP_RULES.replace('\n', '\\n'), scenario=scenario, body=body)
    path = site + '.php'
    with open(path, 'w', encoding='utf-8', newline='\n') as f:
        f.write(code)
    try:
        r = subprocess.run([PHP, '-n', path], capture_output=True, timeout=60)
        return (r.stdout + r.stderr).decode('utf-8', 'replace').replace('\r\n', '\n')
    finally:
        os.unlink(path)
        shutil.rmtree(site, ignore_errors=True)


def main():
    bad = 0
    for name, server, body, expect, forbid in CASES:
        out = run(server, body)
        missing = [e for e in expect if not re.search(e, out)]
        present = [x for x in forbid if re.search(x, out)]
        if re.search(r'(Fatal error|Parse error|Warning:|Uncaught)', out):
            missing.append('no PHP errors')
        if missing or present:
            bad += 1
            print('FAIL  %s\n      missing: %s  forbidden-present: %s\n      output: %r' % (name, missing, present, out[-700:]))
        else:
            print('ok    %s' % name)
    print('\n%d scenarios, %d failing' % (len(CASES), bad))
    sys.exit(1 if bad else 0)


if __name__ == '__main__':
    main()
