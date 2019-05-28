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

  chapters(query, id) {
    return request({
      url: '/' + this.uri + '/' + id + '/chapters',
      method: 'get',
      query: query,
    });
  }

  getExamMakers(id) {
    return request({
      url: '/' + this.uri + '/' + id + '/exam-makers',
      method: 'get',
    });
  }
  storeExamMaker(subjectId, userId) {
    return request({
      url: '/' + this.uri + '/' + subjectId + '/add-exam-maker',
      method: 'post',
      data: { user_uuid: userId },
    });
  }
  removeExamMaker(subjectId, userId) {
    return request({
      url: '/' + this.uri + '/' + subjectId + '/remove-exam-maker',
      method: 'post',
      data: { user_uuid: userId },
    });
  }
}

export { SubjectResource as default };
