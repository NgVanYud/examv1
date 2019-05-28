import Cookies from 'js-cookie';

export const ALL_ROLES = {
  'exams_maker': 'Giáo Viên Ra Đề',
  'curator': 'Cán Bộ Khảo Thí', // khảo thí
  'protor': 'Giám Thị', // giám thị
  'admin': 'Quản Trị Hệ Thống',
  'student': 'Sinh Viên',
};

const TokenKey = 'Admin-Token';

export function getToken() {
  return Cookies.get(TokenKey);
}

export function setToken(token) {
  return Cookies.set(TokenKey, token);
}

export function removeToken() {
  return Cookies.remove(TokenKey);
}
