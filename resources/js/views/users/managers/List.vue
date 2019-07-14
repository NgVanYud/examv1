<template>
  <div class="app-container">
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
          <el-select
            v-model="query.roles"
            :placeholder="$t('table.role')"
            clearable
            class="filter-item"
            @change="handleFilter">
            <el-option v-for="item in roles" :key="item.id" :label="item.name" :value="item.id" />
          </el-select>
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

      <el-table-column align="center" :label="$t('table.username')" width="100">
        <template slot-scope="scope">
          <span>{{ scope.row.username }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('table.code')" width="100">
        <template slot-scope="scope">
          <span>{{ scope.row.code }}</span>
        </template>
      </el-table-column>

      <el-table-column :label="$t('table.email')">
        <template slot-scope="scope">
          <span>{{ scope.row.email }}</span>
        </template>
      </el-table-column>

      <el-table-column :label="$t('table.fullname')">
        <template slot-scope="scope">
          <span>{{ scope.row.full_name }}</span>
        </template>
      </el-table-column>

      <el-table-column :label="$t('table.role')">
        <template slot-scope="scope">
          <span>{{ scope.row.roles | roles }}</span>
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
            :to = "{ name: 'UserEdit', params: { id: scope.row.uuid }}">
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
      @pagination="getList" />

    <el-dialog :visible.sync="dialogPermissionVisible" :title="'Edit Permissions - ' + currentUser.name">
      <div class="form-container" v-loading="dialogPermissionLoading" v-if="currentUser.name">
        <div class="permissions-container">
          <div class="block">
            <el-form :model="currentUser" label-width="80px" label-position="top">
              <el-form-item label="Menus">
                <el-tree ref="menuPermissions" :data="normalizedMenuPermissions" :default-checked-keys="permissionKeys(userMenuPermissions)" :props="permissionProps" show-checkbox node-key="id" class="permission-tree" />
              </el-form-item>
            </el-form>
          </div>
          <div class="block">
          <el-form :model="currentUser" label-width="80px" label-position="top">
              <el-form-item label="Permissions">
                <el-tree ref="otherPermissions" :data="normalizedOtherPermissions" :default-checked-keys="permissionKeys(userOtherPermissions)" :props="permissionProps" show-checkbox node-key="id" class="permission-tree" />
              </el-form-item>
            </el-form>
          </div>
          <div class="clear-left"></div>
        </div>
        <div style="text-align:right;">
          <el-button type="danger" @click="dialogPermissionVisible=false">
            {{ $t('permission.cancel') }}
          </el-button>
          <el-button type="primary" @click="confirmPermission">
            {{ $t('permission.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>

    <el-dialog title="Tạo mới người dùng" :visible.sync="dialogFormVisible">
      <div class="form-container" v-loading="itemCreating">
        <el-form ref="itemForm" :rules="rules" :model="newItem" label-position="left" label-width="150px" style="max-width: 500px;" size="mini">
          <el-form-item label="Tài Khoản" prop="username">
            <el-input v-model="newItem.username"/>
          </el-form-item>
          <el-form-item label="Mã Số" prop="code">
            <el-input v-model="newItem.code"/>
          </el-form-item>
          <el-form-item label="Họ" prop="last_name">
            <el-input v-model="newItem.last_name"/>
          </el-form-item>
          <el-form-item label="Tên" prop="first_name">
            <el-input v-model="newItem.first_name"/>
          </el-form-item>
          <el-form-item label="Email" prop="email">
            <el-input v-model="newItem.email"/>
          </el-form-item>
<!--          <el-form-item label="Kích Hoạt" prop="active">-->
<!--            <el-switch v-model="newItem.active"></el-switch>-->
<!--          </el-form-item>-->
          <el-form-item label="Quyền" prop="roles">
            <el-select v-model="newItem.role_ids" placeholder="Chọn Nhóm" multiple>
              <el-option v-for="item in roles" :label="item.name" :value="item.id" :key="item.id"/>
            </el-select>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false">
            {{ $t('button.cancel') }}
          </el-button>
          <el-button type="primary" @click="createUser()">
            {{ $t('button.create') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import ManagerResource from '@/api/manager';
import RoleResource from '@/api/role';
import Resource from '@/api/resource';
import waves from '@/directive/waves'; // Waves directive
import permission from '@/directive/permission'; // Waves directive
import checkPermission from '@/utils/permission'; // Permission checking
import { ALL_ROLES } from '@/utils/auth';
import { include as includeRole } from '@/utils/role';
import { getNotification } from '@/utils/notification';
import { uppercaseFirst } from '@/filters';

const userResource = new ManagerResource();
const roleResource = new RoleResource();
const permissionResource = new Resource('permissions');

export default {
  name: 'UsersList',
  components: { Pagination },
  directives: { waves, permission },
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
      allRoles: ALL_ROLES,
      query: {
        page: 1,
        limit: 10,
        roles: '',
        search: '',
      },
      // roles: ['admin', 'manager', 'editor', 'user', 'visitor'],
      newItem: {},
      dialogFormVisible: false,
      dialogPermissionVisible: false,
      dialogPermissionLoading: false,
      currentUserId: 0,
      currentUser: {
        name: '',
        permissions: [],
        rolePermissions: [],
      },
      rules: {
        username: [
          {
            required: true,
            message: this.$t('validation.required', {
              attribute: this.$t('validation.attributes.username'),
            }),
            trigger: ['change', 'blur'],
          },
          {
            min: 3,
            max: 15,
            message: this.$t('validation.between.string', {
              attribute: this.$t('validation.attributes.username'),
              min: 3,
              max: 15,
            }),
            trigger: ['change', 'blur'] },
        ],
        code: [
          {
            required: true,
            message: this.$t('validation.required', {
              attribute: this.$t('validation.attributes.code'),
            }),
            trigger: ['change', 'blur'],
          },
          {
            min: 3,
            max: 15,
            message: this.$t('validation.between.string', {
              attribute: this.$t('validation.attributes.code'),
              min: 3,
              max: 15,
            }),
            trigger: ['change', 'blur'],
          },
        ],
        first_name: [
          {
            required: true,
            message: this.$t('validation.required', {
              attribute: this.$t('validation.attributes.firstName'),
            }),
            trigger: ['change', 'blur'],
          },
          {
            min: 1,
            max: 20,
            message: this.$t('validation.between.string', {
              attribute: this.$t('validation.attributes.firstName'),
              min: 1,
              max: 20,
            }),
            trigger: ['change', 'blur'],
          },
        ],
        last_name: [
          {
            required: true,
            message: this.$t('validation.required', {
              attribute: this.$t('validation.attributes.lastName'),
            }),
            trigger: ['change', 'blur'],
          },
          {
            min: 2,
            max: 60,
            message: this.$t('validation.between.string', {
              attribute: this.$t('validation.attributes.lastName'),
              min: 2,
              max: 60,
            }),
            trigger: ['change', 'blur'],
          },
        ],
        email: [
          {
            type: 'email',
            required: true,
            message: this.$t('validation.required', {
              attribute: this.$t('validation.attributes.email'),
            }),
            trigger: ['change', 'blur'],
          },
          {
            min: 3,
            max: 100,
            message: this.$t('validation.between.string', {
              attribute: this.$t('validation.attributes.email'),
              min: 3,
              max: 100,
            }),
            trigger: ['change', 'blur'],
          },
        ],
        role_ids: [
          {
            required: true,
            message: this.$t('validation.required', {
              attribute: this.$t('validation.attributes.role'),
            }),
            trigger: ['change', 'blur'],
          },
        ],
      },
      permissionProps: {
        children: 'children',
        label: 'name',
        disabled: 'disabled',
      },
      permissions: [],
      menuPermissions: [],
      otherPermissions: [],
    };
  },
  computed: {
    normalizedMenuPermissions() {
      let tmp = [];
      this.currentUser.permissions.role.forEach(permission => {
        tmp.push({
          id: permission.id,
          name: permission.name,
          disabled: true,
        });
      });
      const rolePermissions = {
        id: -1, // Faked ID
        name: 'Inherited from role',
        disabled: true,
        children: this.classifyPermissions(tmp).menu,
      };

      tmp = this.menuPermissions.filter(permission => !this.currentUser.permissions.role.find(p => p.id === permission.id));
      const userPermissions = {
        id: 0, // Faked ID
        name: 'Extra menus',
        children: tmp,
        disabled: tmp.length === 0,
      };

      return [rolePermissions, userPermissions];
    },
    normalizedOtherPermissions() {
      let tmp = [];
      this.currentUser.permissions.role.forEach(permission => {
        tmp.push({
          id: permission.id,
          name: permission.name,
          disabled: true,
        });
      });
      const rolePermissions = {
        id: -1,
        name: 'Inherited from role',
        disabled: true,
        children: this.classifyPermissions(tmp).other,
      };

      tmp = this.otherPermissions.filter(permission => !this.currentUser.permissions.role.find(p => p.id === permission.id));
      const userPermissions = {
        id: 0,
        name: 'Extra permissions',
        children: tmp,
        disabled: tmp.length === 0,
      };

      return [rolePermissions, userPermissions];
    },
    userMenuPermissions() {
      return this.classifyPermissions(this.userPermissions).menu;
    },
    userOtherPermissions() {
      return this.classifyPermissions(this.userPermissions).other;
    },
    userPermissions() {
      return this.currentUser.permissions.role.concat(this.currentUser.permissions.user);
    },
  },
  created() {
    this.resetNewUser();
    this.getList();
    this.getRoles();
    // if (checkPermission(['manage permission'])) {
    //   this.getPermissions();
    // }
  },
  methods: {
    checkPermission,
    async getPermissions() {
      const { data } = await permissionResource.list({});
      const { all, menu, other } = this.classifyPermissions(data);
      this.permissions = all;
      this.menuPermissions = menu;
      this.otherPermissions = other;
    },

    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      try {
        const { data, meta } = await userResource.list(this.query);
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
    async getRoles() {
      const { data } = await roleResource.teachers();
      this.roles = data;
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
    handleRestore(id, name) {

    },
    async handleEditPermissions(id) {
      this.currentUserId = id;
      this.dialogPermissionLoading = true;
      this.dialogPermissionVisible = true;
      const found = this.list.find(user => user.id === id);
      const { data } = await userResource.permissions(id);
      this.currentUser = {
        id: found.id,
        name: found.name,
        permissions: data,
      };
      this.dialogPermissionLoading = false;
      this.$nextTick(() => {
        this.$refs.menuPermissions.setCheckedKeys(this.permissionKeys(this.userMenuPermissions));
        this.$refs.otherPermissions.setCheckedKeys(this.permissionKeys(this.userOtherPermissions));
      });
    },
    createUser() {
      this.$refs['itemForm'].validate((valid) => {
        if (valid) {
          this.itemCreating = true;
          userResource
            .store(this.newItem)
            .then(response => {
              console.log('store done: ', response);
              if (response.error) {
                getNotification(
                  this.$t('notification.action.create'),
                  this.$t('notification.object.user'),
                  this.$t('notification.status.error'),
                  'error',
                  uppercaseFirst(this.$t('notification.reason', {
                    object: this.$t('notification.object.data'),
                    status: this.$t('notification.status.invalid'),
                  }))
                );
              } else {
                getNotification(
                  this.$t('notification.action.create'),
                  this.$t('notification.object.user'),
                  this.$t('notification.status.success'),
                  'success'
                );
                this.resetNewUser();
                this.dialogFormVisible = false;
                this.handleFilter();
              }
            })
            .catch(error => {
              console.log(error);
              getNotification(
                this.$t('notification.action.create'),
                this.$t('notification.object.user'),
                this.$t('notification.status.error')
              );
            })
            .finally(() => {
              this.itemCreating = false;
            });
        } else {
          getNotification(
            this.$t('notification.action.create'),
            this.$t('notification.object.user'),
            this.$t('notification.status.error'),
            'error',
            uppercaseFirst(this.$t('notification.reason', {
              object: this.$t('notification.object.data'),
              status: this.$t('notification.status.invalid'),
            }))
          );
        }
      });
    },
    resetNewUser() {
      this.newItem = {
        username: '',
        email: '',
        code: '',
        first_name: '',
        last_name: '',
        role_ids: '',
      };
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
    permissionKeys(permissions) {
      return permissions.map(permssion => permssion.id);
    },
    classifyPermissions(permissions) {
      const all = []; const menu = []; const other = [];
      permissions.forEach(permission => {
        const permissionName = permission.name;
        all.push(permission);
        if (permissionName.startsWith('view menu')) {
          menu.push(this.normalizeMenuPermission(permission));
        } else {
          other.push(this.normalizePermission(permission));
        }
      });
      return { all, menu, other };
    },

    normalizeMenuPermission(permission) {
      return { id: permission.id, name: this.$options.filters.uppercaseFirst(permission.name.substring(10)), disabled: permission.disabled || false };
    },

    normalizePermission(permission) {
      const disabled = permission.disabled || permission.name === 'manage permission';
      return { id: permission.id, name: this.$options.filters.uppercaseFirst(permission.name), disabled: disabled };
    },
    confirmPermission() {
      const checkedMenu = this.$refs.menuPermissions.getCheckedKeys();
      const checkedOther = this.$refs.otherPermissions.getCheckedKeys();
      const checkedPermissions = checkedMenu.concat(checkedOther);
      this.dialogPermissionLoading = true;

      userResource.updatePermission(this.currentUserId, { permissions: checkedPermissions }).then(response => {
        this.$message({
          message: 'Permissions has been updated successfully',
          type: 'success',
          duration: 5 * 1000,
        });
        this.dialogPermissionLoading = false;
        this.dialogPermissionVisible = false;
      });
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
    includeRole,
  },
};
</script>

<style lang="scss" scoped>
.edit-input {
  padding-right: 100px;
}
.dialog-footer {
  text-align: left;
  padding-top: 0;
  margin-left: 150px;
}
</style>
