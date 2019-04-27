import Vue from 'vue'
import Router from 'vue-router'
import routes from './routers'

Vue.use(Router)
const env = process.env.NODE_ENV;

export default new Router({
  mode: env !== 'develop' ? 'hash' : 'hash',
  routes
})
