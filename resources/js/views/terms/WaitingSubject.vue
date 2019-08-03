<!--Cán bộ coi thi-->
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

      <el-table-column label="Số Đề Thi">
        <template slot-scope="scope">
          <span>{{ scope.row.original_exam_num }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Kỳ Thi">
        <template slot-scope="scope">
          <span>{{ scope.row.term.code }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Thao Tác" width="180">
        <template slot-scope="scope">
          <el-button
            v-if="scope.row.status === 3"
            type="primary"
            size="mini"
            icon="el-icon-refresh"
            title="Kích hoạt bài thi"
            @click="activeQuizs(scope.row)">
          </el-button>
          <el-button
            v-if="scope.row.status === 2"
            type="danger"
            size="mini"
            icon="el-icon-close"
            title="Đóng bài thi"
            @click="closeQuizs(scope.row)">
          </el-button>
         <router-link :to = "{ name: 'TermSubjectResultsList', params: { subjectTermId: scope.row.id }}">
            <el-button type="success" size="mini" icon="el-icon-info" title="Kết quả">
            </el-button>
          </router-link>
        </template>
      </el-table-column>
    </el-table>

  </div>
</template>

<script>
import TermResource from '@/api/term';
import { getNotification } from '@/utils/notification';
// import { getNotification } from '@/utils/notification';

const termResource = new TermResource();

export default {
  components: {
  },
  filters: {
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
      list: null, // Danh sách các record SubjectTerm
      loading: true,
    };
  },
  methods: {
    getList() { // thong tin subjectTerm
      this.loading = true;
      termResource.subjectsForTerm().then(response => {
        const { data } = response;
        this.list = data;
      }).catch(error => {
        console.log(error);
      }).finally(() => {
        this.loading = false;
      });
    },
    activeQuizs(item) {
      this.$confirm(
        this.$t('notification.action.active') + ' ' + this.$t('notification.object.quiz') + ' ' + item.subject.name + ' ' + this.$t('notification.action.continue') + '?', 'Warning',
        {
          confirmButtonText: this.$t('button.ok'),
          cancelButtonText: this.$t('button.cancel'),
          type: 'warning',
        }
      ).then(() => {
        termResource.activeQuiz({ subject_term_id: item.id }).then(response => {
          this.getList();
          getNotification('Kích hoạt', 'bài thi', 'thành công', 'success');
        }).catch(error => {
          getNotification('Kích hoạt', 'bài thi', 'không thành công');
          console.log(error);
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'Hủy kích hoạt bài thi.',
        });
      });
    },
    closeQuizs(item) {
      this.$confirm(
        this.$t('notification.action.close') + ' ' + this.$t('notification.object.quiz') + ' ' + item.subject.name + ' ' + this.$t('notification.action.continue') + '?', 'Warning',
        {
          confirmButtonText: this.$t('button.ok'),
          cancelButtonText: this.$t('button.cancel'),
          type: 'warning',
        }
      ).then(() => {
        termResource.deactiveQuiz({ subject_term_id: item.id }).then(response => {
          this.getList();
          this.$router.push({ name: 'TermSubjectResultsList', params: { subjectTermId: item.id }});
          getNotification('Đóng', 'bài thi', 'thành công', 'success');
        }).catch(error => {
          getNotification('Bài thi', 'bài thi', 'không thành công');
          console.log(error);
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'Hủy đóng bài thi.',
        });
      });
    },
  },
  created() {
    this.getList();
  },
};
</script>

<style scoped>

</style>
