<template>
  <auth-form-frame
    :form-title="$t('form.forgotPwd.title')"
    :footer-title="$t('schoolName')"
    :main-logo-title="$t('schoolName')"
    :sub-logo-title="$t('appName')">
    <template v-slot:formContent>
      <reset-password-form></reset-password-form>
    </template>
  </auth-form-frame>
</template>

<script>
import AuthFormFrame from './components/AuthFormFrame';
import ResetPasswordForm from './components/forms/ResetPassword';

export default {
  name: 'ForgotPwd',
  components: {
    AuthFormFrame,
    ResetPasswordForm,
  },
  data() {
    return {

    };
  },
  methods: {
  },
};
</script>
