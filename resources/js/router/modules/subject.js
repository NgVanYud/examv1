import Layout from '@/layout';
import { ALL_ROLES } from '@/utils/auth';

const subjectRoutes = {
  path: '/subjects',
  component: Layout,
  redirect: '/subjects/list',
  meta: {
    title: 'subjects',
    icon: 'subject',
    permissions: ['view menu components'],
    roles: [ALL_ROLES['admin'], ALL_ROLES['exams_maker']],
  },
  children: [
    {
      path: 'list',
      component: () => import('@/views/subjects/List'),
      name: 'SubjectsList',
      meta: { title: 'subjectsList', icon: 'subject', noCache: true },
    },
    {
      path: 'detail/:slug',
      component: () => import('@/views/subjects/Detail'),
      name: 'SubjectDetail',
      meta: { title: 'editSubject', noCache: true },
      hidden: true,
    },
    {
      path: 'chapters/:slug',
      component: () => import('@/views/subjects/Chapter'),
      name: 'ChaptersList',
      meta: { title: 'contentSubject', noCache: true },
      hidden: true,
    },
    {
      path: 'teachers/:slug',
      component: () => import('@/views/subjects/Chapter'),
      name: 'ChaptersList',
      meta: { title: 'Nội dung môn học', noCache: true },
      hidden: true,
    },
    {
      path: ':subjectSlug/question/edit/:id',
      name: 'QuestionEdit',
      component: () => import('@/views/questions/Edit'),
      meta: { title: 'Chỉnh sửa câu hỏi', noCache: true },
      hidden: true,
    },
  ],
};

export default subjectRoutes;

