<template>
  <el-form ref="resetPwdForm" :model="resetPwdForm" :rules="resetPwdRules" auto-complete="on" label-position="left">
    <el-form-item prop="email">
                <span class="svg-container">
                  <svg-icon icon-class="email" />
                </span>
      <el-input v-model="resetPwdForm.email"
                name="email"
                type="email"
                auto-complete="on"
                :placeholder="$t('validation.attributes.email') | uppercaseFirst" />
    </el-form-item>
    <el-button :loading="loading" class="btn-light mb-2 w-100" @click.native.prevent="handleResetPwd">
      {{ $t('button.submit') }}
    </el-button>
    <el-button class="ml-0 w-100" type="info" @click.native.prevent="handleCancelResetPwd">
      {{ $t('button.cancel') }}
    </el-button>
  </el-form>
</template>

<script>
import { validEmail } from '@/utils/validate';
import { getNotification } from '@/utils/notification';
import { sendResetLinkEmail } from '@/api/auth';
import { uppercaseFirst } from '@/filters';

export default {
  name: 'ResetPasswordForm',
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
        }, {
          min: 7,
          message: this.$t('validation.invalid', {
            attribute: this.$t('validation.attributes.email'),
          }),
          trigger: ['blur', 'change'],
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
              throw response;
            } else {
              getNotification(
                this.$t('notification.action.reset'),
                this.$t('notification.object.password'),
                this.$t('notification.status.success'),
                'success',
                this.$t('notification.action.verify') + ' ' + this.$t('notification.object.email')
              );
            }
          }).catch(error => {
            if (error.code === 422) {
              getNotification(
                this.$t('notification.action.reset'),
                this.$t('notification.object.password'),
                this.$t('notification.status.error'),
                'error',
                this.$t('notification.reason', {
                  object: this.uppercaseFirst(this.$t('notification.object.email')),
                  status: this.$t('notification.status.invalid'),
                })
              );
            } else {
              getNotification(
                this.$t('notification.action.reset'),
                this.$t('notification.object.password'),
                this.$t('notification.status.error')
              );
            }
          }).finally(() => {
            this.loading = false;
          });
        } else {
          getNotification(
            this.$t('notification.action.reset'),
            this.$t('notification.object.password'),
            this.$t('notification.status.error'),
            this.$t('notification.reason', {
              object: this.uppercaseFirst(this.$t('validation.attributes.email')),
              status: this.$t('notification.status.invalid'),
            }),
          );
          return false;
        }
      });
    },
    uppercaseFirst,
  },
};
</script>

<style scoped>

</style>
