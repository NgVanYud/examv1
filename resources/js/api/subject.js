import request from '@/utils/request';
import Resource from '@/api/resource';

class SubjectResource extends Resource {
  constructor() {
    super('subjects');
  }

  destroyMulti(data) {
    return request({
      url: '/' + this.uri + '/delete-multi',
      method: 'post',
      data: data,
    });
  }
}

export { SubjectResource as default };
