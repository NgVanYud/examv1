import Layout from '@/layout';
import { ALL_ROLES } from '@/utils/auth';

const subjectTermRoutes = {
  path: '/subject-term',
  component: Layout,
  redirect: '/subject-term/list',
  meta: {
    title: 'Môn Thi Hiện Tại',
    icon: 'user',
    roles: [ALL_ROLES['protor']],
  },
  children: [
    {
      path: 'list',
      component: () => import('@/views/subjects-term/List'),
      name: 'SubjectsTermList',
      meta: { title: 'Môn Thi Hiện Tại', icon: 'user', noCache: true },
    },
    // {
    //   path: 'edit/:id',
    //   component: () => import('@/views/users/Edit'),
    //   name: 'UserEdit',
    //   meta: { title: 'Chỉnh sửa user', noCache: true },
    //   hidden: true,
    // },
  ],
};

export default subjectTermRoutes;

