<template>
  <div class="app-container">
    <h6 class="title-partial">Đợt thi: {{ term.name | uppercaseFirst }} - Môn thi: {{ subject.name | uppercaseFirst }}</h6>
    <el-form ref="editTermForm" :model="subjectTerm" :rules="rules" label-width="200px" size="mini">
      <el-form-item label="Số Lượng Đề Thi" prop="original_exam_num">
        <el-input type="number" v-model="subjectTerm.original_exam_num" v-model.number="subjectTerm.original_exam_num" :min="1" :max="10"/>
      </el-form-item>
      <el-form-item label="Danh Sách Cán Bộ Coi Thi" prop="protors">
        <el-select v-model="subjectTerm.protors" placeholder="Chọn cán bộ coi thi" multiple>
          <el-option v-for="item in protors" :label="item.username + ' - ' + item.full_name" :value="item.id" :key="item.id"/>
        </el-select>
      </el-form-item>
      <el-form-item label="Danh Sách Sinh Viên">
        <upload-excel :load-fullfill="loadedStudents"></upload-excel>
      </el-form-item>
        <el-form-item>
        <el-button :loading="loading" type="primary" @click="storeSettingTerm('editTermForm')">Cập Nhật</el-button>
        <el-button @click="resetForm('editTermForm')">Reset</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import SubjectResource from '@/api/subject';
import ManagerResource from '@/api/manager';
import TermResource from '@/api/term';
import UploadExcel from '@/views/excel/UploadExcel';
// import waves from '@/directive/waves'; // Waves directive
// import permission from '@/directive/permission'; // Waves directive
// import checkPermission from '@/utils/permission'; // Permission checking
import { ALL_ROLES } from '@/utils/auth';
// import { includes as includeRoles } from '@/utils/role';

import { getNotification } from '@/utils/notification';

const subjectResource = new SubjectResource();
const termResource = new TermResource();
const managerResource = new ManagerResource();
export default {
  components: {
    UploadExcel,
  },
  data() {
    return {
      term: {},
      subject: {},
      subjectTerm: {
        original_exam_num: '',
        protors: [],
        students: [],
      },
      protors: [],
      rules: {
        original_exam_num: [
          { required: true, message: 'Nhập số đề thi', trigger: ['blur', 'change'] },
          { type: 'number', message: 'Giá trị số đề thi phải là số từ 1 đến 10', trigger: ['blur', 'change'] },
        ],
        protors: [
          { required: true, message: 'Chọn cán bộ coi thi', trigger: ['blur', 'change'] },
          // { type: 'number', message: 'Giá trị số đề thi phải là số từ 1 đến 10', trigger: ['blur', 'change'] },
        ],
      },
      studentAccounts: [],
      autoWidth: true,
      bookType: 'xlsx',
      loading: false,
    };
  },
  methods: {
    termDetail(termId) {
      termResource.get(termId).then(response => {
        this.loading = true;
        const { data, subjects } = response;
        this.term = data;
        this.termSubjects = subjects;
        if (this.termSubjects && this.termSubjects.length > 0) {
          this.termSubjects.forEach((element, index) => {
            element['index'] = index + 1;
          });
        }
        const subjectId = this.$route.params.subjectSlug;
        this.subjectDetail(subjectId);
        this.subjectTermDetail(this.term.uuid, subjectId);
      }).catch(error => {
        console.log(error);
      }).finally(() => {
        this.loading = false;
      });
    },
    subjectDetail(subjectId) {
      subjectResource.get(subjectId).then(response => {
        const { data } = response;
        this.subject = data;
      }).catch(() => {

      }).finally(() => {
      });
    },
    subjectTermDetail(termId, subjectId) {
      termResource.subjectTermDetail(termId, subjectId).then(response => {
        const { data } = response;
        if (data.length > 0) {
          this.subjectTerm.original_exam_num = data[0].original_exam_num;
        }
      }).catch(error => {
        console.log(error);
      });
    },
    loadedProtors(data) {
      this.subjectTerm.protors = data;
    },
    loadedStudents(data) {
      this.subjectTerm.students = data;
    },
    storeSettingTerm(formName) {
      this.loading = true;
      this.$refs[formName].validate((valid) => {
        if (valid) {
          termResource.storeSetting(this.subjectTerm, this.term.uuid, this.subject.slug).then(response => {
            if (response.error) {
              getNotification('Thiết lập', 'thông tin môn thi', 'error');
            } else {
              this.studentAccounts = response;
              getNotification('Thiết lập', 'thông tin môn thi', 'success', 'success');
              this.$router.push({ name: 'TermDetail' });
              this.handleDownload();
            }
          }).catch(error => {
            console.log(error);
          }).finally(() => {
            this.loading = false;
          });
        } else {
          getNotification('Thiết lập', 'thông tin môn thi', 'error', 'Do dữ liêu không hợp lệ');
        }
      });
    },
    getAllProtors() {
      // const subjectExamMakerUuids = this.subjectExamMakers.map(examMaker => examMaker.uuid);
      // this.query.except_users = subjectExamMakerUuids;
      // this.query.role_name = this.allRoles['exams_maker'];
      managerResource.getByRole({ role_name: ALL_ROLES['protor'] }).then(response => {
        const { data } = response;
        this.protors = data;
      }).catch(error => {
        console.log(error);
      }).finally(() => {
      });
    },
    handleDownload() {
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['Mã Số', 'Họ', 'Tên', 'Tài Khoản', 'Mât Khẩu'];
        const filterVal = ['code', 'last_name', 'first_name', 'username', 'plain_pwd'];
        const list = this.studentAccounts;
        const data = this.formatJson(filterVal, list);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'ds_account_' + this.term.code,
          autoWidth: this.autoWidth,
          bookType: this.bookType,
        });
      });
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => {
        return v[j];
      }));
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    },
  },
  created() {
    const termId = this.$route.params.termId;
    this.termDetail(termId);
    this.getAllProtors();
  },
};
</script>

<style scoped>

</style>
