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

  subjectTermDetail(termId, subjectId) {
    return request({
      url: '/terms/' + termId + '/subjects/' + subjectId,
      method: 'get',
    });
  }

  storeSetting(data, termId, subjectId) {
    return request({
      url: '/subject-term' + '/terms/' + termId + '/subjects/' + subjectId,
      method: 'post',
      data,
    });
  }

  getStudents(termId, subjectId, data = {}) {
    return request({
      url: '/terms/' + termId + '/subjects/' + subjectId + '/students',
      method: 'get',
      data,
    });
  }

  getProtors(termId, subjectId, data = {}) {
    return request({
      url: '/terms/' + termId + '/subjects/' + subjectId + '/protors',
      method: 'get',
      data,
    });
  }

  getQuizs(termId, subjectId, data = {}) {
    return request({
      url: '/terms/' + termId + '/subjects/' + subjectId + '/quizs',
      method: 'get',
      data,
    });
  }

  subjectsForTerm() {
    return request({
      url: '/subject-term/subjects',
      method: 'get',
    });
  }

  activeQuiz(data) {
    return request({
      url: 'subject-term/subject/active-quiz',
      method: 'post',
      data,
    });
  }

  deactiveQuiz(data) {
    return request({
      url: 'subject-term/subject/deactive-quiz',
      method: 'post',
      data,
    });
  }

  getResults(subjectTermId, params) {
    return request({
      url: 'subject-term/' + subjectTermId + '/subject/results',
      method: 'get',
      params: params,
    });
  }
}

export { TermResource as default };
