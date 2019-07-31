<template>
  <div class="app-container">
    <h6 class="title-partial">Kỳ thi: {{ term.code }} - Môn thi: {{ subject.name | uppercaseFirst }}</h6>

  </div>
</template>

<script>
import SubjectResource from '@/api/subject';
import TermResource from '@/api/term';
// import waves from '@/directive/waves'; // Waves directive
// import permission from '@/directive/permission'; // Waves directive
// import checkPermission from '@/utils/permission'; // Permission checking
// import { ALL_ROLES } from '@/utils/auth';
// import { includes as includeRoles } from '@/utils/role';

const subjectResource = new SubjectResource();
const termResource = new TermResource();

export default {
  data() {
    return {
      term: '',
      subject: '',
      subjects: [],
    };
  },
  methods: {
    termDetail(termId) {
      termResource.get(termId).then(response => {
        this.loading = true;
        const { data, subjects } = response;
        this.term = data;
        this.termSubjects = subjects;
        if (this.termSubjects && this.termSubjects.length > 0) {
          this.termSubjects.forEach((element, index) => {
            element['index'] = index + 1;
          });
        }
        const subjectId = this.$route.params.subjectSlug;
        this.subjectDetail(subjectId);
        this.subjectTermDetail(this.term.uuid, subjectId);
      }).catch(error => {
        console.log(error);
      }).finally(() => {
        this.loading = false;
      });
    },
    subjectDetail(subjectId) {
      subjectResource.get(subjectId).then(response => {
        const { data } = response;
        this.subject = data;
      }).catch(() => {

      }).finally(() => {
      });
    },
  },
  created() {
    const subjectSlug = this.$route.params.subjectSlug;
    const termId = this.$route.params.termId;
    this.subjectDetail(subjectSlug);
    this.termDetail(termId);
  },
};
</script>

<style scoped>

</style>
