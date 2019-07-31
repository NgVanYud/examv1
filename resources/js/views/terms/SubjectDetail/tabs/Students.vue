<template>
  <div>
    <div class="filter-container">
      <div class="d-flex">
        <div>
          <el-input
            v-model="query.keyword"
            :placeholder="$t('table.keyword')"
            style="width: 200px;"
            class="filter-item"
            @keyup.enter.native="handleFilter" />
          <el-button
            v-waves
            class="filter-item"
            type="primary"
            icon="el-icon-search"
            @click="handleFilter">
            {{ $t('table.search') }}
          </el-button>
        </div>
        <div class="ml-auto">
          <el-button
            class="filter-item"
            type="primary"
            icon="el-icon-plus"
            @click="handleCreate">
            {{ $t('table.add') }}
          </el-button>
          <el-button
            v-waves
            :loading="downloading"
            class="filter-item"
            type="primary"
            icon="el-icon-download"
            @click="handleDownload">
            {{ $t('table.export') }}
          </el-button>
<!--          <el-select-->
<!--            v-model="query.roles"-->
<!--            :placeholder="$t('table.role')"-->
<!--            clearable-->
<!--            class="filter-item"-->
<!--            @change="handleFilter">-->
<!--            <el-option v-for="item in roles" :key="item.id" :label="item.name" :value="item.id" />-->
<!--          </el-select>-->
        </div>
      </div>

    </div>

    <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column
        type="selection" align="center">
      </el-table-column>
      <el-table-column align="center" :label="$t('table.num')" width="50">
        <template slot-scope="scope">
          <span>{{ scope.row.index }}</span>
        </template>
      </el-table-column>

      <el-table-column :label="$t('table.code')" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.code }}</span>
        </template>
      </el-table-column>

      <el-table-column :label="$t('table.username')" width="200">
        <template slot-scope="scope">
          <span>{{ scope.row.username }}</span>
        </template>
      </el-table-column>

      <el-table-column :label="$t('table.lastName')" width="150">
        <template slot-scope="scope">
          <span>{{ scope.row.last_name }}</span>
        </template>
      </el-table-column>

      <el-table-column :label="$t('table.firstName')" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.first_name }}</span>
        </template>
      </el-table-column>

      <el-table-column :label="'Mã đề thi'" width="120">
        <template slot-scope="scope">
          <span>{{ scope.row.quiz.code }}</span>
        </template>
      </el-table-column>

      <el-table-column :label="$t('table.status')" align="center" width="100">
        <template slot-scope="scope">
          <el-tag v-if="scope.row.is_actived" type="success">
            <i class="el-icon-success"></i>
          </el-tag>
          <el-tag v-else type="danger">
            <i class="el-icon-error"></i>
          </el-tag>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('table.action')">
        <template slot-scope="scope">
          <router-link
            :to = "{ name: 'ManagerEdit', params: { id: scope.row.uuid }}">
            <!--            <el-button type="primary" size="mini" icon="el-icon-edit" v-permission="['manage user']">-->
            <el-button
              type="primary"
              size="mini"
              icon="el-icon-edit"
              :title="$t('button.edit')">
            </el-button>
          </router-link>
          <!--          <el-button type="warning" size="mini" icon="el-icon-edit" v-if="!scope.row.roles.includes('admin')" v-permission="['manage permission']" @click="handleEditPermissions(scope.row.id);">-->
          <!--            Permissions-->
          <!--          </el-button>-->
          <!--          <el-button type="warning" size="mini" icon="el-icon-edit" v-if="!scope.row.roles.includes('admin')" v-permission="['manage permission']" @click="handleEditPermissions(scope.row.id);">-->
          <!--            Permissions-->
          <!--          </el-button>-->
          <el-button
            v-if="!scope.row.deleted_at"
            type="danger"
            size="mini"
            icon="el-icon-delete"
            @click="handleDelete(scope.row);" :title="$t('button.delete')">
          </el-button>
          <el-button
            v-if="scope.row.deleted_at"
            type="info"
            icon="el-icon-refresh-right"
            @click="handleRestore(scope.row);" :title="$t('button.restore')">
          </el-button>
          <el-button
            class="m-0"
            v-if="!scope.row.is_actived"
            type="success"
            icon="el-icon-check"
            @click="handleActive(scope.row);"
            :title="$t('button.active')">
          </el-button>
          <el-button
            v-else
            class="m-0"
            type="warning"
            icon="el-icon-close"
            @click="handleDeactive(scope.row);"
            :title="$t('button.block')">
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination
      v-show="total>0"
      :total="total"
      :page.sync="query.page"
      :limit.sync="query.limit"
      :pageSizes="[30, 40, 50, 60]"
      @pagination="getList" />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import ManagerResource from '@/api/manager';
import TermResource from '@/api/term';
import waves from '@/directive/waves'; // Waves directive
import permission from '@/directive/permission'; // Waves directive
import { getNotification } from '@/utils/notification';
// import { uppercaseFirst } from '@/filters';

const userResource = new ManagerResource();
const termResource = new TermResource();

export default {
  components: { Pagination },
  directives: { waves, permission },
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
    // var validateConfirmPassword = (rule, value, callback) => {
    //   if (value !== this.newItem.password) {
    //     callback(new Error('Password is mismatched!'));
    //   } else {
    //     callback();
    //   }
    // };
    return {
      list: null,
      total: 0,
      loading: true,
      downloading: false,
      itemCreating: false,
      roles: '',
      query: {
        page: 1,
        limit: 50,
        search: '',
      },
    };
  },
  computed: {
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      const termId = this.$route.params.termId;
      const subjectSlug = this.$route.params.subjectSlug;
      const { limit, page } = this.query;
      this.loading = true;
      try {
        const { data, meta } = await termResource.getStudents(termId, subjectSlug, this.query);
        this.list = data;
        this.list.forEach((element, index) => {
          element['index'] = (page - 1) * limit + index + 1;
        });
        this.total = meta.total;
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    handleCreate() {
      this.resetNewUser();
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['itemForm'].clearValidate();
      });
    },
    handleDelete(item) {
      this.$confirm(
        this.$t('notification.action.delete') + ' ' + this.$t('notification.object.user') + ' ' + item.first_name + '. ' + this.$t('notification.action.continue') + '?', 'Warning',
        {
          confirmButtonText: this.$t('button.ok'),
          cancelButtonText: this.$t('button.cancel'),
          type: 'warning',
        }
      ).then(() => {
        userResource.destroy(item.uuid).then(response => {
          getNotification(
            this.$t('notification.action.delete'),
            this.$t('notification.object.user'),
            this.$t('notification.status.success'),
            'success',
          );
          this.handleFilter();
        }).catch(error => {
          console.log(error);
          getNotification(
            this.$t('notification.action.delete'),
            this.$t('notification.object.user'),
            this.$t('notification.status.error')
          );
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'Hủy xóa người dùng',
        });
      });
    },
    handleDownload() {
      this.downloading = true;
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['id', 'user_id', 'name', 'email', 'role'];
        const filterVal = ['index', 'id', 'name', 'email', 'role'];
        const data = this.formatJson(filterVal, this.list);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'user-list',
        });
        this.downloading = false;
      });
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },
    handleActive(item) {
      userResource.active(item.uuid).then(response => {
        getNotification(
          this.$t('notification.action.active'),
          this.$t('notification.object.account'),
          this.$t('notification.status.success'),
          'success'
        );
        this.refreshData();
      }).catch(error => {
        console.log(error);
        getNotification(
          this.$t('notification.action.active'),
          this.$t('notification.object.account'),
          this.$t('notification.status.error')
        );
      });
    },
    handleDeactive(item) {
      userResource.deactive(item.uuid).then(response => {
        getNotification(
          this.$t('notification.action.deactive'),
          this.$t('notification.object.account'),
          this.$t('notification.status.success'),
          'success'
        );
        this.refreshData();
      }).catch(error => {
        console.log(error);
        getNotification(
          this.$t('notification.action.deactive'),
          this.$t('notification.object.account'),
          this.$t('notification.status.error')
        );
      });
    },
    refreshData() {
      this.getList();
    },
  },
};
</script>

<style lang="scss" scoped>

</style>
