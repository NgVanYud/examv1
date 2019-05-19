import Layout from '@/layout';
import { ALL_ROLES } from '@/utils/auth';

const userRoutes = {
  path: '/users',
  component: Layout,
  redirect: '/users/list',
  meta: {
    title: 'users',
    icon: 'user',
    permissions: ['view menu components'],
    roles: [ALL_ROLES['admin']],
  },
  children: [
    {
      path: 'list',
      component: () => import('@/views/users/List'),
      name: 'UsersList',
      meta: { title: 'users', icon: 'user' },
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

