<template>
  <div class="app-container">
    <h6 class="title-partial">Tạo Mới Đợt Thi</h6>
    <el-form ref="createTermForm" :model="term" :rules="rules" label-width="120px" size="mini">
      <el-form-item label="Mã Số" prop="code">
        <el-input v-model="term.code"/>
      </el-form-item>
      <el-form-item label="Tên" prop="name">
        <el-input v-model="term.name"/>
      </el-form-item>
      <el-form-item label="Thời Gian">
        <el-col :span="11" prop="begin">
          <el-form-item prop="begin">
            <el-date-picker value-format="yyyy-MM-dd" type="date" placeholder="Bắt đầu" v-model="term.begin" style="width: 100%;"></el-date-picker>
          </el-form-item>
        </el-col>
        <el-col class="line text-center" :span="2">-</el-col>
        <el-col :span="11">
          <el-form-item prop="end">
            <el-date-picker type="date" value-format="yyyy-MM-dd" placeholder="Kết thúc" v-model="term.end" style="width: 100%;"></el-date-picker>
          </el-form-item>
        </el-col>
      </el-form-item>
      <el-form-item label="Môn Học" prop="subjects">
        <el-select v-model="term.subjects" placeholder="Chọn môn học" multiple>
          <el-option v-for="item in subjects" :label="item.name" :value="item.id" :key="item.id"/>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="createTerm('createTermForm')">Tạo Mới</el-button>
        <el-button @click="resetForm('createTermForm')">Reset</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import SubjectResource from '@/api/subject';
import TermResource from '@/api/term';
// import waves from '@/directive/waves'; // Waves directive
// import permission from '@/directive/permission'; // Waves directive
// import checkPermission from '@/utils/permission'; // Permission checking
// import { ALL_ROLES } from '@/utils/auth';
// import { includes as includeRoles } from '@/utils/role';

import { getNotification } from '@/utils/notification';

const subjectResource = new SubjectResource();
const termResource = new TermResource();

export default {
  name: 'CreateTerm',
  data() {
    return {
      term: {
        subjects: [],
      },
      subjects: [],
      rules: {
        code: [
          { required: true, message: 'Vui lòng nhập mã số đợt thi.', trigger: ['change', 'blur'] },
          { min: 3, max: 15, message: 'Độ dài mã số đợt thi từ 3 đến 15 ký tự.', trigger: ['change', 'blur'] },
        ],
        name: [
          { required: true, message: 'Vui lòng nhập tên đợt thi.', trigger: ['change', 'blur'] },
          { min: 5, max: 200, message: 'Độ dài tên đợt thi từ 5 đến 200 ký tự.', trigger: ['change', 'blur'] },
        ],
        begin: [
          { type: 'string', required: true, message: 'Vui lòng nhập ngày bắt đầu.', trigger: ['change', 'blur'] },
        ],
        end: [
          { type: 'string', required: true, message: 'Vui lòng nhập ngày kết thúc.', trigger: ['change', 'blur'] },
        ],
        subjects: [
          { type: 'array', required: true, message: 'Vui lòng chọn môn học.', trigger: ['change'] },
        ],
      },
    };
  },
  methods: {
    async getSubjectList() {
      this.loading = true;
      const { data } = await subjectResource.list({ limit: 200, page: 1 });
      this.subjects = data;
    },
    createTerm(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          termResource.store(this.term).then(response => {
            if (response.error) {
              getNotification('Tạo mới', 'đợt thi', 'error', 'Do dữ liệu trùng lặp hoặc không hơp lệ');
            } else {
              getNotification('Tạo mới', 'đợt thi', 'success');
              this.$router.push({ name: 'TermsList' });
            }
          }).catch(error => {
            console.log('Error: ', error);
            getNotification('Tạo mới', 'đợt thi', 'error');
          });
        } else {
          getNotification('Tạo mới', 'đợt thi', 'error', 'Do dữ liêu không hợp lệ');
        }
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    },
  },
  created() {
    this.getSubjectList();
  },
};
</script>

<style scoped>

</style>
