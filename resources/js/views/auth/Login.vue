<template>
  <div class="auth-form-container d-flex justify-content-center align-items-center">
    <div class="auth-form-wrapper">
      <div class="container">
        <div class="row">
          <div class="col-md-5 text-center d-flex justify-content-center flex-column" style="border-right: 2px solid rgba(255, 255, 255, 0.23);">
            <div>
              <img src="@/assets/logo.png" class="rounded-circle" alt="KMA">
            </div>
            <div class="mt-3">
              <div>
                <strong>{{ $t('schoolName') }}</strong>
              </div>
              <div>
                <small>*********</small>
              </div>
              <div>
                <small>
                  <strong>{{ $t('appName') }}</strong>
                </small>
              </div>
            </div>
          </div>
          <div class="col-md-7">
            <el-form ref="loginForm" :model="loginForm" :rules="loginRules" auto-complete="on" label-position="left">
              <h3 class="title">{{ $t('form.login.title') }}</h3>
              <lang-select class="set-language" />
              <el-form-item prop="username">
                <span class="svg-container">
                  <svg-icon icon-class="user" />
                </span>
                <el-input v-model="loginForm.username" name="email" type="text" auto-complete="on" :placeholder="$t('validation.attributes.username')" />
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
                  :placeholder="$t('validation.attributes.password')"
                  @keyup.enter.native="handleLogin" />
                <span class="show-pwd" @click="showPwd">
                  <svg-icon icon-class="eye" />
                </span>
              </el-form-item>
              <el-form-item>
                <el-button :loading="loading" class="btn-light" style="width:100%;" @click.native.prevent="handleLogin">
                  {{ $t('button.signIn') }}
                </el-button>
              </el-form-item>
              <div class="login-form-footer text-center">
                <hr>
                <span> {{ $t('schoolName') }}</span>
              </div>
            </el-form>
          </div>
        </div>
      </div>

    </div>

  </div>
</template>

<script>
import LangSelect from '@/components/LangSelect';
import { getNotification } from '@/utils/notification';
// import { validUsername } from '@/utils/validate';

export default {
  name: 'Login',
  components: { LangSelect },
  data() {
    // const validateUsername = (rule, value, callback) => {
    //   if (!validUsername(value)) {
    //     callback(new Error(this.$t('')));
    //   } else {
    //     callback();
    //   }
    // };
    // const validatePass = (rule, value, callback) => {
    //   if (value.length < 4) {
    //     callback(new Error('Password cannot be less than 4 digits'));
    //   } else {
    //     callback();
    //   }
    // };
    return {
      loginForm: {
        username: 'MN000001',
        password: '123@abc',
      },
      loginRules: {
        username: [{
          required: true,
          message: this.$t('validation.required', {
            attribute: this.$t('validation.attributes.username'),
          }),
          trigger: 'blur',
        }, {
          min: 8,
          message: this.$t('validation.invalid', {
            attribute: this.$t('validation.attributes.username'),
          }),
          trigger: 'blur',
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
            this.loading = false;
          }).catch((error) => {
            this.loading = false;
            console.log(error);
            getNotification(
              this.$t('notification.action.login'),
              this.$t('notification.object.system'),
              this.$t('notification.status.error'),
              this.$t('notification.reason', {
                object: this.$t('notification.object.info').charAt(0).toUpperCase() + this.$t('notification.object.info').slice(1),
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
              object: this.$t('notification.object.info').charAt(0).toUpperCase() + this.$t('notification.object.info').slice(1),
              status: this.$t('notification.status.invalid'),
            }),
          );
          return false;
        }
      });
    },
  },
};
</script>
