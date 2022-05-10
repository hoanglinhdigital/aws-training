var http = require('http'); // Import Node.js core module
var server = http.createServer(function (req, res) { //create web server
    if (req.url == '/') { //check the URL of the current request
        // set response header
        res.writeHead(200, { 'Content-Type': 'text/html' });
        // set response content
        res.write('<html><body><p>CodeStar-T9 v1.1.</p></body></html>');
        res.end();
    }
    else if (req.url == "/student") {
        res.writeHead(200, { 'Content-Type': 'text/html' });
        res.write('<html><body><p>This is student Page v1.1.</p></body></html>');
        res.end();
    }
    else if (req.url == "/admin") {
        res.writeHead(200, { 'Content-Type': 'text/html' });
        res.write('<html><body><h3 style="color:red;">You need admin permission to access this page!</h3>/<p>This is admin Page v1.1.</p></body></html>');
        res.end();
    }
    else
        res.end('Invalid Request! v1.1');
});
server.listen(5000); //6 - listen for any incoming requests
console.log('Node.js web server at port 5000 is running..')