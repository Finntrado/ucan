# -*- coding: utf-8 -*-
"""Fixes a real, sitewide bug found on the first actual render against a
live WordPress install (LocalWP): phase 0 assumed <style data-ucan="base">
was byte-identical across every standalone/*.html page (checked only
against about.html) and baked that one copy into header.php as if it were
universal. It isn't - the first 8568 characters (reset/tokens/typography/
generic shell components) really are shared across every page checked,
but several pages append their OWN extra component CSS inside that same
tag afterwards, which header.php's fixed copy never carried:

  front-page.php (home)                         <- index.html
  page-our-people.php                           <- our-people.html      (.pcard/.grp/.people)
  page-impact.php                               <- impact.html
  page-learning-network-for-urban-managers.php  <- learning-network.html
  page-newsletter.php                           <- newsletter.html
  single-ucan_member.php                        <- any profile-*.html   (checked 3, byte-identical)
  single-ucan_newsletter.php (designed branch)   <- any designed newsletter issue (checked 3, byte-identical)

Every other already-built page/template's own base block was checked and
found byte-identical to about.html's, so header.php's existing CSS already
covers them correctly - not touched here.

Writes the extra CSS into a new $ucan_page_css global, read by
functions.php's ucan_document_head() (the same mechanism already used for
$ucan_page_meta/$ucan_page_jsonld), inserted immediately before each
file's one get_header() call.
"""
import io
import os

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', '..')
STANDALONE = os.path.join(ROOT, 'standalone')
THEME = os.path.join(ROOT, 'wp-theme', 'ucan')

SHARED_PREFIX_LEN = 8544


def base_suffix(fname, whole=False):
    """whole=True for index.html: checked separately (see fix_page_css.py's
    module docstring / commit history) and its base block diverges from
    the shared prefix almost immediately (it even overrides shared :root
    tokens like --wrap/--radius) - it's one of the original hand-unpacked
    pages with its own bespoke stylesheet lineage (CLAUDE.md §23), not a
    shared-prefix-plus-suffix case like the other 6. Emitting its whole
    block as page CSS still layers correctly: a later :root redeclaration
    of the same custom property wins the cascade, so this overrides
    header.php's about.html-derived tokens for the homepage specifically
    without needing to touch header.php itself."""
    s = io.open(os.path.join(STANDALONE, fname), encoding='utf-8').read()
    i = s.index('<style data-ucan="base">') + len('<style data-ucan="base">')
    j = s.index('</style>', i)
    block = s[i:j]
    if whole:
        return block.strip()
    assert block[:SHARED_PREFIX_LEN] == base_suffix.SHARED_PREFIX, \
        '%s: shared prefix mismatch - re-check SHARED_PREFIX_LEN' % fname
    suffix = block[SHARED_PREFIX_LEN:]
    # SHARED_PREFIX_LEN lands mid-rule (the rule whose selector is in the
    # shared prefix but whose closing brace is just past it) - slicing
    # there left a dangling "prop: value; }" with no selector, invalid
    # CSS. Rules are one-per-line with a blank line between them here, so
    # cut at the first clean rule boundary instead of the raw byte offset.
    boundary = suffix.index('}\n\n') + len('}\n\n')
    return suffix[boundary:].strip()


# the known-shared prefix, read once from about.html, used to assert every
# other file's prefix really does match before trusting the suffix split
_about = io.open(os.path.join(STANDALONE, 'about.html'), encoding='utf-8').read()
_i = _about.index('<style data-ucan="base">') + len('<style data-ucan="base">')
base_suffix.SHARED_PREFIX = _about[_i:_i + SHARED_PREFIX_LEN]


def php_string(s):
    return "'" + s.replace('\\', '\\\\').replace("'", "\\'") + "'"


TARGETS = [
    ('front-page.php', 'index.html', True),
    ('page-our-people.php', 'our-people.html', False),
    ('page-impact.php', 'impact.html', False),
    ('page-learning-network-for-urban-managers.php', 'learning-network.html', False),
    # newsletter.html and every newsletter-*.html issue diverge from
    # position 0 too (checked) - a separate bespoke stylesheet lineage
    # from the rest of the site (§20's own history), not a
    # shared-prefix-plus-suffix case.
    ('page-newsletter.php', 'newsletter.html', True),
    ('single-ucan_member.php', 'profile-siddharth-pandit.html', False),
    ('single-ucan_newsletter.php', 'newsletter-april-2025.html', True),
]

for php_file, source_file, whole in TARGETS:
    css = base_suffix(source_file, whole=whole)
    path = os.path.join(THEME, php_file)
    content = io.open(path, encoding='utf-8').read()
    marker = 'get_header();'
    count = content.count('\n' + marker + '\n')
    if count != 1:
        raise SystemExit('%s: expected exactly one standalone get_header(); line, found %d' % (php_file, count))
    injected = "$ucan_page_css = %s;\n\n%s" % (php_string(css), marker)
    content = content.replace('\n' + marker + '\n', '\n' + injected + '\n', 1)
    io.open(path, 'w', encoding='utf-8', newline='\n').write(content)
    print('patched %s with %d chars of page CSS from %s' % (php_file, len(css), source_file))
