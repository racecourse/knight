import config from '../config';
import cookie from 'js-cookie';
export default class storage {
  constructor (tokenName = 'knight') {
    if (!window.localStorage) {
      cookie.setItem = cookie.set;
      cookie.getItem = cookie.get;
      cookie.removeItem = cookie.remove;
      this.store = cookie;
    } else {
      this.store = window.localStorage;
    }
    this.key = config.domain + '#' + tokenName;
  }

  setItem(key, value) {
    return this.store.setItem(key, value);
  }

  getItem(key, value) {
    return this.store.getItem(key, value);
  }

  setUser (user) {
    if (!user) {
      return false;
    }

    user.expiredAt = Date.now() + user.expired * 1000;
    if (typeof user === 'object') {
      user = JSON.stringify(user);
    }

    return this.store.setItem(this.key, user);
  }

  getUser () {
    try {
      const user = this.store.getItem(this.key);
      if (!user) return false;
      return typeof user === 'object' ? user : JSON.parse(user);
    } catch (err) {
      console.log(err);
      return false;
    }
  }

  clear() {
    this.store.removeItem(this.key);
    this.store.removeItem('token');
  }

  check() {
    const user = this.getUser();
    if (!user) {
      return false;
    }

    if (user.expiredAt < Date.now()) {
      return false;
    }

    return true;
  }
}
