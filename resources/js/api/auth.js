import request from '@/utils/request';
import store from '@/store';

export function login(data) {
  return request({
    url: 'auth/login',
    method: 'post',
    data: data,
  });
}

export function getInfo() {
  const userGroup = store.state.user.group;
  if (userGroup === 'student') {
    return getInfoStudent();
  } else if (userGroup === 'manager') {
    return getInfoManager();
  }
}

function getInfoStudent() {
  return request({
    url: 'student/me',
    method: 'post',
  });
}

function getInfoManager() {
  return request({
    url: 'manager/me',
    method: 'post',
  });
}

export function logout() {
  return request({
    url: '/auth/logout',
    method: 'post',
  });
}
