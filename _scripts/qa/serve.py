"""Serve standalone/ the way Vercel does: cleanUrls (/about -> about.html),
gzip for text, and long-lived caching for /assets. Lighthouse numbers against
an uncompressed server would be pessimistic, so compression matters here."""
import gzip, http.server, io, os, socketserver

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', '..', 'standalone')
PORT = 8099
TEXT = ('.html', '.css', '.js', '.json', '.svg', '.txt', '.xml')


class H(http.server.SimpleHTTPRequestHandler):
    def __init__(self, *a, **k):
        super().__init__(*a, directory=ROOT, **k)

    def translate_path(self, path):
        p = super().translate_path(path)
        if not os.path.exists(p) and os.path.exists(p + '.html'):
            return p + '.html'
        return p

    def send_head(self):
        path = self.translate_path(self.path)
        if os.path.isdir(path):
            path = os.path.join(path, 'index.html')
        wants_gzip = 'gzip' in self.headers.get('Accept-Encoding', '')
        if not (wants_gzip and path.endswith(TEXT) and os.path.isfile(path)):
            return super().send_head()
        with open(path, 'rb') as f:
            body = gzip.compress(f.read(), 6)
        self.send_response(200)
        self.send_header('Content-Type', self.guess_type(path))
        self.send_header('Content-Encoding', 'gzip')
        self.send_header('Content-Length', str(len(body)))
        self.end_headers()
        return io.BytesIO(body)

    def end_headers(self):
        if '/assets/' in self.path:
            self.send_header('Cache-Control', 'public, max-age=31536000, immutable')
        super().end_headers()

    def log_message(self, *a):
        pass


class S(socketserver.ThreadingTCPServer):
    allow_reuse_address = True
    daemon_threads = True


if __name__ == '__main__':
    with S(('127.0.0.1', PORT), H) as s:
        s.serve_forever()
