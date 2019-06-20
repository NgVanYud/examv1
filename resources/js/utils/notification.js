import { Message } from 'element-ui';

export function getNotification(action, obj, status, reason = '', type = 'error', time = 5000) {
  Message({
    message: action + ' ' + obj + ' ' + status + '. ' + reason,
    type: type,
    duration: time,
  });
}
