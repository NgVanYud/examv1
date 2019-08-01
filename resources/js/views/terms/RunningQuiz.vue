<!--for student-->
<template>
  <div class="app-container">
    <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%" size="mini">
      <el-table-column
        type="index"
        width="50">
      </el-table-column>
      <el-table-column label="Môn Thi" width="400">
        <template slot-scope="scope">
          <span>{{ scope.row.subject.name | uppercaseFirst }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Kỳ Thi">
        <template slot-scope="scope">
          <span>{{ scope.row.term.code }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Thao Tác" width="180">
        <template slot-scope="scope">
         <router-link :to = "{ name: 'DoQuiz', params: { subjectTermId: scope.row.id }}">
            <el-button type="success" size="mini" icon="el-icon-d-arrow-right" title="Làm Bài Thi">
            </el-button>
          </router-link>
        </template>
      </el-table-column>
    </el-table>

  </div>
</template>

<script>
import QuizResource from '@/api/quiz';
// import { getNotification } from '@/utils/notification';

const quizResource = new QuizResource();

export default {
  components: {
  },
  data() {
    return {
      list: [],
      loading: true,
    };
  },
  methods: {
    getQuiz() {
      this.loading = true;
      quizResource.getInfo().then(response => {
        const { data } = response;
        this.list.push(data);
      }).catch(error => {
        console.log(error);
      }).finally(() => {
        this.loading = false;
      });
    },
    detailQuiz(item) {
      this.$router.push({ name: 'DoQuiz', params: { subjectTermId: item.id }});
    },
  },
  created() {
    this.getQuiz();
  },
};
</script>

<style scoped>

</style>
