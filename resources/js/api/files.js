import request from '@/utils/request';
import Resource from '@/api/resource';

class FileResource extends Resource {
  constructor() {
    super('files');
  }

  upload(data) {
    return request({
      url: '/' + this.uri + '/upload',
      method: 'post',
      data: data,
      // config: {
      //   headers: {
      //     'Content-Type': 'multipart/form-data;',
      //   },
      // },
      headers: {
        'Content-Type': 'multipart/form-data',
      },
      withCredentials: false,
    });
  }
}

export { FileResource as default };
