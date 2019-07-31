<template>
  <div>
    <div v-for="(item, index) in detail">
      <question-frame
        :key="index"
        :question="item.question"
        :options="item.options"
        :num="index + 1"
      >
      </question-frame>
    </div>
  </div>
</template>

<script>
import QuestionFrame from '@/views/terms/SubjectDetail/tabs/components/QuestionFrame';
export default {
  components: {
    QuestionFrame,
  },
  data() {
    return {
    };
  },
  props: {
    detail: {
      type: Array,
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
