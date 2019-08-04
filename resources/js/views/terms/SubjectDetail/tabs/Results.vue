<!--Cán bộ coi thi-->
<template>
  <div>
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
          <span>{{ scope.row.quiz.code }}</span>
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
            type="primary"
            size="mini"
            icon="el-icon-download"
            title="Tạo phiếu điểm"
            @click="createResultSheet(scope.row)">
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
import { generateResultDetail as exportResultSheet } from '@/utils/export';
import { oneChar2TwoChars } from '@/filters';

const termResource = new TermResource();

export default {
  components: {
    Pagination,
  },
  data() {
    return {
      list: null, // Danh sách kết quả của tất cả sinh viên tham gia môn thi
      loading: true,
      subjectTerm: {},
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
      const subjectTermId = this.subjectTerm.id;
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
    getSubjectTerm() {
      this.loading = true;
      const termId = this.$route.params.termId;
      const subjectSlug = this.$route.params.subjectSlug;
      termResource.subjectTermDetail(termId, subjectSlug).then(response => {
        const { data } = response;
        this.subjectTerm = data;
        this.getList();
        console.log('subjectTerm Detail: ', data);
      }).catch(error => {
        console.log(error);
      }).finally(() => {
        this.loading = false;
      });
    },
    createResultSheet(item) {
      const detailInfo = this.parseResultData(item);
      exportResultSheet(detailInfo);
    },
    parseResultData(item) {
      const info = {};
      info.term = item.term.name.toUpperCase();
      info.quiz_name = item.quiz.name;
      info.quiz_code = item.quiz.code;
      info.student_name = item.fullname;
      info.student_code = item.student_code;
      info.subject = item.subject.name.toUpperCase();
      info.score = item.score;
      info.answers = this.parseMultiChoices(item.detail, item.questions_total);
      info.key = item.quiz.answer;
      const createdAt = new Date(item.updated_at);
      info.day = oneChar2TwoChars('' + createdAt.getDate());
      info.month = oneChar2TwoChars('' + (createdAt.getMonth() + 1));
      info.year = createdAt.getFullYear();
      return info;
    },
    parseMultiChoices(info, questionNum) {
      const detail = [];
      for (let i = 0; i < questionNum; i++) {
        const tmp = {
          num: oneChar2TwoChars('' + (i + 1)),
          result: info[i] || 'Ø',
        };
        detail.push(tmp);
      }
      return detail;
    },
  },
  created() {
    this.getSubjectTerm();
  },
};
</script>

<style scoped>

</style>
