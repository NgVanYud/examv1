// import request from '@/utils/request';
import UserResource from '@/api/user';
import request from '@/utils/request';

class ManagerResource extends UserResource {
  constructor(uri = 'managers') {
    super(uri);
  }

  getByRole(data) {
    return request({
      url: '/' + this.uri + '/roles',
      method: 'post',
      data: data,
    });
  }
}

export default ManagerResource;
