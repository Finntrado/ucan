"""Builds snippet.php (the WPCode snippet body, no opening tag) with the exact
CSS / markup strings read from the repo, so nothing is retyped by hand."""
import io, re, sys

REPO = '/home/user/ucan'
OUT = sys.argv[1]

om = io.open(REPO + '/standalone/our-members.html', encoding='utf-8', newline='').read()

# ---- NEW css tail + markup, taken from the repo page ----
m = re.search(r'<style data-ucan="rh">\.tk\{align-items:center\}(.*?</style>)', om, re.S)
assert m, 'rh style not found'
NEW_CSS_TAIL = m.group(1)
assert NEW_CSS_TAIL.startswith('.tk-cover{display:block;justify-self:center;width:100%;max-width:300px')

m = re.search(r'(<span class="tk-book">.*?<span class="tk-gloss"></span></span>\n      </a>)', om, re.S)
assert m, 'new markup not found'
NEW_MARKUP = m.group(1)

# ---- OLD (what is live now) ----
OLD_CSS_TAIL = (
    ".tk-cover{display:block;justify-self:center;max-width:280px;width:100%;"
    "box-shadow:0 18px 40px -18px rgba(14,83,72,.45);transition:transform .35s cubic-bezier(.2,.7,.3,1)}"
    ".tk-cover img{display:block;width:100%;height:auto}"
    ".tk-cover:hover,.tk-cover:focus-visible{transform:translateY(-4px)}"
    "@media (prefers-reduced-motion:reduce){.tk-cover{transition:none}}</style>"
)
OLD_MARKUP = NEW_MARKUP.replace('<span class="tk-book"><span class="tk-back"></span>', '', 1) \
                       .replace('<span class="tk-gloss"></span></span>', '', 1)
assert OLD_MARKUP.startswith('<img src="/wp-content/uploads/2026/11/Roots-and-Horizons-cover.webp"')
assert OLD_MARKUP.endswith('">\n      </a>')

for name, val in (('NEW_CSS_TAIL', NEW_CSS_TAIL), ('OLD_CSS_TAIL', OLD_CSS_TAIL),
                  ('NEW_MARKUP', NEW_MARKUP), ('OLD_MARKUP', OLD_MARKUP)):
    assert 'UCAN_NOWDOC_END' not in val, name

tpl = io.open(sys.argv[2], encoding='utf-8', newline='').read()
out = (tpl.replace('@@OLD_CSS_TAIL@@', OLD_CSS_TAIL)
          .replace('@@NEW_CSS_TAIL@@', NEW_CSS_TAIL)
          .replace('@@OLD_MARKUP@@', OLD_MARKUP)
          .replace('@@NEW_MARKUP@@', NEW_MARKUP))
io.open(OUT, 'w', encoding='utf-8', newline='\n').write(out)
print('snippet written:', len(out), 'bytes')
