// import Layout from '@/layout';
// import { ALL_ROLES } from '@/utils/auth';
//
// const termRoutes = {
//   path: '/terms',
//   component: Layout,
//   redirect: '/terms/list',
//   meta: {
//     title: 'Đợt Thi',
//     icon: 'subject',
//     permissions: ['view menu components'],
//     roles: [ALL_ROLES['admin'], ALL_ROLES['exams_maker']],
//   },
//   children: [
//     {
//       path: 'list',
//       component: () => import('@/views/subjects/List'),
//       name: 'SubjectsList',
//       meta: { title: 'Môn học', icon: 'subject', noCache: true },
//     },
//     {
//       path: 'edit/:slug',
//       component: () => import('@/views/subjects/Edit'),
//       name: 'SubjectEdit',
//       meta: { title: 'Chỉnh sửa môn học', noCache: true },
//       hidden: true,
//     },
//   ],
// };
//
// export default subjectRoutes;
//
