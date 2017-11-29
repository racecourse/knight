import fetch from '../fetch';
import querystring from 'querystring';

export default {
  async login({ commit }, data) {
    const res = await fetch('/login', 'POST', data);
    if (res.ok) {
      commit('USER_LOGIN_SUCCESS', res)
    } else {
      commit('USER_LOGIN_FAILURE', res)
    }
  },
  register() {

  },
  async posts({ commit }, data) {
    let uri = '/posts';
    if (data) {
      data = typeof data === 'object' ? querystring.stringify(data) : data;
      uri += '?' + data;
    }
    const res = await fetch(uri, 'get');
    if (res.ok) {
      commit('POST_FETCH_SUCCESS', res)
    } else {
      commit('POST_FETCH_FAILURE', res)
    }
  },
  async post({ commit }, id) {
    const res = await fetch('/posts/' + id, 'get');
    if (res.ok) {
      commit('POST_DETAIL_SUCCESS', res)
    } else {
      commit('POST_FETCH_FAILURE', res)
    }
  },
  async article({ commit }, data) {
    const res = await fetch('/admin/article', 'get', data);
    if (res.ok) {
      commit('ARTICLE_FETCH_SUCCESS', res)
    } else {
      commit('ARTICLE_FETCH_FAILURE', res)
    }
  },
  async delArt({ commit }, { id }) {
    const res = await fetch('/admin/article/' + id, 'delete');
    if (res.ok) {
      commit('ARTICLE_FETCH_SUCCESS', res)
    } else {
      commit('ARTICLE_FETCH_FAILURE', res)
    }
  },
  async getArt({ commit }, id) {
    const res = await fetch('/admin/article/' + id, 'get');
    if (res.ok) {
      commit('ARTICLE_DETAIL_SUCCESS', res)
    } else {
      commit('ARTICLE_DETAIL_FAILURE', res)
    }
  },
  async getCommentsByPostId({ commit }, id) {
    const res = await fetch('/posts/' + id + '/comments', 'get');
    if (res.ok) {
      commit('COMMENT_FETCH_SUCCESS', res);
    } else {
      commit('COMMENT_FETCH_FAILURE', res);
    }
  },
  async addComment({commit, dispatch}, data) {
    const res = await fetch('/posts/' + data.id + '/comments', 'post', data);
    if (res.ok) {
      commit('COMMENT_FETCH_SUCCESS', res);
      await dispatch('getCommentsByPostId', data.id);
    } else {
      commit('COMMENT_FETCH_FAILURE', res);
    }
  },
  async survey({ commit, dispatch }) {
    const res = await fetch('/admin/survey', 'get');
    if (res.ok) {
      commit('SURVEY_FETCH_SUCCESS', res);
    } else {
      commit('FETCH_FAILURE', res);
    }
  },
  async addArticle ({ commit }, data) {
    const res = await fetch('/admin/article', 'post', data);
    if (res.ok) {
      commit('ARTICLE_ADD_SUCCESS', res);
    } else {
      commit('ARTICLE_ADD_FAILURE', res);
    }
  },
  async editArticle ({commit}, data) {
    const res = await fetch('/admin/article/' + data.id, 'put', data);
    if (res.ok) {
      commit('ARTICLE_ADD_SUCCESS', res);
    } else {
      commit('ARTICLE_ADD_FAILURE', res);
    }
  },
  async category({ commit }){
    const res = await fetch('/category', 'get');
    if (res.ok) {
      commit('CATEGORY_FETCH_SUCCESS', res);
    } else {
      commit('CATEGORY_FETCH_FAILURE', res);
    }
  },
  nextPost({commit}, page) {
    commit('POST_PAGE_CHANGED', page);
  },
  async comments({commit}, query) {
    const res = await fetch('/admin/comments', 'get', query);
    if (res.ok) {
      commit('COMMENT_FETCH_SUCCESS', res);
    } else {
      commit('COMMENT_FETCH_FAILURE', res);
    }
  },
  logout({commit}) {
    commit('LOGOUT_REQUEST');
  },
  async addCategory({commit}, name) {
    const res = await fetch('/admin/category', 'post', {name});
    if (res.ok) {
      commit('CATEGORY_FETCH_SUCCESS', res);
    } else {
      commit('FETCH_FAILURE');
    }
  }
}