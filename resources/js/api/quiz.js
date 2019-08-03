import request from '@/utils/request';
import Resource from '@/api/resource';

class QuizResource extends Resource {
  constructor() {
    super('quizs');
  }

  getInfo() {
    return request({
      url: '/quizs',
      method: 'get',
    });
  }

  detail(subjectTermId) {
    return request({
      url: '/quizs/' + subjectTermId,
      method: 'get',
    });
  }

  submitResult(subjectTermId, data) {
    return request({
      url: '/quizs/' + subjectTermId + '/result',
      method: 'post',
      data,
    });
  }
}

export { QuizResource as default };
