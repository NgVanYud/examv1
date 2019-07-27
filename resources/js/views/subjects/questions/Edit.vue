<template>
  <div class="app-container">
    <h6 class="title-partial">Cập Nhật Nội Dung Câu Hỏi Môn Học <strong>{{ subject.name }}</strong></h6>
    <div v-loading="loading">
      <el-form ref="editQuestionForm" :model="question" :rules="editQuestionRules" label-width="150px" :label-position="'left'" size="mini">
        <el-form-item prop="chapter_id" label="Nội dung môn học">
          <el-select v-model="tmpQuestion.chapter_id" clearable :disabled=true placeholder="Chọn Nội Dung Môn Học" size="mini">
            <el-option
              v-for="cht in allChapters"
              :key="cht.id"
              :label="cht.name + ' (' + cht.question_num + ')'"
              :value="cht.id">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item prop="content" label="Câu hỏi">
          <ckeditor :value-saved="tmpQuestion.content" :toolbar-id="'questionContentToolbarId'" :edit-area-id="'questionContentEditAreaId'" @changeValue="changeQuestionContent"></ckeditor>
        </el-form-item>

        <el-form-item prop="option1" label="Option #1">
          <ckeditor :value-saved="(tmpQuestion.options)[0]" :toolbar-id="'option1ToolbarId'" :edit-area-id="'option1EditAreaId'" @changeValue="changeQuestionOption(0, ...arguments)"></ckeditor>
        </el-form-item>

        <el-form-item prop="option2" label="Option #2">
          <ckeditor :value-saved="tmpQuestion.options[1]" :toolbar-id="'option2ToolbarId'" :edit-area-id="'option2EditAreaId'" @changeValue="changeQuestionOption(1, ...arguments)"></ckeditor>
        </el-form-item>

        <el-form-item prop="option3" label="Option #3">
          <ckeditor :value-saved="tmpQuestion.options[2]" :toolbar-id="'option3ToolbarId'" :edit-area-id="'option3EditAreaId'" @changeValue="changeQuestionOption(2, ...arguments)"></ckeditor>
        </el-form-item>

        <el-form-item prop="option4" label="Option #4">
          <ckeditor :value-saved="tmpQuestion.options[3]" :toolbar-id="'option4ToolbarId'" :edit-area-id="'option4EditAreaId'" @changeValue="changeQuestionOption(3, ...arguments)"></ckeditor>
        </el-form-item>

        <el-form-item prop="answer" label="Đáp án">
          <el-select v-model="tmpQuestion.answer" clearable placeholder="Chọn Nội Dung Môn Học" size="mini">
            <el-option
              v-for="index in 4"
              :key="'answer' + index"
              :label="'Option #' + index"
              :value="(index - 1)">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item prop="is_actived" label="Kích hoạt">
          <el-switch v-model="tmpQuestion.is_actived"></el-switch>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" @click="updateQuestion('editQuestionForm')">Cập Nhật</el-button>
          <el-button @click="resetForm('editQuestionForm')">Reset</el-button>
        </el-form-item>
      </el-form>

    </div>
  </div>
</template>

<script>
import SubjectResource from '@/api/subject';

import CKEditorItem from '@/components/CKEditor/index';

import { getNotification } from '@/utils/notification';

const subjectResource = new SubjectResource();

export default {
  name: 'QuestionEdit',
  components: {
    ckeditor: CKEditorItem,
  },
  data() {
    return {
      loading: true,
      subject: {},
      allChapters: [],
      tmpQuestion: {
        subject_id: '',
        chapter_id: '',
        content: '',
        options: [],
        answer: '',
        is_actived: 0,
      },
      question: {
      },
      editQuestionRules: {
        chapter_id: [
          { required: true, message: 'Chọn nội dung môn học' },
          // { type: 'number', message: 'Giá trị là một số từ 1 đến 100' },
          // { validator: checkAge, trigger: ['blur', 'change'] },
        ],
        content: [
          { required: true, message: 'Nhập nội dung câu hỏi', trigger: ['blur', 'change'] },
        ],
        // option1: [
        //   { required: true, message: 'Nhập đáp án câu hỏi', trigger: ['blur', 'change'] },
        //   // { type: 'array', message: 'Độ dài trường tên môn học tư 3 đến 100 ký tự', trigger: ['blur', 'change'] },
        // ],
        // option2: [
        //   { required: true, message: 'Nhập đáp án câu hỏi', trigger: ['blur', 'change'] },
        // ],
        // option3: [
        //   { required: true, message: 'Nhập đáp án câu hỏi', trigger: ['blur', 'change'] },
        // ],
        // option4: [
        //   { required: true, message: 'Nhập đáp án câu hỏi', trigger: ['blur', 'change'] },
        //   // { type: 'array', message: 'Độ dài trường tên môn học tư 3 đến 100 ký tự', trigger: ['blur', 'change'] },
        // ],
        answer: [
          { required: true, message: 'Chọn đáp án đúng', trigger: ['blur', 'change'] },
          // { min: 3, max: 100, message: 'Độ dài trường tên môn học tư 3 đến 100 ký tự', trigger: ['blur', 'change'] },
        ],
      },
    };
  },
  methods: {
    subjectDetail(idKey) {
      this.loading = true;
      subjectResource.get(idKey).then(response => {
        const { data } = response;
        this.subject = data;
        this.tmpQuestion.subject_id = this.subject.id;
        this.questionDetail(this.subject.slug);
        this.getChapters(this.subject);
      }).catch(() => {

      }).finally(() => {
        this.loading = false;
      });
    },
    questionDetail(subjectId) {
      const questionId = this.$route.params['questionId'];
      subjectResource.showQuestion(subjectId, questionId).then(response => {
        const { data } = response;
        this.question = data;
        this.setAnswerIndex(this.question.options);
        this.setTmpQuestion(this.question);
      }).catch(error => {
        console.log(error);
      });
    },
    setAnswerIndex(options) {
      const optionCounter = options.length;
      for (let i = 0; i < optionCounter; i++) {
        if (options[i].is_correct) {
          this.question.answer = i;
          break;
        }
      }
    },
    setTmpQuestion(currentQuestion) {
      this.tmpQuestion.content = currentQuestion.content;
      this.tmpQuestion.answer = currentQuestion.answer;
      this.tmpQuestion.chapter_id = currentQuestion.chapter_id;
      this.tmpQuestion.subject_id = currentQuestion.subject_id;
      this.tmpQuestion.options = [];
      this.tmpQuestion.is_actived = currentQuestion.is_actived;
      const allOptions = currentQuestion.options;
      for (let i = 0; i < allOptions.length; i++) {
        (this.tmpQuestion.options)[i] = (allOptions[i]).content;
      }
    },
    getChapters(subject) {
      subjectResource.chapters(subject.slug).then(response => {
        const { data } = response;
        this.allChapters = data;
      }).catch(error => {
        console.log(error);
      });
    },
    resetForm(formName) {
      this.setTmpQuestion(this.question);
    },
    updateQuestion(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.loading = true;
          subjectResource.updateQuestion(this.tmpQuestion, this.subject.slug, this.question.id).then(response => {
            if (response.error) {
              getNotification('Cập nhật', 'câu hỏi', 'error', 'Do dữ liệu trùng lặp hoặc không hơp lệ');
            } else {
              getNotification('Cập nhật', 'câu hỏi', 'success', 'success');
              this.$router.push({ name: 'QuestionsList', params: { subjectSlug: this.subject.slug }});
            }
          }).catch(error => {
            console.log(error);
            getNotification('Cập nhật', 'câu hỏi', 'error');
          }).finally(() => {
            this.loading = false;
          });
        } else {
          getNotification('Cập nhật', 'câu hỏi', 'error', 'Do dữ liêu không hợp lệ');
        }
      });
    },
    changeQuestionContent(value) {
      this.tmpQuestion.content = value;
    },
    changeQuestionOption(index, value) {
      this.tmpQuestion.options[index] = value;
    },
  },
  created() {
    const subjectName = this.$route.params.subjectSlug;
    this.subjectDetail(subjectName);
  },
};
</script>

<style scoped lang="scss">

</style>
