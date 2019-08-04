<template>
  <div>
    <div class="">
      <div class="mb-3">
        <b><i class="el-icon-timer"></i> Thời gian làm bài: </b> {{ list[0].timeout }} <i>phút</i> -
        <b><i class="el-icon-question"></i> Số lượng câu hỏi</b>: {{ list[0].question_num }} <i>câu</i>
      </div>
      <el-tabs type="card" lazy>
        <el-tab-pane
          v-for="item in list"
          :key="item.code"
          :label="item.code"
          :name="item.code" >
          <quiz-frame
            :detail="item.detail | json2Arr"
            :subject-term="subjectTerm"
            :answer="item.answer | json2Arr"
          ></quiz-frame>
        </el-tab-pane>
      </el-tabs>

    </div>
  </div>
</template>

<script>
import TermResource from '@/api/term';
import QuizFrame from '@/views/terms/SubjectDetail/tabs/components/QuizFrame';

const termResource = new TermResource();

export default {
  components: {
    QuizFrame,
  },
  filters: {
    json2Arr: function (value) {
      return JSON.parse(value);
    },
  },
  data() {
    return {
      list: [],
      loading: false,
      subjectTerm: {},
    };
  },
  computed: {
    activeName: function() {
      return (this.list)[0].code;
    },
  },
  created() {
    this.getQuizs();
    this.getSubjectTermDetail();
  },
  methods: {
    getQuizs() {
      const termId = this.$route.params.termId;
      const subjectSlug = this.$route.params.subjectSlug;
      this.loading = true;
      termResource.getQuizs(termId, subjectSlug).then(response => {
        const { data } = response;
        this.list = data;
      }).catch(error => {
        console.log(error);
      }).finally(() => {
        this.loading = false;
      });
    },
    getSubjectTermDetail() {
      const termId = this.$route.params.termId;
      const subjectSlug = this.$route.params.subjectSlug;
      termResource.subjectTermDetail(termId, subjectSlug).then(response => {
        const { data } = response;
        this.subjectTerm = data;
      }).catch(error => {
        console.log(error);
      });
    },
  },
  beforeDestroy() {
    this.list = undefined;
  },
};
</script>

<style scoped>

</style>
