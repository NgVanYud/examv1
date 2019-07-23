<template>
  <div class="app-container">
    <el-form ref="itemForm" :model="user" :rules="rules" label-width="120px" size="mini">
      <el-form-item label="Tài Khoản" prop="username">
        <el-input v-model="user.username"/>
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
import UserResource from '@/api/manager';
import { getNotification } from '@/utils/notification';
import { uppercaseFirst } from '@/filters';

const userResource = new UserResource();
const roleResource = new RoleResource();

export default {
  data() {
    return {
      user: {},
      roles: [],
      userRoleIds: [],
      permissionIds: [],
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
      console.log('thong tin user: ', data);
      this.user = data;
      this.userRoleIds = roles;
      this.permissionIds = permissions;
    },
    onSubmit() {
      this.updateUser();
    },
    async updateUser() {
      this.$refs['itemForm'].validate((valid) => {
        if (valid) {
          this.user.role_ids = this.userRoleIds;
          userResource.update(this.user.uuid, this.user).then(response => {
            if (response.error) {
              throw new Error('error');
            } else {
              getNotification(
                this.$t('notification.action.update'),
                this.$t('notification.object.user'),
                this.$t('notification.status.success'),
                'success',
              );
              this.$router.push({ name: 'ManagersList' });
            }
          }).catch(error => {
            console.log('Error: ', error);
            getNotification(
              this.$t('notification.action.update'),
              this.$t('notification.object.user'),
              this.$t('notification.status.error'),
              'error',
              uppercaseFirst(this.$t('notification.reason', {
                object: this.$t('notification.object.data'),
                status: this.$t('notification.status.invalid'),
              }))
            );
          });
        } else {
          getNotification(
            this.$t('notification.action.update'),
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

</style>

