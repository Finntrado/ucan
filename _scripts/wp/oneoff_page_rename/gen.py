import io, sys
old, new, tpl, out = sys.argv[1:5]
s = io.open(tpl, encoding='utf-8', newline='').read()
s = s.replace('@@OLD@@', old).replace('@@NEW@@', new).replace('@@KEY@@', new.replace('-', '_'))
io.open(out, 'w', encoding='utf-8', newline='\n').write(s)
print('snippet written:', len(s), 'bytes')
