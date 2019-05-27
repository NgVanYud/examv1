<template>
  <div class="app-container">
    <el-form ref="itemForm" :model="item" :rules="rules" label-width="120px" size="mini">
      <el-form-item label="Mã Số" prop="code">
        <el-input v-model="item.code"/>
      </el-form-item>
      <el-form-item label="Tên" prop="name">
        <el-input v-model="item.name"/>
      </el-form-item>
      <el-form-item label="Số tín chỉ" prop="credit">
        <el-input type="number" v-model="item.credit" v-model.number="item.credit" :min="1" :max="10"/>
      </el-form-item>
      <el-form-item label="Mô Tả" prop="description">
        <el-input type="textarea" v-model="item.description" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit">Cập Nhật</el-button>
        <el-button @click="onCancel">Hủy</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import SubjectResource from '@/api/subject';
import { Message } from 'element-ui';

const subjectResource = new SubjectResource();

export default {
  name: 'EditUser',
  data() {
    return {
      item: {},
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
    async itemDetail(idKey) {
      const { data } = await subjectResource.get(idKey);
      this.item = data;
    },
    onSubmit() {
      this.updateItem();
    },
    async updateItem() {
      this.$refs['itemForm'].validate((valid) => {
        if (valid) {
          subjectResource.update(this.item.slug, this.item).then(response => {
            if (response.error) {
              this.$message({
                message: 'Cập nhật môn học không thành công do dũ liệu trùng lặp hoặc không hợp lệ',
                type: 'error',
                duration: 5 * 1000,
              });
            } else {
              Message({
                message: 'Cập nhật môn học thành công!',
                type: 'success',
                duration: 5 * 1000,
              });
              this.$router.push({ name: 'SubjectsList' });
            }
          }).catch(error => {
            console.log('Error: ', error);
            Message({
              message: 'Có lỗi xảy ra. Vui lòng thử lại!',
              type: 'error',
              duration: 5 * 1000,
            });
          });
        } else {
          Message({
            message: 'Dữ liệu không hợp lệ. Vui lòng nhập lại!',
            type: 'error',
            duration: 5 * 1000,
          });
        }
      });
    },
    onCancel() {
      this.itemDetail(this.item.slug);
    },
  },
  created() {
    const itemId = this.$route.params.slug;
    this.itemDetail(itemId);
  },
};
</script>

<style scoped>

</style>

