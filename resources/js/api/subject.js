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
  storeChapter(data, subjectId) {
    return request({
      url: '/' + this.uri + '/' + subjectId + '/chapters',
      method: 'post',
      data: data,
    });
  }

  updateChapter(data, subjectId, chapterId) {
    return request({
      url: '/' + this.uri + '/' + subjectId + '/chapters/' + chapterId + '/update',
      method: 'post',
      data: data,
    });
  }

  getFormatExam(subjectId) {
    return request({
      url: '/' + this.uri + '/' + subjectId + '/format',
      method: 'get',
    });
  }

  updateExamFormat(data, subjectId, formatId) {
    return request({
      url: '/' + this.uri + '/' + subjectId + '/' + 'formats' + '/' + formatId + '/update',
      method: 'post',
      data: data,
    });
  }

  storeExamFormat(data, subjectId) {
    return request({
      url: '/' + this.uri + '/' + subjectId + '/formats',
      method: 'post',
      data: data,
    });
  }

  questions(query, subjectId) {
    return request({
      url: '/' + this.uri + '/' + subjectId + '/questions',
      method: 'get',
      params: query,
    });
  }
  storeQuestion(data, subjectId, chapterId) {
    return request({
      url: '/' + this.uri + '/' + subjectId + '/' + 'chapters' + '/' + chapterId + '/questions',
      method: 'post',
      data: data,
    });
  }
  activeQuestion(subjectId, questionId) {
    return request({
      url: '/' + this.uri + '/' + subjectId + '/' + 'questions' + '/' + questionId + '/active',
      method: 'post',
    });
  }
  deactiveQuestion(subjectId, questionId) {
    return request({
      url: '/' + this.uri + '/' + subjectId + '/' + 'questions' + '/' + questionId + '/deactive',
      method: 'post',
    });
  }
  showQuestion(subjectId, questionId) {
    return request({
      url: '/' + this.uri + '/' + subjectId + '/' + 'questions' + '/' + questionId + '/show',
      method: 'get',
    });
  }
  updateQuestion(data, subjectId, chapterId, questionId) {
    return request({
      url: '/' + this.uri + '/' + subjectId + '/chapters/' + chapterId + '/questions/' + questionId + '/update',
      method: 'post',
      data: data,
    });
  }
  getById(subjectId) {
    return request({
      url: '/' + this.uri + '/' + subjectId + '/get-by-id',
      method: 'get',
    });
  }
}

export { SubjectResource as default };
