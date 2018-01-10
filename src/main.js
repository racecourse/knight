import Vue from 'vue';
import VueRouter from 'vue-router';
import routes from './routers';
import store from './store';
import FastClick from 'fastclick';
import { sync } from 'vuex-router-sync';
import Cellar from './util/storage';
import App from './App.vue';
import MuseUI from 'muse-ui';
import hljs from 'highlight.js';
import 'muse-ui/dist/muse-ui.css';
import teal from 'muse-ui/dist/theme-teal.css'
import './assets/hljs.css';
import './assets/common.css';
import './assets/reset.css';

window.hljs = hljs;
Vue.use(MuseUI)
const storage = new Cellar();
window.addEventListener('load', () => {
  FastClick.attach(document.body)
});
Vue.use(VueRouter);

export const router = new VueRouter({
  mode: 'hash',
  routes,
});

router.beforeEach((to, from, next, ...rest) => {
  const meta = to.meta || {};
  const user = storage.getUser();
  const path = to.path;
  if (meta.auth && !user && path !== '/login') {
    return next({ path: '/login' });
  }
  if(user && path === '/login' ){
    return next({ path: '/admin/home' });
  }
  next();
});
window.$router = router;
sync(store, router);
new Vue({
  router,
  store,
  render: h => h(App),
}).$mount('#app');




