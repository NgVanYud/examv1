<template>
  <div>
    <div class="row">
      <div :class="{'col-md-9': (subjectTerm.status === 4)}">
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
      <div v-if="subjectTerm.status === 4" class="col-md-3">
        <div class="mb-3 text-center">
          <b> Đáp Án: </b>
        </div>
        <div>
          <div
            class="d-inline-block mr-4"
            v-for="(item, index) in answer"
            :key="index">
            <span>
              <b>{{ (''+(index + 1)) | oneChar2TwoChars }}. </b> {{ item }}
            </span>
          </div>
        </div>
      </div>
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
    subjectTerm: {
      type: Object,
      required: true,
    },
    answer: {
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
