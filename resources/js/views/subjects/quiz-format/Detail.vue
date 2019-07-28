<template>
  <div class="app-container">
    <div class="row">
      <div class="col-md-4">
        <h6>Thông Tin Đề Thi - {{ subject.name }}</h6>
        <table class="table table-bordered table-sm">
          <tbody>
          <tr>
            <th scope="row">Thời Gian Làm Bài <small><i>(phút)</i></small></th>
            <td>{{ examFormat.timeout || 'Chưa thiết lập' }}</td>
          </tr>
          <tr>
            <th scope="row">Tổng Số Câu Hỏi</th>
            <td>{{ examFormat.question_num || 'Chưa thiết lập' }}</td>
          </tr>
          </tbody>
        </table>
      </div>
      <div class="col-md-8">
        <h6>Số Lượng Câu Hỏi Từng Phần Nội Dung Môn Học Trong Đề Thi</h6>
        <table class="table table-bordered table-sm">
          <tbody v-if="examFormat.format">
          <tr>
            <th>STT</th>
            <th>Nội Dung Môn Học</th>
            <th>Số Câu Hỏi</th>
          </tr>
          <tr v-for="(questionNum, chapterId, index) in format">
            <td>{{ index + 1}}</td>
            <th scope="row">{{ chapterId | chapterName(allChapters) | uppercaseFirst }}</th>
            <td>{{ questionNum }}</td>
          </tr>
          </tbody>
          <tbody v-else class="text-center">
          <div class="p-3">
            Dữ Liệu Chưa Được Thiết Lập
          </div>
          </tbody>
        </table>
        <el-button type="primary" @click="editExamFormat">Cập Nhật Định Dạng Đề Thi</el-button>
      </div>

      <!--      Edit & Update-->
      <div class="col-12" v-if="activedEditAction">
        <h6>Cập Nhật Định Dạng Đề Thi</h6>
        <div class="border p-4">
          <el-form :model="newExamFormat" :rules="examFormatRules" ref="examFormatForm" :label-position="'right'" label-width="350px" size="mini">
            <div class="row">
              <div class="col-md-6">
                <el-form-item label="Thời Gian Làm Bài" prop="timeout">
                  <el-input type="number" v-model.number="newExamFormat.timeout" min="1" max="300"></el-input>
                </el-form-item>
              </div>
              <div class="col-md-6">
                <el-form-item label="Số Lượng Câu Hỏi">
                  <el-input type="number" v-model="questionCounter" min="1" max="300"></el-input>
                </el-form-item>
              </div>
              <hr class="w-100 mt-0 mb-1">
              <div class="col-md-6" v-for="cht in allChapters" :key="cht.id">
                <el-form-item :label="cht.name + ' (' + 'Max: ' + cht.question_num +')' | uppercaseFirst" prop="format">
                  <el-input type="number" required=true v-model.number="newExamFormat.format[cht.id]" min=1 max=50></el-input>
                </el-form-item>
              </div>
            </div>
            <el-form-item>
              <el-button type="primary" @click="handleProcessExamFormat('examFormatForm')">Cập Nhật</el-button>
              <el-button @click="cancelUpdateExamFormat('examFormatForm')">Hủy</el-button>
              <el-button @click="resetForm('examFormatForm')">Reset</el-button>
            </el-form-item>
          </el-form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import SubjectResource from '@/api/subject';
// import ExamFormatResource from '@/api/examFormat';
import ChapterResource from '@/api/chapter';

const subjectResource = new SubjectResource();
const chapterResource = new ChapterResource();
// const examFormatResource = new ExamFormatResource();

export default {
  filters: {
    chapterName (chapterId, allChapters) {
      const chapterCounter = allChapters.length;
      const tmpId = parseInt(chapterId);
      for (let i = 0; i < chapterCounter; i++) {
        const tmp = allChapters[i];
        if (tmp.id === tmpId) {
          return tmp.name;
        }
      }
    },
  },
  data() {
    return {
      loading: true,
      subject: {},
      activedEditAction: false,
      examFormat: {},
      hasFormat: false,
      allChapters: [],
      newExamFormat: {
        format: {},
        timeout: 0,
      },
      tmpChapter: {},
      examFormatRules: {
        format: [
          { required: true, message: 'Nhập số lượng câu hỏi' },
          // { type: 'number', message: 'Giá trị là một số từ 1 đến 100' },
          // { validator: checkAge, trigger: ['blur', 'change'] },
        ],
        timeout: [
          { required: true, message: 'Nhập thời gian làm bài', trigger: ['blur', 'change'] },
          // { min: 3, max: 20, message: 'Độ dài trường mã môn học từ 3 đên 20 ký tự', trigger: ['blur', 'change'] },
        ],
        // description: [{ required: true, message: 'Password is required', trigger: 'blur' }],
      },
    };
  },
  computed: {
    format: function() {
      const chapterCounter = this.allChapters.length;
      const tmpExamFormat = {};
      for (let i = 0; i < chapterCounter; i++) {
        const tmpChp = this.allChapters[i];
        if (this.examFormat.format[tmpChp.id]) {
          tmpExamFormat[tmpChp.id] = this.examFormat.format[tmpChp.id];
        } else {
          tmpExamFormat[tmpChp.id] = 0;
        }
      }
      return tmpExamFormat;
    },
    questionCounter: function() {
      const tmpFormat = this.newExamFormat.format;
      let questionCounter = 0;
      for (const i in tmpFormat) {
        questionCounter += tmpFormat[i];
      }
      return questionCounter;
    },
  },
  methods: {
    subjectDetail(idKey) {
      this.loading = true;
      subjectResource.get(idKey).then(response => {
        const { data } = response;
        this.subject = data;
        this.getExamFormat(this.subject.slug);
        this.getChapters(this.subject);
      }).catch(() => {

      }).finally(() => {
        this.loading = false;
      });
    },
    getChapters(subject) {
      subjectResource.chapters(subject.slug).then(response => {
        const { data } = response;
        this.allChapters = data;
      }).catch(error => {
        console.log(error);
      });
    },
    getExamFormat(subjectId) {
      subjectResource.getFormatExam(subjectId).then(response => {
        this.examFormat = response;
        if (this.examFormat) {
          this.hasFormat = true;
        } else {
          this.hasFormat = false;
        }
      }).catch(error => {
        console.log(error);
      });
    },
    getChapterName(chapterId) {
      // this.getChapterDetail(chapterId);
    },
    getChapterDetail(chapterId) {
      return chapterResource.get(chapterId).then(response => {
        this.tmpChapter = response.data;
      }).catch(error => {
        console.log(error);
      });
    },
    setFullExamFormat(allChapters, examFormat) {
      const chapterCounter = allChapters.length;
      const tmpExamFormat = {};
      for (let i = 0; i < chapterCounter; i++) {
        const tmpChp = allChapters[i];
        if ((examFormat.format) && !(typeof examFormat.format[tmpChp.id] === 'undefined')) {
          tmpExamFormat[tmpChp.id] = parseInt(examFormat.format[tmpChp.id]);
        } else {
          tmpExamFormat[tmpChp.id] = 0;
        }
      }
      this.newExamFormat.timeout = this.examFormat.timeout;
      this.newExamFormat.format = tmpExamFormat;
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    },
    editExamFormat() {
      this.subjectDetail(this.subject.slug);
      this.setFullExamFormat(this.allChapters, this.examFormat);
      this.activedEditAction = true;
    },
    cancelUpdateExamFormat(formName) {
      // this.editExamFormat();
      this.activedEditAction = false;
    },
    handleProcessExamFormat(formName) {
      if (this.hasFormat) {
        this.updateExamFormat(formName);
      } else {
        this.storeExamFormat(formName);
      }
    },
    updateExamFormat(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          subjectResource.updateExamFormat(this.newExamFormat, this.subject.slug, this.examFormat.id).then(response => {
            if (response.error) {
              this.$message({
                message: 'Cập nhật định dạng đề thi không thành công do dữ liệu trùng lặp hoặc không hợp lệ',
                type: 'error',
                duration: 5 * 1000,
              });
            } else {
              this.$message({
                message: 'Cập nhật định dạng đề thi thành công!',
                type: 'success',
                duration: 5 * 1000,
              });
              this.getExamFormat(this.subject.slug);
            }
          }).catch(error => {
            console.log('Error: ', error);
            this.$message({
              message: 'Có lỗi xảy ra. Vui lòng thử lại!',
              type: 'error',
              duration: 5 * 1000,
            });
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
    storeExamFormat(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          subjectResource.storeExamFormat(this.newExamFormat, this.subject.slug).then(response => {
            console.log(response);
            if (response.error) {
              this.$message({
                message: 'Cập nhật định dạng đề thi không thành công do dữ liệu trùng lặp hoặc không hợp lệ',
                type: 'error',
                duration: 5 * 1000,
              });
            } else {
              this.$message({
                message: 'Cập nhật định dạng đề thi thành công!',
                type: 'success',
                duration: 5 * 1000,
              });
              this.getExamFormat(this.subject.slug);
            }
          }).catch(error => {
            console.log('Error: ', error);
            this.$message({
              message: 'Có lỗi xảy ra. Vui lòng thử lại!',
              type: 'error',
              duration: 5 * 1000,
            });
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
  },
  async created() {
    const subjectName = this.$route.params.subjectSlug;
    this.subjectDetail(subjectName);
  },
};
</script>

<style scoped>

</style>
