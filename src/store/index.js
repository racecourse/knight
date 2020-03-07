import Vue from 'vue'
import Vuex from 'vuex'
import user from './modules/user';
import post from './modules/post';
import article from './modules/article';
import admin from './modules/admin';
import actions from './actions';
import getters from './getters';
import comment from './modules/comment';
import category from './modules/category';
import Cellar from '../util/storage';
import album from './modules/album';
import photo from './modules/photo';

Vue.use(Vuex);
const $storage = new Cellar();
const auth = store => {
  store.subscribe((mutation, state) => {
    const {payload} = mutation;
    const {to} = payload;
    if (to && to.path.search(/^\/admin\/(.*?)/) !== -1) {
      const userState = $storage.check();
      if (userState === false) {
        // $storage.clear();
        // state.user.auth = null;
      }
    }

    if (
      // mutation.type === 'FETCH_FAILURE' &&
      payload.code == 10401
    ) {
      $storage.clear();
      state.user.auth = null;
    }
  });
};
export default new Vuex.Store({
  modules: {
    user,
    post,
    article,
    admin,
    comment,
    category,
    album,
    photo,
  },
  strict: true,
  actions,
  getters,
  debug: true,
  plugins: [auth],
});
