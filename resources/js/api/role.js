import request from '@/utils/request';
import Resource from '@/api/resource';

class RoleResource extends Resource {
  constructor() {
    super('roles');
  }

  permissions(id) {
    return request({
      url: '/' + this.uri + '/' + id + '/permissions',
      method: 'get',
    });
  }

  /**
   * Get all users is teacher
   * @return {AxiosPromise}
   */
  teachers() {
    return request({
      url: '/' + this.uri + '/teachers',
      method: 'get',
    });
  }

  // detailByName(roleName) {
  //   return request({
  //   })
  // }
}

export { RoleResource as default };
