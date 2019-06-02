import { Message } from 'element-ui';

export function getNotification(action, obj, type = 'error', reason = '', time = 5000) {
  const statusMap = {
    'error': 'không thành công',
    'success': 'thành công',
  };
  Message({
    message: action + ' ' + obj + ' ' + statusMap[type] + '. ' + reason,
    type: type,
    duration: time,
  });
}
