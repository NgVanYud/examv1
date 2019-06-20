<template>
  <div class="dashboard-container">
    <component :is="currentRole"/>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import adminDashboard from './admin';
import editorDashboard from './editor';
import studentDashboard from './student';
import managerDashboard from './manager';
import { ALL_ROLES } from '@/utils/auth';
import { includes as includeRoles } from '@/utils/role';

export default {
  name: 'Dashboard',
  components: { adminDashboard, editorDashboard, studentDashboard, managerDashboard },
  data() {
    return {
      currentRole: undefined,
    };
  },
  computed: {
    ...mapGetters([
      'roles',
    ]),
  },
  methods: {
    getView() {
      if (includeRoles([
        ALL_ROLES['exams_maker'],
        ALL_ROLES['curator'],
        ALL_ROLES['protor'],
        ALL_ROLES['admin'],
      ], this.roles)) {
        this.currentRole = 'managerDashboard';
      } else if (includeRoles([ALL_ROLES['student']], this.roles)) {
        this.currentRole = 'studentDashboard';
      } else {
        this.$router.push({ name: 'Page401' });
      }
    },
  },
  created() {
    this.getView();
    // if (!this.roles.includes(ALL_ROLES['admin'])) {
    //   this.currentRole = 'adminDashboard';
    // }
  },
};
</script>
