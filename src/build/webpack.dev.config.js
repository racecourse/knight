var webpack = require('webpack');
var merge = require('webpack-merge');
var HtmlWebpackPlugin = require('html-webpack-plugin');
var baseWebpackConfig = require('./webpack.base.config');
var utils = require('./utils');
var config = require('./config');
// var FriendlyErrorsPlugin = require('friendly-errors-webpack-plugin');

const DashboardPlugin = require('webpack-dashboard/plugin');
// 热替换
Object.keys(baseWebpackConfig.entry).forEach(function (name) {
  baseWebpackConfig.entry[name] = [
    `webpack-dev-server/client?http://localhost:${config.dev.port}/`,
    "webpack/hot/dev-server"
  ].concat(baseWebpackConfig.entry[name])
});

module.exports = merge(baseWebpackConfig, {
  output: {
    path: config.dev.outputPath,
    publicPath: config.dev.outputPublicPath
  },
  resolve: {
    alias: {
      'vue': 'vue/dist/vue.js'
    }
  },
  module: {
    rules: utils.styleLoaders()
  },
  plugins: [
    // new webpack.HotModuleReplacementPlugin(),
    // new webpack.NoEmitOnErrorsPlugin(),
    // new HtmlWebpackPlugin({
    //   filename: 'index.html',
    //   template: 'index.tpl.html',
    //   inject: true
    // }),
    // new FriendlyErrorsPlugin()
    new DashboardPlugin(),
    // new webpack.NoErrorsPlugin(),
    new webpack.optimize.OccurrenceOrderPlugin(),
    new webpack.HotModuleReplacementPlugin(),
    new HtmlWebpackPlugin({
      template: './src/index.html',
      inject: 'body',
      favicon: 'favicon.ico'
    })
  ]
});