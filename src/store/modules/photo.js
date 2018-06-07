const PHOTO_FETCH_REQUEST = 'PHOTO_FETCH_REQUEST';
const PHOTO_FETCH_SUCCESS = 'PHOTO_FETCH_SUCCESS';
const PHOTO_FETCH_FAILURE = 'PHOTO_FETCH_FAILURE';
const PHOTO_FETCH_CHANGE = 'PHOTO_FETCH_CHANGE';
const PHOTO_DETAIL_SUCCESS = 'PHOTO_DETAIL_SUCCESS';

const state = {
  photos: {},
  message: '',
  ok: false,
};

const mutations = {
  [PHOTO_FETCH_SUCCESS](state, payload){
    const { data, message, ok }  = payload;
    state.photos = data;
    state.message = message;
    state.ok = ok;
  },
  [PHOTO_FETCH_FAILURE](state, payload){
    const { message, ok }  = payload;
    state.message = message;
    state.ok = ok;
  },
  [PHOTO_FETCH_CHANGE] (state, item) {
    state.auth = Object.assign(state.auth || {}, item);
  },

};

export default {
  state,
  mutations
}