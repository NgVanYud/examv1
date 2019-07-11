import { Message } from 'element-ui';

export function getNotification(action, obj, status, type = 'error', other = '', time = 5000) {
  Message({
    message: action + ' ' + obj + ' ' + status + '. ' + other,
    type: type,
    duration: time,
  });
}
