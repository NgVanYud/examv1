import request from '@/utils/request';
import Resource from '@/api/resource';

class UserResource extends Resource {
  constructor() {
    super('users');
  }

  permissions(id) {
    return request({
      url: '/' + this.uri + '/' + id + '/permissions',
      method: 'get',
    });
  }

  updatePermission(id, permissions) {
    return request({
      url: '/' + this.uri + '/' + id + '/permissions',
      method: 'put',
      data: permissions,
    });
  }

  active(id) {
    return request({
      url: '/' + this.uri + '/active',
      method: 'post',
      data: { uuid: id },
    });
  }

  deactive(id) {
    return request({
      url: '/' + this.uri + '/deactive',
      method: 'post',
      data: { uuid: id },
    });
  }
}

export { UserResource as default };
