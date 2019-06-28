<template>
  <div class="col-md-5 text-center d-flex justify-content-center flex-column" style="border-right: 2px solid rgba(255, 255, 255, 0.23);">
    <div>
      <img src="@/assets/logo.png" class="rounded-circle" alt="KMA">
    </div>
    <div class="mt-3">
      <div>
        <strong>{{ this.mainTitle }}</strong>
      </div>
      <div>
        <small>*********</small>
      </div>
      <div>
        <small>
          <strong>{{ this.subTitle }}</strong>
        </small>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AuthLogoFrame',
  props: {
    mainTitle: {
      type: String,
      required: true,
    },
    subTitle: {
      type: String,
      required: true,
    },
  },
};
</script>

<style scoped>

</style>
