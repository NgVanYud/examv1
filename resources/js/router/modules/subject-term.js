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
    {
      path: ':subjectTermId/results',
      component: () => import('@/views/terms/ResultsList'),
      name: 'TermSubjectResultsList',
      hidden: true,
      meta: { title: 'Kết quả bài thi', icon: 'user', noCache: true },
    },
  ],
};

export default subjectTermRoutes;

