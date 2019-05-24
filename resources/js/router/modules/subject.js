import Layout from '@/layout';
import { ALL_ROLES } from '@/utils/auth';

const subjectRoutes = {
  path: '/subjects',
  component: Layout,
  redirect: '/subjects/list',
  meta: {
    title: 'Môn học',
    icon: 'subject',
    permissions: ['view menu components'],
    roles: [ALL_ROLES['admin']],
  },
  children: [
    {
      path: 'list',
      component: () => import('@/views/users/List'),
      name: 'SubjectsList',
      meta: { title: 'Danh Sách Môn Học', icon: 'subject' },
    },
    {
      path: 'edit/:id',
      component: () => import('@/views/users/Edit'),
      name: 'UserEdit',
      meta: { title: 'Chỉnh sửa user' },
      hidden: true,
    },
  ],
};

export default userRoutes;

