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
    roles: [ALL_ROLES['admin'], ALL_ROLES['exams_maker']],
  },
  children: [
    {
      path: 'list',
      component: () => import('@/views/subjects/List'),
      name: 'SubjectsList',
      meta: { title: 'Môn học', icon: 'subject' },
    },
    {
      path: 'edit/:id',
      component: () => import('@/views/subjects/Edit'),
      name: 'SubjectEdit',
      meta: { title: 'Chỉnh sửa môn học' },
      hidden: true,
    },
  ],
};

export default subjectRoutes;

