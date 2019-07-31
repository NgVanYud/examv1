<template>
  <div class="dashboard-container">
    <component :is="currentView"/>
  </div>
</template>

<script>
// import { mapGetters } from 'vuex';
// import adminDashboard from './admin';
// import editorDashboard from './editor';
// import studentDashboard from './student';
// import managerDashboard from './manager';
// import { ALL_ROLES } from '@/utils/auth';
import setting from '@/views/terms/SettingSubject';
import detail from '@/views/terms/SubjectDetail/Detail';
import TermResource from '@/api/term';
const termResource = new TermResource();

export default {
  components: { setting, detail },
  data() {
    return {
      subjectTerm: '',
      currentView: undefined,
    };
  },
  computed: {
  },
  methods: {
    getView(termId, subjectId) {
      this.subjectTermDetail(termId, subjectId).then(termData => {
        console.log('data term: ', termData);
        if (!termData.is_configed) {
          this.currentView = 'setting';
        } else {
          this.currentView = 'detail';
        }
      });
    },
    subjectTermDetail(termId, subjectId) {
      return new Promise((resolve, reject) => {
        termResource.subjectTermDetail(termId, subjectId).then(response => {
          const { data } = response;
          resolve(data);
        }).catch(error => {
          console.log(error);
          reject(error);
        });
      });
    },
  },
  created() {
    const termId = this.$route.params.termId;
    const subjectSlug = this.$route.params.subjectSlug;
    this.getView(termId, subjectSlug);
  },
};
</script>
