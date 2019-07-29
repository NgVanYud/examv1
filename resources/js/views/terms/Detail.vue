<template>
  <div class="app-container">
    <div>
      <div>
        <h6 class="title-partial">Thông Tin Cơ Bản</h6>
      </div>
      <table class="table table-bordered table-sm">
        <tbody>
        <tr>
          <th scope="row">Mã Số</th>
          <td>{{ term.code }}</td>
        </tr>
        <tr>
          <th scope="row">Tên</th>
          <td>{{ term.name }}</td>
        </tr>
        <tr>
          <th scope="row">Ngày Bắt Đầu</th>
          <td>{{ term.begin }}</td>
        </tr>
        <tr>
          <th scope="row">Ngày Kết Thúc</th>
          <td>{{ term.end }}</td>
        </tr>
        </tbody>
      </table>
    </div>

    <div>
      <div>
        <h6 class="title-partial">Danh Sách Môn Thi</h6>
      </div>
      <el-table v-loading="loading" :data="termSubjects" border fit highlight-current-row style="width: 100%" size="mini">
        <el-table-column
          type="selection" align="center">
        </el-table-column>
        <el-table-column align="center" label="STT" width="50">
          <template slot-scope="scope">
            <span>{{ scope.row.index }}</span>
          </template>
        </el-table-column>

        <el-table-column label="Mã Số" width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.code }}</span>
          </template>
        </el-table-column>

        <el-table-column label="Tên">
          <template slot-scope="scope">
            <span>{{ scope.row.name }}</span>
          </template>
        </el-table-column>

        <el-table-column label="Số Tín Chỉ" align="center" width="85">
          <template slot-scope="scope">
            <span>{{ scope.row.credit }}</span>
          </template>
        </el-table-column>

        <el-table-column label="Mô Tả">
          <template slot-scope="scope">
            <span>{{ scope.row.description }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="Thao Tác" width="150">
          <template slot-scope="scope">
            <router-link :to = "{ name: 'SettingTermSubject', params: { termId: term.id, subjectSlug: scope.row.slug }}">
              <el-button type="primary" size="mini" icon="el-icon-edit" title="Thiết Lập Thông Tin">
              </el-button>
            </router-link>
<!--            <router-link :to = "{ name: 'SubjectDetail', params: { slug: scope.row.slug }}" v-if="includeRoles(userRoles, [allRoles.admin, allRoles.exams_maker], false)">-->
<!--              <el-button type="warning" size="mini" icon="el-icon-document" title="Tùy Chọn">-->
<!--              </el-button>-->
<!--            </router-link>-->
<!--            <el-button v-if="includeRoles(userRoles, [allRoles.admin], true)" type="danger" size="mini" icon="el-icon-delete" @click="handleDelete(scope.row);"  title="Xóa">-->
<!--            </el-button>-->
            <!--          <router-link :to = "{ name: 'SubjectEdit', params: { slug: scope.row.slug }}" v-if="includeRoles(userRoles, [allRoles.admin], false)">-->
            <!--            <el-button type="warning" size="mini" icon="el-icon-document" title="Giáo viên ra đề">-->
            <!--            </el-button>-->
            <!--          </router-link>-->
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
// import SubjectResource from '@/api/subject';
import TermResource from '@/api/term';
// import waves from '@/directive/waves'; // Waves directive
// import permission from '@/directive/permission'; // Waves directive
// import checkPermission from '@/utils/permission'; // Permission checking
// import { ALL_ROLES } from '@/utils/auth';
// import { includes as includeRoles } from '@/utils/role';

// import { getNotification } from '@/utils/notification';

// const subjectResource = new SubjectResource();
const termResource = new TermResource();

export default {
  name: 'TermDetail',
  data() {
    return {
      loading: true,
      term: {},
      termSubjects: [],
      subjects: [],
      subjectConfig: {
        query: {
          page: 1,
          limit: 10,
          keyword: '',
        },
      },
    };
  },
  methods: {
    termDetail(termId) {
      termResource.get(termId).then(response => {
        this.loading = true;
        const { data, subjects } = response;
        this.term = data;
        this.termSubjects = subjects;
        if (this.termSubjects.length > 0) {
          this.termSubjects.forEach((element, index) => {
            element['index'] = index + 1;
          });
        }
      }).catch(error => {
        console.log(error);
      }).finally(() => {
        this.loading = false;
      });
    },
    // allSubjects() {
    //   const { limit, page } = this.subjectConfig.query;
    //   this.loading = true;
    //   const { data, meta } = subjectResource.list(this.subjectConfig.query).then(response => {
    //     this.subjects = data;
    //     if (this.subjects.length > 0) {
    //       this.subjects.forEach((element, index) => {
    //         element['index'] = (page - 1) * limit + index + 1;
    //       });
    //     }
    //     this.subjectConfig.total = meta.total;
    //     this.loading = false;
    //   }).catch(error => {
    //     console.log(error);
    //   }).finally(() => {
    //     this.loading = false;
    //   });
    // },
  },
  created() {
    const termId = this.$route.params.id;
    this.termDetail(termId);
    // this.allSubjects();
  },
};
</script>

<style scoped>

</style>
