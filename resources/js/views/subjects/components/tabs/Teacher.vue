<template>
  <div>
    <div class="mb-4">
      <div>
        <h5>Danh sách giáo viên ra đề môn học <b>{{ this.subject.name }}</b></h5>
      </div>
      <div class="filter-container">
        <div class="d-flex flex-row-reverse">
          <div>
            <el-button size="mini" class="filter-item" type="primary" icon="el-icon-plus" @click="addExamMaker">
              <!--                  {{ $t('table.search') }}-->
              Thêm Giáo Viên Ra Đề
            </el-button>
          </div>
        </div>
      </div>

      <el-table v-loading="loading" :data="subjectExamMakers" border fit highlight-current-row style="width: 100%" size="mini">
        <el-table-column
          type="selection" align="center">
        </el-table-column>
        <el-table-column align="center" label="STT" width="50">
          <template slot-scope="scope">
            <span>{{ scope.row.index }}</span>
          </template>
        </el-table-column>

        <el-table-column align="center" label="Username" width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.username }}</span>
          </template>
        </el-table-column>

        <el-table-column align="center" label="Mã Số" width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.code }}</span>
          </template>
        </el-table-column>

        <el-table-column :label="$t('table.email')">
          <template slot-scope="scope">
            <span>{{ scope.row.email }}</span>
          </template>
        </el-table-column>

        <el-table-column :label="'Họ Tên'">
          <template slot-scope="scope">
            <span>{{ scope.row.full_name }}</span>
          </template>
        </el-table-column>

        <el-table-column :label="$t('table.role')">
          <template slot-scope="scope">
            <span>{{ scope.row.roles | roles }}</span>
          </template>
        </el-table-column>

        <el-table-column label="Kích Hoạt" align="center" width="100">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.active" type="success">
              <i class="el-icon-success"></i>
            </el-tag>
            <el-tag v-else type="danger">
              <i class="el-icon-error"></i>
            </el-tag>
          </template>
        </el-table-column>

        <el-table-column align="center" label="Thao Tác">
          <template slot-scope="scope">
            <el-button v-if="!includeRole(scope.row.roles, allRoles.admin)" type="danger" size="mini" icon="el-icon-delete" @click="handleDeleteExamMaker(scope.row);"  title="Xóa">
            </el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <div>
      <div>
        <h5>Danh sách tài khoản người dùng giáo viên</h5>
      </div>
      <el-table v-loading="loading" :data="allExamMakers" border fit highlight-current-row style="width: 100%" size="mini">
        <el-table-column
          type="selection" align="center">
        </el-table-column>
        <el-table-column align="center" label="STT" width="50">
          <template slot-scope="scope">
            <span>{{ scope.row.index }}</span>
          </template>
        </el-table-column>

        <el-table-column align="center" label="Username" width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.username }}</span>
          </template>
        </el-table-column>

        <el-table-column align="center" label="Mã Số" width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.code }}</span>
          </template>
        </el-table-column>

        <el-table-column :label="$t('table.email')">
          <template slot-scope="scope">
            <span>{{ scope.row.email }}</span>
          </template>
        </el-table-column>

        <el-table-column :label="'Họ Tên'">
          <template slot-scope="scope">
            <span>{{ scope.row.full_name }}</span>
          </template>
        </el-table-column>

        <el-table-column :label="$t('table.role')">
          <template slot-scope="scope">
            <span>{{ scope.row.roles | roles }}</span>
          </template>
        </el-table-column>

        <el-table-column label="Kích Hoạt" align="center" width="100">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.active" type="success">
              <i class="el-icon-success"></i>
            </el-tag>
            <el-tag v-else type="danger">
              <i class="el-icon-error"></i>
            </el-tag>
          </template>
        </el-table-column>

        <el-table-column align="center" label="Thao Tác">
          <template slot-scope="scope">
            <el-button type="primary" size="mini" icon="el-icon-plus" title="Thêm" @click="handleAddExamUser(scope.row)">
            </el-button>
          </template>
        </el-table-column>
      </el-table>

      <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getAllExamMakers" />
    </div>

  </div>
</template>

<script>
import UserResource from '@/api/user';
import SubjectResource from '@/api/subject';
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination

import { ALL_ROLES } from '@/utils/auth';
import { include as includeRole } from '@/utils/role';

const userResource = new UserResource();
const subjectResource = new SubjectResource();

export default {
  name: 'TeacherTab',
  components: {
    Pagination,
  },
  filters: {
    roles(roles) {
      const rolesArr = [];
      for (let i = 0; i < roles.length; i++) {
        rolesArr.push(roles[i].name);
      }
      return rolesArr.join(', ');
    },
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
      loading: true,
      subject: {},
      activedAddAction: false,
      subjectExamMakers: [],
      allExamMakers: [],
      query: {
        page: 1,
        limit: 10,
        keyword: '',
        except_users: '',
        role_name: '',
      },
      allRoles: ALL_ROLES,
      total: 0,
    };
  },
  methods: {
    subjectDetail(idKey) {
      this.loading = true;
      subjectResource.get(idKey).then(response => {
        const { data } = response;
        this.subject = data;
        this.getSubjectExamMakers(this.subject.id);
        this.getAllExamMakers();
      }).catch(() => {

      }).finally(() => {
        this.loading = false;
      });
    },
    addExamMaker() {
      this.activedAddAction = true;
      this.$nextTick(() => {
        this.getAllExamMakers();
      });
    },
    getAllExamMakers() {
      this.loading = true;
      const subjectExamMakerUuids = this.subjectExamMakers.map(examMaker => examMaker.uuid);
      this.query.except_users = subjectExamMakerUuids;
      this.query.role_name = this.allRoles['exams_maker'];
      userResource.getByRoleName(this.query).then(response => {
        const { data, meta } = response;
        this.allExamMakers = data;
        this.allExamMakers.forEach((element, index) => {
          element['index'] = (this.query.page - 1) * this.query.limit + index + 1;
        });
        this.total = meta.total;
      }).catch(error => {
        console.log(error);
        this.loading = false;
      }).finally(() => {
        this.loading = false;
      });
    },
    getSubjectExamMakers(subjectId) {
      this.loading = true;
      subjectResource.getExamMakers(subjectId).then(response => {
        const { data } = response;
        this.subjectExamMakers = data;
        this.subjectExamMakers.forEach((element, index) => {
          element['index'] = index + 1;
        });
        this.getAllExamMakers();
      }).catch(error => {
        console.log(error);
      }).finally(() => {
        this.loading = false;
      });
    },
    handleAddExamUser(user) {
      subjectResource.storeExamMaker(this.subject.id, user.uuid).then(response => {
        this.loading = true;
        // this.getAllExamMakers();
        // this.getSubjectExamMakers();
        this.handleFilter();
        this.$message({
          message: 'Thêm giáo viên ra đề thành công.',
          type: 'success',
          duration: 5 * 1000,
        });
        this.loading = false;
      }).catch(error => {
        this.$message({
          message: 'Thêm giáo viên ra đề không thành công.',
          type: 'error',
          duration: 5 * 1000,
        });
        console.log(error);
      }).finally(() => {
        this.loading = false;
      });
    },
    handleFilter() {
      this.query.page = 1;
      this.getSubjectExamMakers(this.subject.id);
      this.getAllExamMakers();
    },
    handleDeleteExamMaker(user) {
      this.$confirm('Xóa giáo viên ra đề ' + user.first_name + '. Tiếp tục?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Hủy',
        type: 'warning',
      }).then(() => {
        subjectResource.removeExamMaker(this.subject.id, user.uuid).then(response => {
          this.loading = true;
          this.getSubjectExamMakers(this.subject.id);
          // this.handleFilter();
          // this.getSubjectExamMakers();
          // this.getAllExamMakers();
          // this.handleFilter();
          this.$message({
            type: 'success',
            message: 'Xóa giáo viên ra đề thành công',
            duration: 5 * 1000,
          });
        }).catch(error => {
          console.log(error);
          this.$message({
            type: 'error',
            message: 'Xóa giáo viên ra đề không thành công',
            duration: 5 * 1000,
          });
        }).finally(() => {
          this.loading = false;
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'Hủy xóa giáo viên ra đề',
        });
      }).finally(() => {
        this.loading = false;
      });
    },
    getUserRoles() {
      this.userRoles = this.$store.getters.roles;
    },
    includeRole,
  },
  created() {
    const subjectName = this.$route.params.slug;
    this.subjectDetail(subjectName);
  },
};
</script>

<style scoped>

</style>
