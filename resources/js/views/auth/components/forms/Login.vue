<template>
  <div>
    <el-form ref="loginForm" :model="loginForm" :rules="loginRules" auto-complete="on" label-position="left">
      <el-form-item prop="username">
                <span class="svg-container">
                  <svg-icon icon-class="user" />
                </span>
        <el-input v-model="loginForm.username"
                  name="username"
                  type="text"
                  auto-complete="on"
                  :placeholder="$t('validation.attributes.username') | uppercaseFirst" />
      </el-form-item>
      <el-form-item prop="password">
                <span class="svg-container">
                  <svg-icon icon-class="password" />
                </span>
        <el-input
          :type="pwdType"
          v-model="loginForm.password"
          name="password"
          auto-complete="on"
          :placeholder="$t('validation.attributes.password') | uppercaseFirst"
          @keyup.enter.native="handleLogin" />
        <span class="show-pwd" @click="showPwd">
                  <svg-icon icon-class="eye" />
                </span>
      </el-form-item>
      <el-form-item>
        <el-button :loading="loading" class="btn-light" style="width:100%;" size="medium" @click.native.prevent="handleLogin">
          {{ $t('button.signIn') }}
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { getNotification } from '@/utils/notification';
import { validUsername } from '@/utils/validate';
import { uppercaseFirst } from '@/filters';

export default {
  name: 'LoginForm',
  data() {
    const validateUsername = (rule, value, callback) => {
      if (!validUsername(value)) {
        callback(this.$t('validation.invalid', {
          attribute: this.$t('validation.attributes.username'),
        }));
      } else {
        callback();
      }
    };
    return {
      loginForm: {
        username: '',
        password: '',
      },
      loginRules: {
        username: [{
          validator: validateUsername,
          trigger: ['change'],
        }, {
          required: true,
          message: this.$t('validation.required', {
            attribute: this.$t('validation.attributes.username'),
          }),
          trigger: ['blur', 'change'],
        }, {
          min: 8,
          message: this.$t('validation.invalid', {
            attribute: this.$t('validation.attributes.username'),
          }),
          trigger: ['blur', 'change'],
        }],
        password: [
          // { required: true, trigger: 'blur', validator: validatePass },
          { required: true,
            message: this.$t('validation.required', {
              attribute: this.$t('validation.attributes.password'),
            }),
            trigger: 'blur',
          },
          {
            min: 7,
            message: this.$t('validation.invalid', {
              attribute: this.$t('validation.attributes.password'),
            }),
            trigger: 'blur',
          },
        ],
      },
      loading: false,
      pwdType: 'password',
      redirect: undefined,
    };
  },
  watch: {
    $route: {
      handler: function(route) {
        this.redirect = route.query && route.query.redirect;
      },
      immediate: true,
    },
  },
  methods: {
    showPwd() {
      if (this.pwdType === 'password') {
        this.pwdType = '';
      } else {
        this.pwdType = 'password';
      }
    },
    handleLogin() {
      this.$refs.loginForm.validate(valid => {
        if (valid) {
          this.loading = true;
          this.$store.dispatch('user/login', this.loginForm).then(() => {
            this.$router.push({ path: this.redirect || '/' });
          }).catch((error) => {
            console.log(error);
            getNotification(
              this.$t('notification.action.login'),
              this.$t('notification.object.system'),
              this.$t('notification.status.error'),
              this.$t('notification.reason', {
                object: this.uppercaseFirst(this.$t('notification.object.info')),
                status: this.$t('notification.status.invalid'),
              }),
            );
          });
        } else {
          getNotification(
            this.$t('notification.action.login'),
            this.$t('notification.object.system'),
            this.$t('notification.status.error'),
            this.$t('notification.reason', {
              object: this.uppercaseFirst(this.$t('notification.object.info')),
              status: this.$t('notification.status.invalid'),
            }),
          );
          return false;
        }
        this.loading = false;
      });
    },
    uppercaseFirst,
  },
};
</script>

<style scoped>

</style>
