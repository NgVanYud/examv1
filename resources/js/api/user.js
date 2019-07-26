import request from '@/utils/request';
import Resource from '@/api/resource';

class UserResource extends Resource {
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

  // teachers(query) {
  //   return request({
  //     url: '/' + this.uri + '/teachers',
  //     method: 'get',
  //     params: query,
  //   });
  // }
}

export { UserResource as default };
