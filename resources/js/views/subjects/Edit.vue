<template>
  <div class="app-container">
    <el-tabs v-model="activeName">
      <el-tab-pane label="Thông Tin" name="info">
<!--        <el-form ref="itemForm" :model="item" :rules="rules" label-width="120px" size="mini">-->
<!--          <el-form-item label="Mã Số" prop="code">-->
<!--            <el-input v-model="item.code"/>-->
<!--          </el-form-item>-->
<!--          <el-form-item label="Tên" prop="name">-->
<!--            <el-input v-model="item.name"/>-->
<!--          </el-form-item>-->
<!--          <el-form-item label="Số tín chỉ" prop="credit">-->
<!--            <el-input type="number" v-model="item.credit" v-model.number="item.credit" :min="1" :max="10"/>-->
<!--          </el-form-item>-->
<!--          <el-form-item label="Mô Tả" prop="description">-->
<!--            <el-input type="textarea" v-model="item.description" />-->
<!--          </el-form-item>-->
<!--          <el-form-item>-->
<!--            <el-button type="primary" @click="onSubmit">Cập Nhật</el-button>-->
<!--            <el-button @click="onCancel">Hủy</el-button>-->
<!--          </el-form-item>-->
<!--        </el-form>-->
      </el-tab-pane>

      <el-tab-pane label="Giáo viên ra đề" name="teacher">
        <div class="mb-4">
          <div>
            <h5>Danh sách giáo viên ra đề môn học <b>{{ this.item.name }}</b></h5>
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

        <div v-show="activedAddAction">
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

          <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getAllTeachers" />
        </div>

      </el-tab-pane>
      <el-tab-pane label="Chỉnh Sửa" name="edit">
        <el-form ref="itemForm" :model="item" :rules="rules" label-width="120px" size="mini">
          <el-form-item label="Mã Số" prop="code">
            <el-input v-model="item.code"/>
          </el-form-item>
          <el-form-item label="Tên" prop="name">
            <el-input v-model="item.name"/>
          </el-form-item>
          <el-form-item label="Số tín chỉ" prop="credit">
            <el-input type="number" v-model="item.credit" v-model.number="item.credit" :min="1" :max="10"/>
          </el-form-item>
          <el-form-item label="Mô Tả" prop="description">
            <el-input type="textarea" v-model="item.description" />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onSubmit">Cập Nhật</el-button>
            <el-button @click="onCancel">Hủy</el-button>
          </el-form-item>
        </el-form>
      </el-tab-pane>
      <el-tab-pane label="Task" name="fourth">Task</el-tab-pane>
    </el-tabs>

  </div>
</template>

<script>
import SubjectResource from '@/api/subject';
import UserResource from '@/api/user';
import RoleResource from '@/api/role';
// import Resource from '@/api/resource';

import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination

import { Message } from 'element-ui';
import { ALL_ROLES } from '@/utils/auth';
import { include as includeRole } from '@/utils/role';

const subjectResource = new SubjectResource();
const userResource = new UserResource();
const roleResource = new RoleResource();
// const permissionResource = new Resource('permissions');

export default {
  name: 'EditUser',
  components: {
    Pagination,
  },
  props: {
    // activeName: {
    //   type: String,
    //   default: 'info',
    // },
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
      // Config
      activeName: 'info',
      // Teacher
      query: {
        page: 1,
        limit: 10,
        keyword: '',
        except_users: '',
        role_name: '',
      },
      subjectExamMakers: [],
      allExamMakers: null,
      total: 0,
      loading: true,
      downloading: false,
      itemCreating: false,
      roles: '',
      allRoles: ALL_ROLES,
      activedAddAction: false,

      // Edit
      item: {},
      rules: {
        code: [
          { required: true, message: 'Nhập mã môn học', trigger: ['blur', 'change'] },
          { min: 3, max: 20, message: 'Độ dài trường mã môn học từ 3 đên 20 ký tự', trigger: ['blur', 'change'] },
        ],
        name: [
          { required: true, message: 'Nhập tên môn học', trigger: ['blur', 'change'] },
          { min: 3, max: 100, message: 'Độ dài trường tên môn học tư 3 đến 100 ký tự', trigger: ['blur', 'change'] },
        ],
        credit: [
          { required: true, message: 'Nhập số tín chỉ', trigger: ['blur', 'change'] },
          { type: 'number', message: 'Giá trị số tín chỉ phải là số từ 1 đến 10', trigger: ['blur', 'change'] },
        ],
        // description: [{ required: true, message: 'Password is required', trigger: 'blur' }],
      },
    };
  },
  methods: {
    itemDetail(idKey) {
      // this.loading = true;
      subjectResource.get(idKey).then(response => {
        const { data } = response;
        this.item = data;
        this.getSubjectExamMakers();
      }).catch(() => {

      }).finally(() => {
        // this.loading = false;
      });
    },
    onSubmit() {
      this.updateItem();
    },
    updateItem() {
      this.$refs['itemForm'].validate((valid) => {
        if (valid) {
          subjectResource.update(this.item.slug, this.item).then(response => {
            if (response.error) {
              this.$message({
                message: 'Cập nhật môn học không thành công do dũ liệu trùng lặp hoặc không hợp lệ',
                type: 'error',
                duration: 5 * 1000,
              });
            } else {
              Message({
                message: 'Cập nhật môn học thành công!',
                type: 'success',
                duration: 5 * 1000,
              });
              this.$router.push({ name: 'SubjectsList' });
            }
          }).catch(error => {
            console.log('Error: ', error);
            Message({
              message: 'Có lỗi xảy ra. Vui lòng thử lại!',
              type: 'error',
              duration: 5 * 1000,
            });
          });
        } else {
          Message({
            message: 'Dữ liệu không hợp lệ. Vui lòng nhập lại!',
            type: 'error',
            duration: 5 * 1000,
          });
        }
      });
    },
    onCancel() {
      this.itemDetail(this.item.slug);
    },
    // handleClick(tab, event) {
    //   console.log(tab, event);
    // },

    // teacher
    getAllTeachers() {
      const { limit, page } = this.query;
      // this.loading = true;
      userResource.teachers(this.query).then(response => {
        const { data, meta } = response;
        this.allExamMakers = data;
        this.allExamMakers.forEach((element, index) => {
          element['index'] = (page - 1) * limit + index + 1;
        });
        this.total = meta.total;
      }).catch(error => {
        console.log(error);
      }).finally(() => {
        this.loading = false;
      });
    },
    getRoles() {
      roleResource.teachers().then(response => {
        const { data } = response;
        this.roles = data;
      });
    },
    getAllExamMakers() {
      // this.loading = true;
      const subjectExamMakerUuids = this.subjectExamMakers.map(examMaker => examMaker.uuid);
      this.query.except_users = subjectExamMakerUuids;
      this.query.role_name = this.allRoles['exams_maker'];
      userResource.getByRoleName(this.query).then(response => {
        console.log('lay xong tat ca gvrd', response);
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
    getSubjectExamMakers() {
      this.loading = true;
      console.log('item', this.item);
      subjectResource.getExamMakers(this.item.id).then(response => {
        // console.log('lay xong giao vien ra de: ', response);
        const { data } = response;
        this.subjectExamMakers = data;
        this.subjectExamMakers.forEach((element, index) => {
          element['index'] = index + 1;
        });
      }).catch(error => {
        console.log(error);
      }).finally(() => {
        this.loading = false;
      });
    },
    handleFilter() {
      this.query.page = 1;
      this.refreshData();
    },
    refreshData() {
      // this.itemDetail(this.item.id);
      this.getSubjectExamMakers();
      this.getAllExamMakers();
    },
    addExamMaker() {
      this.activedAddAction = true;
      this.getAllExamMakers();
    },
    handleAddExamUser(user) {
      subjectResource.storeExamMaker(this.item.id, user.uuid).then(response => {
        this.loading = true;
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
    handleDeleteExamMaker(user) {
      this.$confirm('Xóa giáo viên ra đề' + user.first_name + '. Tiếp tục?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Hủy',
        type: 'warning',
      }).then(() => {
        subjectResource.removeExamMaker(this.item.id, user.uuid).then(response => {
          this.loading = true;
          this.refreshData();
          // this.getSubjectExamMakers();
          // this.getAllExamMakers();
          // this.handleFilter();
          console.log('xoa xong');
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
    includeRole,
  },
  created() {
    const itemId = this.$route.params.slug;
    this.itemDetail(itemId);
    // this.getSubjectExamMakers();
    this.getAllExamMakers();
  },
};
</script>

<style scoped>

</style>

