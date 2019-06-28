<template>
  <auth-form-frame
    :form-title="$t('form.login.title')"
    :footer-title="$t('schoolName')"
    :main-logo-title="$t('schoolName')"
    :sub-logo-title="$t('appName')">
    <template v-slot:formContent>
      <login-form></login-form>
    </template>
  </auth-form-frame>
</template>
<script>
import AuthFormFrame from './components/AuthFormFrame';
import LoginForm from './components/forms/Login';

export default {
  name: 'Login',
  components: {
    AuthFormFrame,
    LoginForm,
  },
  data() {
    return {
    };
  },
  watch: {
  },
  methods: {
  },
};
</script>
