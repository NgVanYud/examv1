<template>
  <div class="login-form-footer text-center">
    <hr>
    <span> {{ footerTitle }}</span>
  </div>
</template>

<script>
export default {
  name: 'FormFooter',
  props: {
    footerTitle: {
      type: String,
      required: true,
    },
  },
};
</script>

<style scoped>

</style>
