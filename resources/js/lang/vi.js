import validation from './vi/validation';
import route from './vi/route';
import navbar from './vi/navbar';
import table from './vi/table';
import notification from './vi/notification';
import role from './vi/role';
import permission from './vi/permission';
import form from './vi/form';
import button from './vi/button';

export default {
  // common: {
  //   back: 'Quay lại',
  //   return: 'Trở về',
  // },
  schoolName: 'Học Viện Kỹ Thuật Mật Mã',
  appName: 'Hệ Thống Thi Trắc Nghiệm Online',
  route: route,
  navbar: navbar,
  form: form,
  button: button,
  documentation: {
    documentation: 'Tài liệu',
    github: 'Github Repository',
    laravel: 'Laravel',
  },
  permission: permission,
  guide: {
    description: 'Trang hướng dẫn sẽ có ích cho những người mới vào website lần đầu. Bạn có thể giới thiệu sơ lược các chức năng của website. Kiểm tra demo',
    button: 'Hiện hướng dẫn',
  },
  components: {
    documentation: 'Tài liệu',
    tinymceTips: 'Rich text editor is a core part of management system, but at the same time is a place with lots of problems. In the process of selecting rich texts, I also walked a lot of detours. The common rich text editors in the market are basically used, and the finally chose Tinymce. See documentation for more detailed rich text editor comparisons and introductions.',
    dropzoneTips: 'Because my business has special needs, and has to upload images to qiniu, so instead of a third party, I chose encapsulate it by myself. It is very simple, you can see the detail code in @/components/Dropzone.',
    stickyTips: 'When the page is scrolled to the preset position will be sticky on the top.',
    backToTopTips1: 'When the page is scrolled to the specified position, the Back to Top button appears in the lower right corner',
    backToTopTips2: 'You can customize the style of the button, show / hide, height of appearance, height of the return. If you need a text prompt, you can use element-ui el-tooltip elements externally',
    imageUploadTips: 'Since I was using only the vue@1 version, and it is not compatible with mockjs at the moment, I modified it myself, and if you are going to use it, it is better to use official version.',
  },
  table: table,
  errorLog: {
    tips: 'Please click the bug icon in the upper right corner',
    description: 'Now the management system are basically the form of the spa, it enhances the user experience, but it also increases the possibility of page problems, a small negligence may lead to the entire page deadlock. Fortunately Vue provides a way to catch handling exceptions, where you can handle errors or report exceptions.',
    documentation: 'Document introduction',
  },
  excel: {
    export: 'Export',
    selectedExport: 'Export Selected Items',
    placeholder: 'Please enter the file name(default excel-list)',
  },
  zip: {
    export: 'Export',
    placeholder: 'Please enter the file name(default file)',
  },
  pdf: {
    tips: 'Here we use window.print() to implement the feature of downloading pdf.',
  },
  theme: {
    change: 'Change Theme',
    documentation: 'Theme documentation',
    tips: 'Tips: It is different from the theme-pick on the navbar is two different skinning methods, each with different application scenarios. Refer to the documentation for details.',
  },
  tagsView: {
    refresh: 'Refresh',
    close: 'Close',
    closeOthers: 'Close Others',
    closeAll: 'Close All',
  },
  user: {
    'role': 'Nhóm',
    'password': 'Mật khẩu',
    'confirmPassword': 'Nhập lại mật khẩu',
    'name': 'Tên',
    'email': 'Địa chỉ email',
  },
  role: role,
  notification: notification,
  validation: validation,
};
