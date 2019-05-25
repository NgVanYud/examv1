// import request from '@/utils/request';
import Resource from '@/api/resource';

class SubjectResource extends Resource {
  constructor() {
    super('subjects');
  }
}

export { SubjectResource as default };
