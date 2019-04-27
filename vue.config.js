module.exports = {
  outputDir: 'dist',
  // publicPath: "./public",
  css: {
    loaderOptions: { // 向 CSS 相关的 loader 传递选项
    },
    extract: true,
    modules: false
  },
  // chainWebpack: config => {
  //   config
  //     .plugin("html")
  //     .tap(args => {
  //       args[0].template = "./src/index.html"

  //       return args
  //     })
  // },
  // configureWebpack: config => {
  //   if (process.env.NODE_ENV === 'production') {
  //     // 为生产环境修改配置...
  //   } else {
  //     // 为开发环境修改配置...
  //   }
  // }
}
