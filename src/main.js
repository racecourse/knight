import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './store';
import FastClick from 'fastclick';
import { sync } from 'vuex-router-sync';
import Cellar from './util/storage';
import App from './App.vue';
import MuseUI from 'muse-ui';
import VuePreview from 'vue-preview'
import hljs from 'highlight.js';
import 'muse-ui/dist/muse-ui.css';
// import teal from 'muse-ui/dist/theme-teal.css'
import './assets/hljs.css';
import './assets/common.css';
import './assets/reset.css';
import './assets/md-icon.css';
import 'muse-ui/dist/muse-ui.css';

import router from './router'

Vue.config.productionTip = false


Vue.use(MuseUI)
Vue.use(VuePreview);
const storage = new Cellar();
Vue.use(VueRouter);
router.beforeEach((to, from, next) => {
  const meta = to.meta || {};
  const user = storage.getUser();
  const path = to.path;
  if (meta.auth && !user && path !== '/login') {
    return next({ path: '/login' });
  }
  if(user && path === '/login' ){
    return next({ path: '/admin/dashboard' });
  }
  next();
});
window.addEventListener('load', () => {
  FastClick.attach(document.body)
});
window.hljs = hljs;
window.$router = router;
sync(store, router);
new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app', true)




