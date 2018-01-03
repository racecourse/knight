var path = require('path');
module.exports = {
  dev: {
    outputPath: path.resolve(__dirname, '../../public'),
    outputPublicPath: '/',
    port: 8080
  },
  prod: {
    outputPath: path.resolve(__dirname, '../../public'),
    outputPublicPath: '/'
  }
};