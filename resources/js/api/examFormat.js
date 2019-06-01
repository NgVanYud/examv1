// import request from '@/utils/request';
import Resource from '@/api/resource';
import request from "@/utils/request";

class ExamFormatResource extends Resource {
  constructor() {
    super('formats');
  }

  get(subjectId, formatId) {
    return request({
      url: '/subjects/' + subjectId + '/' +  this.uri + '/' + formatId,
      method: 'get',
    });
  }
}

export { ExamFormatResource as default };
