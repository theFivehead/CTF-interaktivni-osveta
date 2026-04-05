import http.server
import socketserver
import os

PORT = 667
# Whitelisted files (relative to current directory)
ALLOWED_FILES = {"Hydra.exe", "Melting.exe","ScreenScrew.exe"}

class RestrictedHandler(http.server.SimpleHTTPRequestHandler):
    def do_GET(self):
        # Get requested file path
        requested_file = os.path.basename(self.path)
        
        if requested_file in ALLOWED_FILES:
            # Serve the file normally
            return super().do_GET()
        else:
            # Deny access
            self.send_response(403)
            self.send_header("Content-type", "text/plain")
            self.end_headers()
            self.wfile.write(b"403 Forbidden: Access denied\n")

# Run the server
with socketserver.TCPServer(("", PORT), RestrictedHandler) as httpd:
    print(f"Serving on port {PORT}, only allowing {ALLOWED_FILES}")
    httpd.serve_forever()
