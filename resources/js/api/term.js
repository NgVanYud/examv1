import request from '@/utils/request';
import Resource from '@/api/resource';

class TermResource extends Resource {
  constructor() {
    super('terms');
  }

  // upload(data) {
  //   return request({
  //     url: '/' + this.uri + '/upload',
  //     method: 'post',
  //     data: data,
  //     // config: {
  //     //   headers: {
  //     //     'Content-Type': 'multipart/form-data;',
  //     //   },
  //     // },
  //     headers: {
  //       'Content-Type': 'multipart/form-data',
  //     },
  //     withCredentials: false,
  //   });
  // }

  subjectTermDetail(query) {
    return request({
      url: '/subject-term' + '/' + 'detail',
      method: 'get',
      params: query,
    });
  }

  storeSetting(data, termId, subjectId) {
    return request({
      url: '/subject-term' + '/' + termId + '/' + subjectId + '/setting',
      method: 'post',
      data,
    });
  }
}

export { TermResource as default };
