<template>
  <div class="app-container">
    <div class="filter-container">
      <div class="d-flex">
        <div>
          <el-input size="mini" v-model="query.keyword" :placeholder="$t('table.keyword')" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
          <el-button size="mini" v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
            {{ $t('table.search') }}
          </el-button>
        </div>
<!--        <div class="d-inline-block ml-2" v-if="activedActionBtns">-->
<!--          <el-tooltip class="item" effect="dark" content="Xóa" placement="top">-->
<!--            <el-button icon="el-icon-delete" class="filter-delete" size="mini" circle @click="handleDeleteMulti"></el-button>-->
<!--          </el-tooltip>-->
<!--        </div>-->
        <div class="ml-auto">
          <router-link :to = "{ name: 'CreateTerm' }">
            <el-button type="primary" size="mini" title="Tùy Chọn" class="filter-item" icon="el-icon-plus">
              {{ $t('table.add') }}
            </el-button>
          </router-link>
<!--          <el-button size="mini" v-waves :loading="downloading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">-->
<!--            {{ $t('table.export') }}-->
<!--          </el-button>-->
        </div>
      </div>

    </div>

    <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%" size="mini">
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

      <el-table-column label="Ngày Bắt Đầu">
        <template slot-scope="scope">
          <span>{{ scope.row.begin }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Ngày Kết Thúc">
        <template slot-scope="scope">
          <span>{{ scope.row.end }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Môn Thi">
        <template slot-scope="scope">
          {{ scope.row.subjects | subjects }}
        </template>
      </el-table-column>

      <el-table-column align="center" label="Thao Tác" width="180">
        <template slot-scope="scope">
          <router-link :to = "{ name: 'EditTerm', params: { id: scope.row.id }}" v-if="includeRoles(userRoles, [allRoles.curator], false)">
            <el-button type="primary" size="mini" icon="el-icon-edit" title="Chỉnh Sửa">
            </el-button>
          </router-link>
          <router-link :to = "{ name: 'TermDetail', params: { id: scope.row.id }}" v-if="includeRoles(userRoles, [allRoles.curator], false)">
            <el-button type="warning" size="mini" icon="el-icon-document" title="Chi Tiết">
            </el-button>
          </router-link>
          <el-button v-if="includeRoles(userRoles, [allRoles.admin], true)" type="danger" size="mini" icon="el-icon-delete" @click="handleDelete(scope.row);"  title="Xóa">
          </el-button>
          <!--          <router-link :to = "{ name: 'SubjectEdit', params: { slug: scope.row.slug }}" v-if="includeRoles(userRoles, [allRoles.admin], false)">-->
          <!--            <el-button type="warning" size="mini" icon="el-icon-document" title="Giáo viên ra đề">-->
          <!--            </el-button>-->
          <!--          </router-link>-->
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import waves from '@/directive/waves'; // Waves directive
import TermResource from '@/api/term';
// import UserResource from '@/api/user';
// import RoleResource from '@/api/role';
// import Resource from '@/api/resource';
// import permission from '@/directive/permission'; // Waves directive
// import checkPermission from '@/utils/permission'; // Permission checking
import { ALL_ROLES } from '@/utils/auth';
import { includes as includeRoles } from '@/utils/role';

const termResource = new TermResource();
// const userResource = new UserResource();
// const roleResource = new RoleResource();
// const permissionResource = new Resource('permissions');
export default {
  name: 'TermsList',
  components: {
    Pagination,
  },
  directives: { waves },
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
    subjects(subjects) {
      const subjectCounter = subjects.length;
      let subjectList = '';
      for (let i = 0; i < (subjectCounter); i++) {
        const tmpSubject = subjects[i];
        subjectList += tmpSubject.name + ', ';
      }
      subjectList = subjectList.substring(0, subjectList.length - 2);
      // subjectList += subjectList + (subjects[(subjectCounter - 1)]).name;
      return subjectList;
    },
  },
  data() {
    return {
      list: null,
      total: 0,
      loading: true,
      downloading: false,
      itemCreating: false,
      roles: '',
      allRoles: ALL_ROLES,
      query: {
        page: 1,
        limit: 10,
        keyword: '',
      },
      // roles: ['admin', 'manager', 'editor', 'user', 'visitor'],
      nonAdminRoles: ['editor', 'user', 'visitor'],
      userRoles: '',
    };
  },
  methods: {
    getList() {
      const { limit, page } = this.query;
      this.loading = true;
      termResource.list(this.query).then(response => {
        const { data, meta } = response;
        this.list = data;
        this.list.forEach((element, index) => {
          element['index'] = (page - 1) * limit + index + 1;
        });
        this.total = meta.total;
        this.loading = false;
      }).catch(error => {
        console.log(error);
      }).finally(() => {
        this.loading = false;
      });
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    getUserRoles() {
      this.userRoles = this.$store.getters.roles;
    },
    handleCreate() {
      // this.resetNewUser();
      // this.dialogFormVisible = true;
      // this.$nextTick(() => {
      //   this.$refs['itemForm'].clearValidate();
      // });
    },
    includeRoles,
  },
  created() {
    this.getList();
    this.getUserRoles();
  },
};
</script>

<style scoped>

</style>
