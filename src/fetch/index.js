import Storage from '../util/storage';
import util from '../util';
import config from '../config';
import {router} from '../main';

const storage = new Storage();
const getHeaders = () => {
  const token = storage.getItem('token');
  let header = {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  };
  if (token) {
    header['Authorization'] = 'Bearer ' + token;
  }
  return header;
};

export default async function api (uri, method, data) {
  // try {
  if (typeof data === 'object') {
    data = JSON.stringify(data);
  }
  const headers = getHeaders();
  const fetchParams = {
    headers,
    mode: 'cors',
    method: method,
  };
  if (data) {
    if (method !== 'get') fetchParams.body = data;
    else uri += '?' + util.queryString(data);
  }
  const url = 'http://' + config.api + uri;
  const res = await fetch(url, fetchParams);
  let body = await res.json();
  body = body && typeof body === 'object' ? body : {};
  if (res.status === 200) {
    body.ok = true;
  } else if (res.status >= 500) {
    body.message = 'Server Error';
    body.ok = false;
  } else if (Number(body.code) === 10401) {
    body.certification = false;
    body.ok = false;
    storage.clear();
    router.push('/login');
  } else {
    body.ok = false;
  }

  return body;
  // } catch (err) {
  //   return {
  //     message: 'Exception:' + err.message,
  //   };
  // }
};
