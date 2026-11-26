import io, sys
slug, old_re, new, key, tpl, out = sys.argv[1:7]
s = io.open(tpl, encoding='utf-8', newline='').read()
s = s.replace('@@SLUG@@', slug).replace('@@OLD_RE@@', old_re).replace('@@NEW@@', new).replace('@@KEY@@', key)
io.open(out, 'w', encoding='utf-8', newline='\n').write(s)
print('snippet written:', len(s), 'bytes')
