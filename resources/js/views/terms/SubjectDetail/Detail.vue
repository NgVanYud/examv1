<template>
  <div class="app-container">
    <div class="filter-container">
      <div class="ml-auto">
        <router-link :to = "{ name: 'CreateTerm' }">
          <el-button type="primary" size="mini" title="Cập nhật" class="filter-item" icon="el-icon-refresh">
            Cập nhật
          </el-button>
        </router-link>
        <!--          <el-button size="mini" v-waves :loading="downloading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">-->
        <!--            {{ $t('table.export') }}-->
        <!--          </el-button>-->
      </div>
    </div>
    <div>
      <i class="el-icon-s-finance"></i>
       Kỳ thi: <b>{{ subjectTerm.term.code }}</b> -
      <i class="el-icon-tickets"></i>
       Môn thi: <b>{{ subjectTerm.subject.name | uppercaseFirst }}</b>
    </div>
    <div class="">
      <el-tabs v-model="activeName" @tab-click="changeTab" lazy>
        <el-tab-pane label="Sinh viên" name="student" v-if="includeRoles(userRoles, [allRoles['curator']], false)">
          <student-tab></student-tab>
        </el-tab-pane>
        <el-tab-pane label="Giáo viên ra đề" name="teacher" v-if="includeRoles(userRoles, [allRoles['curator']], false)">
          <teacher-tab></teacher-tab>
        </el-tab-pane>
        <el-tab-pane label="Đề thi" name="content" v-if="includeRoles(userRoles, [allRoles['curator']], false)">
          <quiz-tab></quiz-tab>
        </el-tab-pane>
      </el-tabs>

    </div>
  </div>
</template>

<script>
import { includes as includeRoles } from '@/utils/role';
import { include as includeRole } from '@/utils/role';
import { ALL_ROLES } from '@/utils/auth';
import StudentTab from './tabs/Students';
import TeacherTab from './tabs/Teachers';
import QuizTab from './tabs/Quizs';
import TermResource from '@/api/term';

const termResource = new TermResource();

export default {
  components: {
    StudentTab,
    TeacherTab,
    QuizTab,
  },
  data() {
    return {
      userRoles: [],
      allRoles: ALL_ROLES,
      activeName: 'student',
      subjectTerm: {},
    };
  },
  methods: {
    getUserRoles() {
      this.userRoles = this.$store.getters.roles;
    },
    changeTab() {

    },
    subjectTermDetail() {
      const termId = this.$route.params.termId;
      const subjectSlug = this.$route.params.subjectSlug;
      termResource.subjectTermDetail(termId, subjectSlug).then(response => {
        const { data } = response;
        console.log('detail: ', data);
        this.subjectTerm = data;
      }).catch(error => {
        console.log(error);
      });
    },
    includeRole,
    includeRoles,
  },
  created() {
    this.getUserRoles();
    this.subjectTermDetail();
  },
};
</script>
<style scoped>

</style>
