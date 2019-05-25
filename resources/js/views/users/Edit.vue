<template>
  <div class="app-container">
    <el-form ref="editForm" :model="user" :rules="rules" label-width="120px" size="mini">
      <el-form-item label="Tài Khoản" prop="username">
        <el-input v-model="user.username" :disabled="true"/>
      </el-form-item>
      <el-form-item label="Mã Số" prop="code">
        <el-input v-model="user.code"/>
      </el-form-item>
      <el-form-item label="Họ" prop="last_name">
        <el-input v-model="user.last_name"/>
      </el-form-item>
      <el-form-item label="Tên" prop="first_name">
        <el-input v-model="user.first_name"/>
      </el-form-item>
      <el-form-item label="Email" prop="email">
        <el-input v-model="user.email"/>
      </el-form-item>
      <el-form-item label="Kích Hoạt" prop="active">
        <el-switch v-model="user.active"></el-switch>
      </el-form-item>
      <el-form-item label="Quyền" prop="roles">
        <el-select v-model="userRoleIds" placeholder="Chọn Quyền" multiple>
          <el-option v-for="item in roles" :label="item.name" :value="item.id" :key="item.id"/>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit">Cập Nhật</el-button>
        <el-button @click="onCancel">Hủy</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import RoleResource from '@/api/role';
import UserResource from '@/api/user';
import { Message } from 'element-ui';

const userResource = new UserResource();
const roleResource = new RoleResource();

export default {
  name: 'EditUser',
  data() {
    return {
      user: {},
      roles: [],
      userRoleIds: [],
      permissionIds: [],
      rules: {
        code: [
          { required: true, message: 'Vui lòng nhập mã số người dùng.', trigger: ['change', 'blur'] },
          { min: 3, max: 15, message: 'Độ dài mã số người dùng từ 3 đến 15 ký tự.', trigger: ['change', 'blur'] },
        ],
        first_name: [
          { required: true, message: 'Vui lòng nhập tên người dùng.', trigger: ['change', 'blur'] },
          { min: 1, max: 20, message: 'Độ dài tên người dùng từ 1 đến 20 ký tự.', trigger: ['change', 'blur'] },
        ],
        last_name: [
          { required: true, message: 'Vui lòng nhập họ người dùng.', trigger: ['change', 'blur'] },
          { min: 2, max: 60, message: 'Độ dài họ người dùng từ 2 đến 60 ký tự.', trigger: ['change', 'blur'] },
        ],
        email: [
          { type: 'email', required: true, message: 'Vui lòng nhập email người dùng.', trigger: ['change', 'blur'] },
          { min: 3, max: 100, message: 'Độ dài email từ 3 đến 100 ký tự.', trigger: ['change', 'blur'] },
        ],
        roles: [
          { type: 'array', required: true, message: 'Vui lòng nhập email người dùng.', trigger: ['change', 'blur'] },
        ],
      },
    };
  },
  methods: {
    async getRoles() {
      const { data } = await roleResource.list();
      this.roles = data;
    },
    async userDetail(uuid) {
      const { data, roles, permissions } = await userResource.get(uuid);
      this.user = data;
      this.userRoleIds = roles;
      this.permissionIds = permissions;
    },
    onSubmit() {
      this.updateUser();
    },
    async updateUser() {
      this.user.role_ids = this.userRoleIds;
      await userResource.update(this.user.uuid, this.user).then(response => {
        Message({
          message: 'Cập nhật người dùng thành công!',
          type: 'success',
          duration: 5 * 1000,
        });
        this.$router.push({ name: 'UsersList' });
      }).catch(error => {
        console.log('Error: ', error);
        Message({
          message: 'Có lỗi xảy ra. Vui lòng thử lại!',
          type: 'error',
          duration: 5 * 1000,
        });
      });
    },
    onCancel() {
      this.userDetail(this.user.uuid);
    },
  },
  created() {
    const userUuid = this.$route.params.id;
    this.userDetail(userUuid);
    this.getRoles();
  },
};
</script>

<style scoped>
  .line{
    text-align: center;
  }
</style>

