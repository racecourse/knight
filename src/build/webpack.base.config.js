var path = require('path');
var utils = require('./utils');
var ExtractTextPlugin = require('extract-text-webpack-plugin');

function resolve (relPath) {
  return path.resolve(__dirname, relPath);
}

module.exports = {
  entry: { app: resolve('../main.js') },
  output: {
    filename: 'js/[name].js'
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        loader: 'babel-loader',
        include: [resolve('../')],
      },
      {
        test: /\.vue$/,
        use: {
          loader: "vue-loader",
          options: utils.vueLoaderOptions()
        }
      },
      {
        test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
        use: {
          loader: "url-loader",
          options: {
            limit: 10000,
            name: 'images/[name].[hash:7].[ext]'
          }
        }
      },
      {
        test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
        use: [{
          loader: "url-loader",
          options: {
            limit: 10000,
            name: 'fonts/[name].[hash:7].[ext]'
          }
        }]
      },
      {
        test: /\.scss$/,
        exclude: /node_modules/,
        use: ExtractTextPlugin.extract({
          fallback: 'style-loader',
          use: [
            {
              loader: 'css-loader',
              query: {
                modules: true,
                sourceMap: true,
                importLoaders: 2,
                localIdentName: '[name]__[local]___[hash:base64:5]'
              }
            },
            'sass-loader'
          ]
        }),
      },
    ]
  }
};