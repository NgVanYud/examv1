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
      meta: {
        title: 'subjectsList', icon: 'subject', noCache: true,
      },
    },
    {
      path: 'detail/:slug',
      component: () => import('@/views/subjects/Detail'),
      name: 'SubjectDetail',
      meta: { title: 'editSubject', noCache: true },
      hidden: true,
    },
    // {
    //   path: 'teachers/:slug',
    //   component: () => import('@/views/subjects/Chapter'),
    //   name: 'ChaptersList',
    //   meta: { title: 'Nội dung môn học', noCache: true },
    //   hidden: true,
    // },
    {
      path: ':subjectSlug/edit',
      name: 'EditSubject',
      component: () => import('@/views/subjects/Edit'),
      meta: { title: 'Chỉnh sửa môn học', noCache: true, roles: [ALL_ROLES['admin']] },
      hidden: true,
    },
    {
      path: ':subjectSlug/quiz-makers',
      name: 'QuizMakers',
      component: () => import('@/views/subjects/quiz-makers/List'),
      meta: { title: 'Giáo viên ra đề', noCache: true, roles: [ALL_ROLES['admin']] },
      hidden: true,
    },
    {
      path: ':subjectSlug/questions',
      name: 'QuestionsList',
      component: () => import('@/views/subjects/questions/List'),
      meta: { title: 'Danh sách câu hỏi', noCache: true, roles: [ALL_ROLES['exams_maker']] },
      hidden: true,
    },
    {
      path: ':subjectSlug/questions/:questionId/edit',
      name: 'EditQuestion',
      component: () => import('@/views/subjects/questions/Edit'),
      meta: { title: 'Chỉnh sửa câu hỏi', noCache: true, roles: [ALL_ROLES['exams_maker']] },
      hidden: true,
    },
    {
      path: ':subjectSlug/questions/create',
      name: 'CreateQuestion',
      component: () => import('@/views/subjects/questions/Create'),
      meta: { title: 'Tạo câu hỏi', noCache: true, roles: [ALL_ROLES['exams_maker']] },
      hidden: true,
    },
    {
      path: ':subjectSlug/content',
      name: 'SubjectContent',
      component: () => import('@/views/subjects/contents/List'),
      meta: { title: 'Nội dung môn học', noCache: true, roles: [ALL_ROLES['exams_maker']] },
      hidden: true,
    },
    {
      path: ':subjectSlug/quiz-format',
      name: 'QuizFormat',
      component: () => import('@/views/subjects/quiz-format/Detail'),
      meta: { title: 'Định dạng đề thi', noCache: true, roles: [ALL_ROLES['exams_maker']] },
      hidden: true,
    },
  ],
};

export default subjectRoutes;

