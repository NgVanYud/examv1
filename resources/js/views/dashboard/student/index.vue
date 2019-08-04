<template>
  <div class="dashboard-editor-container">
    student
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  name: 'HomeStudent',
  data() {
    return {
    };
  },
  computed: {
    ...mapGetters([
      'name',
      'avatar',
      'roles',
    ]),
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
</style>
