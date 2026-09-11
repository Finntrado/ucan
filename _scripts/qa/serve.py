"""Serve standalone/ the way Vercel does with cleanUrls: /about -> about.html."""
import http.server, os, socketserver

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', '..', 'standalone')
PORT = 8099

class H(http.server.SimpleHTTPRequestHandler):
    def __init__(self, *a, **k):
        super().__init__(*a, directory=ROOT, **k)
    def translate_path(self, path):
        p = super().translate_path(path)
        if not os.path.exists(p) and os.path.exists(p + '.html'):
            return p + '.html'
        return p
    def log_message(self, *a):
        pass

class S(socketserver.ThreadingTCPServer):
    allow_reuse_address = True
    daemon_threads = True

if __name__ == '__main__':
    with S(('127.0.0.1', PORT), H) as s:
        s.serve_forever()
