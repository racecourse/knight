const express = require('express');
const swagger = require('swagger-ui-dist');
const fs = require('fs');

const app = express();
const pathToSwaggerUi = swagger.absolutePath();
const docPath = '/document';
app.use(function(req, res, next) {
    const url = req.url;
    const swaggerPath = swagger.getAbsoluteFSPath();
    if (url.startsWith(docPath)) {
        let html = fs.readFileSync(swaggerPath +'/index.html');
        html = html.toString();
        html = html.replace(/(url:\s)("http:.+?\.json")/, '$1"/knight"');
        res.send(html);
    } else {
        next();
    }
});
app.get('/knight', function (req, res, next) {
    const doc = require('./knight.json');
    res.send(doc);
});
app.use(express.static(pathToSwaggerUi));
app.listen(3000);