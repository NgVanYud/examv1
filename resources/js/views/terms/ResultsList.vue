<!--Cán bộ coi thi-->
<template>
  <div class="app-container">
    <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%" size="mini">
      <el-table-column label="STT" width="60">
        <template slot-scope="scope">
          <span>{{ scope.row.index }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Mã Số" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.student_code }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Họ">
        <template slot-scope="scope">
          <span>{{ scope.row.last_name }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Tên">
        <template slot-scope="scope">
          <span>{{ scope.row.first_name }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Mã Đề Thi" width="200">
        <template slot-scope="scope">
          <span>{{ scope.row.quiz }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Điểm">
        <template slot-scope="scope">
          <span>{{ scope.row.score }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Thao Tác" width="180">
        <template slot-scope="scope">
          <el-button
            v-if="scope.row.status !== 2"
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
        </template>
      </el-table-column>
    </el-table>
    <pagination
      v-show="total>0"
      :total="total"
      :page.sync="query.page"
      :limit.sync="query.limit"
      @pagination="getList" />
  </div>
</template>

<script>
import TermResource from '@/api/term';
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
// import { getNotification } from '@/utils/notification';

const termResource = new TermResource();

export default {
  components: {
    Pagination,
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
      list: null, // Danh sách kết quả của tất cả sinh viên tham gia môn thi
      loading: true,
      query: {
        page: 1,
        limit: 10,
      },
      total: 0,
    };
  },
  methods: {
    getList() { // Danh sách kết quả
      this.loading = true;
      const { limit, page } = this.query;
      const subjectTermId = this.$route.params.subjectTermId;
      termResource.getResults(subjectTermId, this.query).then(response => {
        const { data, meta } = response;
        console.log('all result: ', data);
        this.list = data;
        this.total = meta.total;
        this.list.forEach((element, index) => {
          element['index'] = (page - 1) * limit + index + 1;
        });
      }).catch(error => {
        console.log(error);
      }).finally(() => {
        this.loading = false;
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
