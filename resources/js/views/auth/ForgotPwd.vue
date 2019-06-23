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
            <el-form ref="resetPwdForm" :model="resetPwdForm" :rules="resetPwdRules" auto-complete="on" label-position="left">
              <h3 class="title">{{ $t('form.forgotPwd.title') }}</h3>
              <lang-select class="set-language" />
              <el-form-item prop="email">
                <span class="svg-container">
                  <svg-icon icon-class="email" />
                </span>
                <el-input v-model="resetPwdForm.email" name="email" type="email" auto-complete="on" :placeholder="$t('validation.attributes.email')" />
              </el-form-item>
              <el-button :loading="loading" class="btn-light mb-2 w-100" @click.native.prevent="handleResetPwd">
                {{ $t('button.submit') }}
              </el-button>
              <el-button class="ml-0 w-100" type="info" @click.native.prevent="handleCancelResetPwd">
                {{ $t('button.cancel') }}
              </el-button>
              <div class="auth-form-footer text-center">
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
import { validEmail } from '@/utils/validate';
import { sendResetLinkEmail } from '@/api/auth';

export default {
  name: 'ForgotPwd',
  components: { LangSelect },
  data() {
    const validateEmail = (rule, value, callback) => {
      if (!validEmail(value)) {
        callback(new Error(this.$t('validation.invalid', { attribute: this.$t('validation.attributes.email') })));
      } else {
        callback();
      }
    };
    return {
      resetPwdForm: {
        email: '',
      },
      resetPwdRules: {
        email: [{
          required: true,
          trigger: ['blur', 'change'],
          validator: validateEmail,
        }],
      },
      loading: false,
    };
  },
  methods: {
    handleCancelResetPwd() {
      this.$router.push({ name: 'Login' });
    },
    handleResetPwd() {
      this.$refs.resetPwdForm.validate(valid => {
        if (valid) {
          this.loading = true;
          sendResetLinkEmail(this.resetPwdForm).then(response => {
            if (response.error) {
              throw new Error();
            } else {
              console.log('response: ', response);
              alert(response);
            }
          }).catch(error => {
            console.log('hihi', error);
            getNotification(
              this.$t('notification.action.reset'),
              this.$t('notification.object.password'),
              this.$t('notification.status.error')
            );
          }).finally(() => {
            this.loading = false;
          });
          // this.$store.dispatch('user/login', this.loginForm).then(() => {
          //   this.$router.push({ path: this.redirect || '/' });
          //   this.loading = false;
          // }).catch((error) => {
          //   this.loading = false;
          //   console.log(error);
          //   getNotification(
          //     this.$t('notification.action.login'),
          //     this.$t('notification.object.system'),
          //     this.$t('notification.status.error'),
          //     this.$t('notification.reason', {
          //       object: this.$t('notification.object.info').charAt(0).toUpperCase() + this.$t('notification.object.info').slice(1),
          //       status: this.$t('notification.status.invalid'),
          //     }),
          //   );
          // });
        } else {
          getNotification(
            this.$t('notification.action.reset'),
            this.$t('notification.object.password'),
            this.$t('notification.status.error'),
            this.$t('notification.reason', {
              object: this.$t('validation.attributes.email').charAt(0).toUpperCase() + this.$t('validation.attributes.email').slice(1),
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
