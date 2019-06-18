<template>
  <div class="login-container">
    <login-form @login="login"></login-form>
  </div>
</template>

<script>
import LoginForm from './components/LoginForm';
import NProgress from 'nprogress'; // progress bar

export default {
  name: 'StudentLogin',
  components: {
    LoginForm,
  },
  data() {
    return {
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
    login(info) {
      NProgress.start();
      this.$store.dispatch('user/login', info).then((response) => {
        console.log('done: ', response);
        NProgress.start();
        this.$router.push({ path: this.redirect || '/' });
        NProgress.done();
      }).catch((error) => {
        console.log(error);
      });
    },
  },
};
</script>

