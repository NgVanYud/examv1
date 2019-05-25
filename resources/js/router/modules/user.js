import Layout from '@/layout';
import { ALL_ROLES } from '@/utils/auth';

const userRoutes = {
  path: '/users',
  component: Layout,
  redirect: '/users/list',
  meta: {
    title: 'Người dùng',
    icon: 'user',
    permissions: ['view menu components'],
    roles: [ALL_ROLES['admin']],
  },
  children: [
    {
      path: 'list',
      component: () => import('@/views/users/List'),
      name: 'UsersList',
      meta: { title: 'Người dùng', icon: 'user', noCache: true },
    },
    {
      path: 'edit/:id',
      component: () => import('@/views/users/Edit'),
      name: 'UserEdit',
      meta: { title: 'Chỉnh sửa user', noCache: true },
      hidden: true,
    },
  ],
};

export default userRoutes;

