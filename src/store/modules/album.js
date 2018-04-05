const ALBUM_FETCH_REQUEST = 'ALBUM_FETCH_REQUEST';
const ALBUM_FETCH_SUCCESS = 'ALBUM_FETCH_SUCCESS';
const ALBUM_FETCH_FAILURE = 'ALBUM_FETCH_FAILURE';
const ALBUM_FETCH_CHANGE = 'ALBUM_FETCH_CHANGE';
const ALBUM_DETAIL_SUCCESS = 'ALBUM_DETAIL_SUCCESS';

const state = {
  albums: {},
  message: '',
  ok: false,
};

const mutations = {
  [ALBUM_FETCH_SUCCESS](state, payload){
    const { data, message, ok }  = payload;
    console.log('++++_', payload);
    state.albums = data;
    state.message = message;
    state.ok = ok;
  },
  [ALBUM_FETCH_FAILURE](state, payload){
    const { message, ok }  = payload;
    state.message = message;
    state.ok = ok;
  },
  [ALBUM_FETCH_CHANGE] (state, item) {
    state.auth = Object.assign(state.auth || {}, item);
  }
};

export default {
  state,
  mutations
}