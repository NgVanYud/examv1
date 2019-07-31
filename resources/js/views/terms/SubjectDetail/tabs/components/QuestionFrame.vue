<template>
  <div>
    <div class="question-wrapper">
      <div class="content-wrapper">
        <b>Câu hỏi {{ num }}: </b>
        <p v-html="question.content" class="d-inline"></p>
      </div>
      <div class="options-wrapper">
        <div v-for="(option, index) in options" class="option-item">
          <b>{{ index | index2OptionChar }}. </b>
          <p v-html="option[0].content" class="d-inline"></p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
    };
  },
  props: {
    question: {
      type: Object,
      required: true,
    },
    options: {
      type: Array,
      required: true,
    },
    num: {
      type: [String, Number],
      required: true,
    },
  },
  created() {
  },
  methods: {
  },
};
</script>

<style scoped>

</style>
