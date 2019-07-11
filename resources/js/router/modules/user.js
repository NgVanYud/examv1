import Layout from '@/layout';
import { ALL_ROLES } from '@/utils/auth';

const userRoutes = {
  path: '/managers',
  component: Layout,
  redirect: '/managers/list',
  meta: {
    title: 'managers',
    icon: 'user',
    permissions: ['view menu components'],
    roles: [ALL_ROLES['admin']],
  },
  children: [
    {
      path: 'list',
      component: () => import('@/views/users/managers/List'),
      name: 'ManagersList',
      meta: { title: 'managersList', icon: 'user', noCache: true },
    },
    {
      path: 'edit/:id',
      component: () => import('@/views/users/Edit'),
      name: 'ManagerEdit',
      meta: { title: 'editManager', noCache: true },
      hidden: true,
    },
  ],
};

export default userRoutes;

