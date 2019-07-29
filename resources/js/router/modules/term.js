import Layout from '@/layout';
import { ALL_ROLES } from '@/utils/auth';

const termRoutes = {
  path: '/terms',
  component: Layout,
  redirect: '/terms/list',
  meta: {
    title: 'Kỳ Thi',
    icon: 'term',
    // permissions: ['view menu components'],
    roles: [ALL_ROLES['curator']],
  },
  children: [
    {
      path: 'list',
      component: () => import('@/views/terms/List'),
      name: 'TermsList',
      meta: { title: 'Kỳ Thi', icon: 'term', noCache: true },
    },
    {
      path: ':id/detail',
      component: () => import('@/views/terms/Detail'),
      name: 'TermDetail',
      hidden: true,
      meta: { title: 'Chi tiết kỳ thi', icon: 'term', noCache: true },
    },
    {
      path: 'create',
      component: () => import('@/views/terms/Create'),
      name: 'CreateTerm',
      hidden: true,
      meta: { title: 'Tạo mới đợt thi', icon: 'term', noCache: true },
    },
    {
      path: ':id/edit',
      component: () => import('@/views/terms/Edit'),
      name: 'EditTerm',
      meta: { title: 'Chỉnh sửa đợt thi', noCache: true },
      hidden: true,
    },
    {
      path: ':termId/subjects/:subjectSlug',
      component: () => import('@/views/terms/SettingSubject'),
      name: 'SettingTermSubject',
      meta: { title: 'Chỉnh sửa đợt thi', noCache: true },
      hidden: true,
    },
    {
      path: 'active/:termId/:subjectId',
      component: () => import('@/views/terms/SettingSubject'),
      name: 'ActiveTerm',
      meta: { title: 'Kích hoạt đợt thi', noCache: true },
      hidden: true,
    },
  ],
};

export default termRoutes;

