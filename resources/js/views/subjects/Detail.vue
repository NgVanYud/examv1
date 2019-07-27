<template>
  <div class="app-container">
    <el-tabs v-model="activeName" @tab-click="changeTab" lazy>
      <el-tab-pane label="Cập Nhật Thông Tin" name="edit" v-if="includeRoles(userRoles, [allRoles['admin']], false)">
        <edit-tab></edit-tab>
      </el-tab-pane>
      <el-tab-pane label="Giáo viên ra đề" name="teacher" v-if="includeRoles(userRoles, [allRoles['admin']], false)">
        <teacher-tab :item="item"></teacher-tab>
      </el-tab-pane>
      <el-tab-pane label="Nội Dung Môn Học" name="content" v-if="includeRoles(userRoles, [allRoles['exams_maker']], false)">
        <subject-content-tab></subject-content-tab>
      </el-tab-pane>
      <el-tab-pane label="Định Dạng Đề Thi" name="exam-format" v-if="includeRoles(userRoles, [allRoles['exams_maker']], false)">
        <exam-format-tab></exam-format-tab>
      </el-tab-pane>
      <el-tab-pane label="Danh Sách Câu Hỏi" name="questions" v-if="includeRoles(userRoles, [allRoles['exams_maker']], false)">
        <question-tab></question-tab>
      </el-tab-pane>
      <el-tab-pane label="Tạo Câu Hỏi" name="question_creation" v-if="includeRoles(userRoles, [allRoles['exams_maker']], false)">
        <question-creation-tab></question-creation-tab>
      </el-tab-pane>
      <el-tab-pane label="Task" name="task">Task</el-tab-pane>
    </el-tabs>

  </div>
</template>

<script>
import SubjectResource from '@/api/subject';
import RoleResource from '@/api/role';

import SubjectContentTab from './components/tabs/SubjectContent';
import EditTab from './components/tabs/Edit';
import TeacherTab from './components/tabs/Teacher';
import QuestionTab from './components/tabs/Question';
import ExamFormatTab from './components/tabs/ExamFormat';
import QuestionCreationTab from './components/tabs/QuestionCreation';

import { ALL_ROLES } from '@/utils/auth';
import { includes as includeRoles } from '@/utils/role';
import { include as includeRole } from '@/utils/role';

const subjectResource = new SubjectResource();
// const userResource = new UserResource();
const roleResource = new RoleResource();
// const permissionResource = new Resource('permissions');

export default {
  name: 'SubjectDetail',
  components: {
    // InfoTag,
    EditTab,
    TeacherTab,
    SubjectContentTab,
    QuestionTab,
    ExamFormatTab,
    QuestionCreationTab,
  },
  filters: {
    roles(roles) {
      const rolesArr = [];
      for (let i = 0; i < roles.length; i++) {
        rolesArr.push(roles[i].name);
      }
      return rolesArr.join(', ');
    },
    statusFilter(status) {
      const statusMap = {
        published: 'success',
        draft: 'info',
        deleted: 'danger',
      };
      return statusMap[status];
    },
  },
  data() {
    return {
      // Config
      activeName: '',
      userRoles: [],
      // Teacher
      loading: true,
      downloading: false,
      roles: '',
      allRoles: ALL_ROLES,
      // Edit
      item: {},
    };
  },
  computed: {

  },
  methods: {
    getDefaultTab() {
      this.getUserRoles();
      if (this.includeRoles(this.userRoles, [this.allRoles['admin']], false)) {
        this.activeName = 'edit';
      } else if (this.includeRoles(this.userRoles, [this.allRoles['exams_maker']], false)) {
        this.activeName = 'content';
      } else {
        this.activeName = 'task';
      }
    },
    changeTab(tab) {
      // console.log(tab.name);
    },
    itemDetail(idKey) {
      // this.loading = true;
      subjectResource.get(idKey).then(response => {
        const { data } = response;
        this.item = data;
        this.getSubjectExamMakers();
        this.listChapters();
      }).catch(() => {

      }).finally(() => {
        // this.loading = false;
      });
    },

    getRoles() {
      roleResource.teachers().then(response => {
        const { data } = response;
        this.roles = data;
      });
    },

    getUserRoles() {
      this.userRoles = this.$store.getters.roles;
    },
    includeRole,
    includeRoles,
  },
  created() {
    this.getDefaultTab();
  },
};
</script>

<style scoped>

</style>

