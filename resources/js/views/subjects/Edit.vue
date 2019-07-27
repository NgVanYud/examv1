<template>
  <div class="app-container">
    <div>
      <h6 class="title-partial">Chỉnh Sửa Thông Tin Môn Học - {{ subject.name | uppercaseFirst }}</h6>
    </div>
    <el-form ref="subjectForm" :model="subject" :rules="rules" label-width="120px" size="mini">
      <el-form-item label="Mã Số" prop="code">
        <el-input v-model="subject.code"/>
      </el-form-item>
      <el-form-item label="Tên" prop="name">
        <el-input v-model="subject.name"/>
      </el-form-item>
      <el-form-item label="Số tín chỉ" prop="credit">
        <el-input type="number" v-model="subject.credit" v-model.number="subject.credit" :min="1" :max="10"/>
      </el-form-item>
      <el-form-item label="Mô Tả" prop="description">
        <el-input type="textarea" v-model="subject.description" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="updateSubject('subjectForm')">Cập Nhật</el-button>
        <el-button @click="onCancel()">Hủy</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import SubjectResource from '@/api/subject';
import { getNotification } from '@/utils/notification';
import { uppercaseFirst } from '@/filters';

const subjectResource = new SubjectResource();

export default {
  data() {
    return {
      loading: true,
      subject: {},
      rules: {
        code: [
          { required: true, message: 'Nhập mã môn học', trigger: ['blur', 'change'] },
          { min: 3, max: 20, message: 'Độ dài trường mã môn học từ 3 đên 20 ký tự', trigger: ['blur', 'change'] },
        ],
        name: [
          { required: true, message: 'Nhập tên môn học', trigger: ['blur', 'change'] },
          { min: 3, max: 100, message: 'Độ dài trường tên môn học tư 3 đến 100 ký tự', trigger: ['blur', 'change'] },
        ],
        credit: [
          { required: true, message: 'Nhập số tín chỉ', trigger: ['blur', 'change'] },
          { type: 'number', message: 'Giá trị số tín chỉ phải là số từ 1 đến 10', trigger: ['blur', 'change'] },
        ],
        // description: [{ required: true, message: 'Password is required', trigger: 'blur' }],
      },
    };
  },
  methods: {
    subjectDetail(idKey) {
      this.loading = true;
      subjectResource.get(idKey).then(response => {
        const { data } = response;
        this.subject = data;
      }).catch(() => {

      }).finally(() => {
        this.loading = false;
      });
    },
    updateSubject(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          subjectResource.update(this.subject.slug, this.subject).then(response => {
            if (response.error) {
              throw new Error('error');
            } else {
              getNotification(
                this.$t('notification.action.update'),
                this.$t('notification.object.subject'),
                this.$t('notification.status.success'),
                'success',
              );
              this.$router.push({ name: 'SubjectsList' });
            }
          }).catch(error => {
            console.log('Error: ', error);
            getNotification(
              this.$t('notification.action.update'),
              this.$t('notification.object.subject'),
              this.$t('notification.status.error'),
              'error',
              uppercaseFirst(this.$t('notification.reason', {
                object: this.$t('notification.object.data'),
                status: this.$t('notification.status.invalid'),
              }))
            );
          });
        } else {
          getNotification(
            this.$t('notification.action.update'),
            this.$t('notification.object.subject'),
            this.$t('notification.status.error'),
            'error',
            uppercaseFirst(this.$t('notification.reason', {
              object: this.$t('notification.object.data'),
              status: this.$t('notification.status.invalid'),
            }))
          );
        }
      });
    },
    onCancel() {
      this.subjectDetail(this.subject.slug);
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    },
  },
  async created() {
    const subjectName = this.$route.params.subjectSlug;
    await this.subjectDetail(subjectName);
  },
};
</script>

<style scoped>

</style>
