// import request from '@/utils/request';
import UserResource from '@/api/user';

class ManagerResource extends UserResource {
  constructor() {
    super('managers');
  }
}

export default ManagerResource;
