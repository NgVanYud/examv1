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
      component: () => import('@/views/terms/WaitingSubject'),
      name: 'TermSubjectsList',
      meta: { title: 'Môn Thi Hiện Tại', icon: 'user', noCache: true },
    },
  ],
};

export default subjectTermRoutes;

