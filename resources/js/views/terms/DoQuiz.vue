<template>
  <div>
    <div class="d-flex justify-content-around p-3 pr-5 w-100 text-light" style="position: fixed; background-color: #1f2d3d; z-index: 2 !important;">
      <div>
        <b><i class="el-icon-tickets"></i> Môn thi: </b> {{ quiz.subject.name | uppercaseFirst }} <i>phút</i>
      </div>
      <div>
        <b><i class="el-icon-timer"></i> Thời gian làm bài: </b> {{ quiz.timeout }} <i>phút</i>
      </div>
      <div>
        <b><i class="el-icon-question"></i> Số lượng câu hỏi</b>: {{ quiz.question_num }} <i>câu</i>
      </div>
      <div>
        <vue-countdown :time="quiz.timeout*60*1000" @end="finishQuiz">
          <template slot-scope="props">
            <b><i class="el-icon-time"> </i> Thời gian còn lại: </b>
            <b style="color: #ff0000;"> {{ props.minutes }} phút, {{ props.seconds }} giây</b>
          </template>
        </vue-countdown>
      </div>
    </div>
    <div class="app-container">
      <div class="quiz-wrapper">
        <div class="row">
          <div class="col-md-9">
            <div class="quiz-content">
              <div
                v-for="(item, quesIndex) in questions"
                :key="quesIndex"
                :id="'question' + quesIndex"
              >
                <div class="question-wrapper">
                  <div class="content-wrapper">
                    <b>Câu hỏi {{ quesIndex + 1 }}: </b>
                    <p v-html="item.question.content" class="d-inline"></p>
                  </div>
                  <div class="options-wrapper">
                    <div v-for="(option, OptIndex) in item.options" :key="OptIndex" class="option-item">
                      <el-radio v-model="results[quesIndex]" :label="OptIndex | index2OptionChar">
                        <b>{{ OptIndex | index2OptionChar }}. </b>
                        <p v-html="option[0].content" class="d-inline"></p>
                      </el-radio>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div>
              <h5 class="text-center">Kết Quả Bài Làm</h5>
              <div>
                <div class="d-inline-block mx-2" v-for="(item, index) in results" :key="index">
                  <a :href="'#question' + index">
                    <span>
                    <b>{{ index + 1 }}. <i class="text-danger">{{ item ? item : '*' }}</i></b>
                  </span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import QuizResource from '@/api/quiz';
// import QuizFrame from '@/views/terms/SubjectDetail/tabs/components/QuizFrame';
import VueCountdown from '@chenfengyuan/vue-countdown';
// import Cookies from 'js-cookie';

const quizResource = new QuizResource();

export default {
  components: {
    VueCountdown,
  },
  filters: {
    json2Arr: function (value) {
      return JSON.parse(value);
    },
  },
  data() {
    return {
      quiz: {},
      results: [],
      loading: false,
    };
  },
  computed: {
    questions: function() {
      return JSON.parse(this.quiz.detail);
    },
  },
  watch: {
    results: function(newValue, oldValue) {

    },
  },
  created() {
    this.getQuiz();
  },
  methods: {
    getQuiz() {
      const subjectTermId = this.$route.params.subjectTermId;
      this.loading = true;
      quizResource.detail(subjectTermId).then(response => {
        const { data } = response;
        console.log('quiz: ', data);
        this.quiz = data;
      }).catch(error => {
        console.log(error);
      }).finally(() => {
        this.loading = false;
      });
    },
    finishQuiz() {
      console.log('finish');
    },
  },
};
</script>

<style scoped lang="scss">
  .quiz-wrapper {
    margin-top: 55px;
    .quiz-content {
      max-height: 90vh;
      overflow: scroll;
    }
  }
</style>
