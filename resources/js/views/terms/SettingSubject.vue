<template>
  <div class="app-container">
    <h6 class="title-partial">Thiết Lập Thông Tin Môn Thi</h6>
    <el-form ref="editTermForm" :model="subjectTerm" :rules="rules" label-width="120px" size="mini">
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
        <el-button type="primary" @click="updateTerm('editTermForm')">Cập Nhật</el-button>
        <el-button @click="resetForm('editTermForm')">Reset</el-button>
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

// import { getNotification } from '@/utils/notification';

const subjectResource = new SubjectResource();
const termResource = new TermResource();
export default {
  name: 'SettingSubject',
  data() {
    return {
      term: {},
      subject: {},
      subjectTerm: {},
    }
  },
  methods: {
    termDetail(termId) {
      termResource.get(termId).then(response => {
        this.loading = true;
        const { data, subjects } = response;
        this.term = data;
        this.termSubjects = subjects;
        if (this.termSubjects.length > 0) {
          this.termSubjects.forEach((element, index) => {
            element['index'] = index + 1;
          });
        }
      }).catch(error => {
        console.log(error);
      }).finally(() => {
        this.loading = false;
      });
    },
  },
  created() {
    const termId = this.$route.params.id;
    this.termDetail(termId);
  }
};
</script>

<style scoped>

</style>
