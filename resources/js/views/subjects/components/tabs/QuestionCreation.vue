<template>
  <div>
    <div class="title-partial">
      <h6 class="title-partial">Tạo Mới Câu Hỏi</h6>
    </div>
    <div v-loading="loading">
      <el-form ref="createQuestionForm" :model="newQuestion" :rules="createQuestionRules" label-width="150px" :label-position="'left'" size="mini">
        <el-form-item prop="chapter_id" label="Nội dung môn học">
          <el-select v-model="newQuestion.chapter_id" clearable placeholder="Chọn Nội Dung Môn Học" size="mini">
            <el-option
              v-for="cht in allChapters"
              :key="cht.id"
              :label="cht.name + ' (' + cht.question_num + ')'"
              :value="cht.id">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item prop="content" label="Câu hỏi">
          <ckeditor :value-saved="newQuestion.content" :toolbar-id="'questionContentToolbarId'" :edit-area-id="'questionContentEditAreaId'" @changeValue="changeQuestionContent"></ckeditor>
        </el-form-item>

        <el-form-item prop="option1" label="Option #1">
          <ckeditor :value-saved="newQuestion.options[0]" :toolbar-id="'option1ToolbarId'" :edit-area-id="'option1EditAreaId'" @changeValue="changeQuestionOption(0, ...arguments)"></ckeditor>
        </el-form-item>

        <el-form-item prop="option2" label="Option #2">
          <ckeditor :value-saved="newQuestion.options[1]" :toolbar-id="'option2ToolbarId'" :edit-area-id="'option2EditAreaId'" @changeValue="changeQuestionOption(1, ...arguments)"></ckeditor>
        </el-form-item>

        <el-form-item prop="option3" label="Option #3">
          <ckeditor :value-saved="newQuestion.options[2]" :toolbar-id="'option3ToolbarId'" :edit-area-id="'option3EditAreaId'" @changeValue="changeQuestionOption(2, ...arguments)"></ckeditor>
        </el-form-item>

        <el-form-item prop="option4" label="Option #4">
          <ckeditor :value-saved="newQuestion.options[3]" :toolbar-id="'option4ToolbarId'" :edit-area-id="'option4EditAreaId'" @changeValue="changeQuestionOption(3, ...arguments)"></ckeditor>
        </el-form-item>

        <el-form-item prop="answer" label="Đáp án">
          <el-select v-model="newQuestion.answer" clearable placeholder="Chọn Nội Dung Môn Học" size="mini">
            <el-option
              v-for="index in 4"
              :key="'answer' + index"
              :label="'Option #' + index"
              :value="(index - 1)">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item prop="is_actived" label="Kích hoạt">
          <el-switch v-model="newQuestion.is_actived"></el-switch>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" @click="createQuestion('createQuestionForm')">Tạo Mới</el-button>
          <el-button @click="resetForm('createQuestionForm')">Reset</el-button>
        </el-form-item>
      </el-form>

    </div>
  </div>
</template>

<script>
import SubjectResource from '@/api/subject';

import CKEditorItem from '@/components/CKEditor/index';

const subjectResource = new SubjectResource();

export default {
  name: 'QuestionCreationTab',
  components: {
    ckeditor: CKEditorItem,
  },
  data() {
    return {
      loading: true,
      subject: {},
      allChapters: [],
      newQuestion: {
        subject_id: '',
        chapter_id: '',
        content: '',
        options: [],
        answer: '',
        is_actived: 0,
      },
      createQuestionRules: {
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
        this.newQuestion.subject_id = this.subject.id;
        this.getChapters(this.subject.id);
      }).catch(() => {

      }).finally(() => {
        this.loading = false;
      });
    },
    getChapters(subjectId) {
      subjectResource.chapters({
        limit: 100,
      }, subjectId).then(response => {
        const { data } = response;
        this.allChapters = data;
      }).catch(error => {
        console.log(error);
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    },
    createQuestion(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.loading = true;
          this.newQuestion.subject_id = this.subject.id;
          subjectResource.storeQuestion(this.newQuestion, this.subject.id, this.newQuestion.chapter_id).then(response => {
            if (response.error) {
              this.$message({
                message: 'Tạo mới câu hỏi không thành công do dũ liệu trùng lặp hoặc không hợp lệ',
                type: 'error',
                duration: 5 * 1000,
              });
            } else {
              this.$message({
                message: 'Tạo mới câu hỏi thành công',
                type: 'success',
                duration: 5 * 1000,
              });
              this.getChapters(this.subject.id);
              this.resetForm('createQuestionForm');
              this.resetNewQuestion();
            }
          }).catch(error => {
            console.log(error);
            this.$message({
              message: 'Tạo mới câu hỏi không thành công.',
              type: 'error',
              duration: 5 * 1000,
            });
          }).finally(() => {
            this.loading = false;
          });
        } else {
          this.$message({
            message: 'Dữ liệu không hợp lệ. Vui lòng nhập lại!',
            type: 'error',
            duration: 5 * 1000,
          });
        }
      });
    },
    resetNewQuestion() {
      this.newQuestion = {
        subject_id: this.subject.id,
        chapter_id: '',
        content: '',
        options: [],
        answer: '',
        is_actived: 0,
      };
    },
    changeQuestionContent(value) {
      this.newQuestion.content = value;
    },
    changeQuestionOption(index, value) {
      this.newQuestion.options[index] = value;
    },
  },
  created() {
    const subjectName = this.$route.params.slug;
    this.subjectDetail(subjectName);
  },
};
</script>

<style scoped lang="scss">

</style>
