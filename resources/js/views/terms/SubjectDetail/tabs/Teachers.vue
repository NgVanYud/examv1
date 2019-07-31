<template>
  <div>
    <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column
        type="index"
        width="50">
      </el-table-column>
      <el-table-column :label="$t('table.code')" width="200">
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
<!--          <router-link-->
<!--            :to = "{ name: 'ManagerEdit', params: { id: scope.row.uuid }}">-->
<!--            &lt;!&ndash;            <el-button type="primary" size="mini" icon="el-icon-edit" v-permission="['manage user']">&ndash;&gt;-->
<!--            <el-button-->
<!--              type="primary"-->
<!--              size="mini"-->
<!--              icon="el-icon-edit"-->
<!--              :title="$t('button.edit')">-->
<!--            </el-button>-->
<!--          </router-link>-->
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
<!--          <el-button-->
<!--            v-if="scope.row.deleted_at"-->
<!--            type="info"-->
<!--            icon="el-icon-refresh-right"-->
<!--            @click="handleRestore(scope.row);" :title="$t('button.restore')">-->
<!--          </el-button>-->
<!--          <el-button-->
<!--            class="m-0"-->
<!--            v-if="!scope.row.is_actived"-->
<!--            type="success"-->
<!--            icon="el-icon-check"-->
<!--            @click="handleActive(scope.row);"-->
<!--            :title="$t('button.active')">-->
<!--          </el-button>-->
<!--          <el-button-->
<!--            v-else-->
<!--            class="m-0"-->
<!--            type="warning"-->
<!--            icon="el-icon-close"-->
<!--            @click="handleDeactive(scope.row);"-->
<!--            :title="$t('button.block')">-->
<!--          </el-button>-->
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import ManagerResource from '@/api/manager';
import TermResource from '@/api/term';
import waves from '@/directive/waves'; // Waves directive
import permission from '@/directive/permission'; // Waves directive
import { getNotification } from '@/utils/notification';
// import { uppercaseFirst } from '@/filters';

const userResource = new ManagerResource();
const termResource = new TermResource();

export default {
  components: { },
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
    return {
      list: [],
      loading: true,
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
      this.loading = true;
      try {
        const { data } = await termResource.getProtors(termId, subjectSlug);
        console.log('teacher: ', data);
        this.list = data;
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    handleFilter() {
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
    refreshData() {
      this.getList();
    },
  },
};
</script>

<style lang="scss" scoped>

</style>
