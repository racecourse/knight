const express = require('express');

const app = express();
const path = __dirname + '/public';
app.use(express.static(path));

app.listen(5000, '0.0.0.0');