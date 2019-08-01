import Layout from '@/layout';
import { ALL_ROLES } from '@/utils/auth';

const quizRoutes = {
  path: '/quizs',
  component: Layout,
  redirect: '/quizs/list',
  meta: {
    title: 'Môn Thi',
    icon: 'user',
    roles: [ALL_ROLES['student']],
  },
  children: [
    {
      path: 'list',
      component: () => import('@/views/terms/RunningQuiz'),
      name: 'RunningQuiz',
      meta: { title: 'Môn Thi Hiện Tại', icon: 'user', noCache: true },
    },
    {
      path: ':subjectTermId/do',
      component: () => import('@/views/terms/DoQuiz'),
      name: 'DoQuiz',
      hidden: true,
      meta: { title: 'Làm bài thi', icon: 'user', noCache: true },
    },
  ],
};

export default quizRoutes;

