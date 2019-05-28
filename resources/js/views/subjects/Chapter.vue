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
        <div class="d-inline-block ml-2" v-if="activedActionBtns">
          <el-tooltip class="item" effect="dark" content="Xóa" placement="top">
            <el-button icon="el-icon-delete" class="filter-delete" size="mini" circle @click="handleDeleteMulti"></el-button>
          </el-tooltip>
        </div>
        <div class="ml-auto">
          <el-button size="mini" class="filter-item" type="primary" icon="el-icon-plus" @click="handleCreate">
            {{ $t('table.add') }}
          </el-button>
          <el-button size="mini" v-waves :loading="downloading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
            {{ $t('table.export') }}
          </el-button>
        </div>
      </div>

    </div>

    <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%" size="mini" @selection-change="handleSelectedRows">
      <el-table-column
        v-if="includeRoles(this.userRoles, [allRoles.admin], false)"
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

      <!--      <el-table-column label="Kích Hoạt" align="center" width="100">-->
      <!--        <template slot-scope="scope">-->
      <!--          <el-tag v-if="scope.row.active" type="success">-->
      <!--            <i class="el-icon-success"></i>-->
      <!--          </el-tag>-->
      <!--          <el-tag v-else type="danger">-->
      <!--            <i class="el-icon-error"></i>-->
      <!--          </el-tag>-->
      <!--        </template>-->
      <!--      </el-table-column>-->

      <el-table-column align="center" label="Thao Tác" width="150">
        <template slot-scope="scope">
          <router-link :to = "{ name: 'SubjectEdit', params: { slug: scope.row.slug }}" v-if="includeRoles(userRoles, [allRoles.admin], false)">
            <el-button type="primary" size="mini" icon="el-icon-edit" title="Chỉnh sửa">
            </el-button>
          </router-link>
          <el-button v-if="includeRoles(userRoles, [allRoles.admin], true)" type="danger" size="mini" icon="el-icon-delete" @click="handleDelete(scope.row);"  title="Xóa">
          </el-button>
          <router-link :to = "{ name: 'SubjectEdit', params: { slug: scope.row.slug }}" v-if="includeRoles(userRoles, [allRoles.admin], false)">
            <el-button type="primary" size="mini" icon="el-icon-edit" title="Chỉnh sửa">
            </el-button>
          </router-link>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />

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

    <el-dialog title="Tạo mới môn học" :visible.sync="dialogFormVisible">
      <div class="form-container" v-loading="itemCreating">
        <el-form ref="itemForm" :rules="rules" :model="newItem" label-position="left" label-width="150px" style="max-width: 500px;" size="mini">
          <el-form-item label="Mã số" prop="code">
            <el-input v-model="newItem.code" />
          </el-form-item>
          <el-form-item label="Tên" prop="name">
            <el-input v-model="newItem.name" />
          </el-form-item>
          <el-form-item label="Số tín chỉ" prop="credit">
            <el-input type="number" v-model="newItem.credit" v-model.number="newItem.credit" :min="1" :max="10"/>
          </el-form-item>
          <el-form-item label="Mô tả" prop="description">
            <el-input type="textarea" v-model="newItem.description" />
          </el-form-item>

        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false" size="mini">
            <!--            {{ $t('table.cancel') }}-->
            Hủy
          </el-button>
          <el-button type="primary" @click="createItem()" size="mini">
            <!--            {{ $t('table.confirm') }}-->
            Tạo mới
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import SubjectResource from '@/api/subject';
import waves from '@/directive/waves'; // Waves directive
import permission from '@/directive/permission'; // Waves directive
import checkPermission from '@/utils/permission'; // Permission checking
import { ALL_ROLES } from '@/utils/auth';
import { includes as includeRoles } from '@/utils/role';

const subjectResource = new SubjectResource();

export default {
  name: 'ChaptersList',
  components: { Pagination },
  directives: { waves, permission },
  // filters: {
  //   roles(roles) {
  //     const rolesArr = [];
  //     for (let i = 0; i < roles.length; i++) {
  //       rolesArr.push(roles[i].name);
  //     }
  //     return rolesArr.join(', ');
  //   },
  //   statusFilter(status) {
  //     const statusMap = {
  //       published: 'success',
  //       draft: 'info',
  //       deleted: 'danger',
  //     };
  //     return statusMap[status];
  //   },
  // },
  data() {
    return {
      list: null,
      total: 0,
      loading: true,
      downloading: false,
      itemCreating: false,
      activedActionBtns: false,
      selectedItems: [],
      roles: '',
      allRoles: ALL_ROLES,
      query: {
        page: 1,
        limit: 10,
        keyword: '',
      },
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
        code: [
          { required: true, message: 'Nhập mã môn học', trigger: ['blur', 'change'] },
          { min: 5, max: 10, message: 'Độ dài trường mã môn học từ 5 đên 10 ký tự', trigger: ['blur', 'change'] },
        ],
        name: [
          { required: true, message: 'Nhập tên môn học', trigger: ['blur', 'change'] },
          { min: 5, max: 150, message: 'Độ dài trường tên môn học tư 5 đến 150 ký tự', trigger: ['blur', 'change'] },
        ],
        credit: [
          { required: true, message: 'Nhập số tín chỉ', trigger: ['blur', 'change'] },
          { type: 'number', message: 'Giá trị số tín chỉ phải là số từ 1 đến 10', trigger: ['blur', 'change'] },
        ],
        // description: [{ required: true, message: 'Password is required', trigger: 'blur' }],
      },
      userRoles: '',

    };
  },
  computed: {

  },
  methods: {
    checkPermission,
    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, meta } = await subjectResource.list(this.query);
      this.list = data;
      if (this.list.length > 0) {
        this.list.forEach((element, index) => {
          element['index'] = (page - 1) * limit + index + 1;
        });
      }
      this.total = meta.total;
      this.loading = false;
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    handleCreate() {
      this.resetnewItem();
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['itemForm'].clearValidate();
      });
    },
    handleDelete(item) {
      this.$confirm('Xóa môn học ' + item.name + '. Tiếp tục?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Hủy',
        type: 'warning',
      }).then(() => {
        subjectResource.destroy(item.slug).then(response => {
          console.log('xoa', response);
          if (response.error) {
            this.$message({
              type: 'error',
              message: 'Xóa môn học không thành công',
            });
          } else {
            this.$message({
              type: 'success',
              message: 'Xóa môn học thành công',
            });
          }
          this.handleFilter();
        }).catch(error => {
          console.log(error);
          this.$message({
            type: 'error',
            message: 'Xóa môn học không thành công',
          });
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'Hủy xóa môn học',
        });
      });
    },
    handleDeleteMulti() {
      this.$confirm('Xóa môn học. Tiếp tục?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Hủy',
        type: 'warning',
      }).then(() => {
        const deletedItemIds = {
          items: this.selectedItems.map(item => item.slug),
        };

        subjectResource.destroyMulti(deletedItemIds).then(response => {
          console.log('xoa', response);
          this.$message({
            type: 'success',
            message: 'Xóa môn học thành công',
          });
          this.handleFilter();
        }).catch(error => {
          console.log(error);
          this.$message({
            type: 'error',
            message: 'Xóa môn học không thành công',
          });
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'Hủy xóa môn học',
        });
      });
    },
    handleRestore(id, name) {

    },
    createItem() {
      this.$refs['itemForm'].validate((valid) => {
        if (valid) {
          this.itemCreating = true;
          subjectResource
            .store(this.newItem)
            .then(response => {
              if (response.error) {
                this.$message({
                  message: 'Tạo mới môn học không thành công do dũ liệu trùng lặp hoặc không hợp lệ',
                  type: 'error',
                  duration: 5 * 1000,
                });
              } else {
                this.$message({
                  message: 'Tạo mới môn học thành công',
                  type: 'success',
                  duration: 5 * 1000,
                });
                this.resetnewItem();
                this.dialogFormVisible = false;
                this.handleFilter();
              }
            })
            .catch(error => {
              console.log(error);
              this.$message({
                message: 'Tạo mới môn học không thành công',
                type: 'error',
                duration: 5 * 1000,
              });
            })
            .finally(() => {
              this.itemCreating = false;
            });
        } else {
          console.log('error submit!!');
          this.$message({
            message: 'Dữ liệu không hợp lệ. Vui lòng nhập lại!',
            type: 'error',
            duration: 5 * 1000,
          });
          return false;
        }
      });
    },
    resetnewItem() {
      this.newItem = {
        name: '',
        code: '',
        credit: '',
        description: '',
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
    handleActive(item) {
      subjectResource.active(item.uuid).then(response => {
        this.$message({
          type: 'success',
          message: 'Kích hoạt tài khoản người dùng thành công',
        });
        this.refreshData();
      }).catch(error => {
        console.log(error);
        this.$message({
          type: 'error',
          message: 'Kích hoạt tài khoản người dùng không thành công',
        });
      });
    },
    handleDeactive(item) {
      subjectResource.deactive(item.uuid).then(response => {
        console.log('active', response);
        this.$message({
          type: 'error',
          message: 'Khóa tài khoản người dùng thành công',
        });
        this.refreshData();
      }).catch(error => {
        console.log(error);
        this.$message({
          type: 'success',
          message: 'Khóa tài khoản người dùng không thành công',
        });
      });
    },
    refreshData() {
      this.getList();
    },
    getUserRoles() {
      this.userRoles = this.$store.getters.roles;
    },
    handleSelectedRows(items) {
      this.selectedItems = items;
      if (this.selectedItems.length > 0) {
        this.activedActionBtns = true;
      } else {
        this.activedActionBtns = false;
      }
    },
    includeRoles,
  },
  created() {
    console.log('creating...');
    this.getList();
    this.getUserRoles();
  },
};
</script>

<style lang="scss" scoped>
  .edit-input {
    padding-right: 100px;
  }
  .cancel-btn {
    position: absolute;
    right: 15px;
    top: 10px;
  }
  .dialog-footer {
    text-align: left;
    padding-top: 0;
    margin-left: 150px;
  }
  .app-container {
    flex: 1;
    justify-content: space-between;
    font-size: 14px;
    padding-right: 8px;
    .block {
      float: left;
      min-width: 250px;
    }
    .clear-left {
      clear: left;
    }
  }
</style>
