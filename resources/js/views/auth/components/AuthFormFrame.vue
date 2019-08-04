<template>
  <div class="auth-form-container d-flex justify-content-center align-items-center">
    <div class="auth-form-wrapper">
      <div class="container">
        <div class="row">
          <logo-frame :main-title="this.mainLogoTitle" :sub-title="this.subLogoTitle"></logo-frame>
          <div class="col-md-7">
            <h3 class="title">{{ formTitle }}</h3>
<!--            <lang-select class="set-language" />-->
            <slot name="formContent"></slot>
            <form-footer :footer-title="this.footerTitle"></form-footer>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import LogoFrame from './LogoFrame';
import FormFooter from './FormFooter';
// import LangSelect from '@/components/LangSelect';

export default {
  name: 'AuthFormFrame',
  components: {
    LogoFrame,
    // LangSelect,
    FormFooter,
  },
  props: {
    formTitle: {
      type: String,
      required: true,
    },
    footerTitle: {
      type: String,
      required: true,
    },
    mainLogoTitle: {
      type: String,
      required: true,
    },
    subLogoTitle: {
      type: String,
      required: true,
    },
  },
};
</script>

<style scoped>

</style>
