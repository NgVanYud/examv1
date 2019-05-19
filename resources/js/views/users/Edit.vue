<template>
  <div class="app-container">
    <el-form ref="form" :model="user" label-width="120px">
      <el-form-item label="Tài Khoản">
        <el-input v-model="user.username"/>
      </el-form-item>
      <el-form-item label="Mã Số">
        <el-input v-model="user.code"/>
      </el-form-item>
      <el-form-item label="Họ">
        <el-input v-model="user.last_name"/>
      </el-form-item>
      <el-form-item label="Tên">
        <el-input v-model="user.first_name"/>
      </el-form-item>
      <el-form-item label="Email">
        <el-input v-model="user.email"/>
      </el-form-item>
      <el-form-item label="Kích Hoạt">
        <el-switch v-model="user.active"></el-switch>
      </el-form-item>
      <el-form-item label="Quyền">
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
      form: {
        // username: this.user.username,
        code: '',
        email: '',
        // last_name: this.user.last_name,
        // first_name: this.user.first_name,
        // roles: '',
        // permission: [],
        // active: this.user.active,
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
      this.$message('submit!');
    },
    onCancel() {
      this.$message({
        message: 'cancel!',
        type: 'warning',
      });
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

