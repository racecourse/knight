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

Vue.use(Vuex);
const $storage = new Cellar();
const auth = store => {
  store.subscribe((mutation, state) => {
    console.log('>>>>>', mutation.payload.code == 10401);
    if (
      // mutation.type === 'FETCH_FAILURE' &&
      mutation.payload.code == 10401
    ) {
      console.log('%%%%%%', store);
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
  },
  strict: true,
  actions,
  getters,
  debug: true,
  plugins: [auth],
});