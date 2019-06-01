<template>
  <div class="row">
    <div class="col-md-5">
      <table class="table table-bordered table-sm">
        <tbody>
        <tr>
          <th scope="row">Mã Số</th>
          <td>{{ subject.code }}</td>
        </tr>
        <tr>
          <th scope="row">Tên</th>
          <td>{{ subject.name }}</td>
        </tr>
        <tr>
          <th scope="row">Số Tín Chỉ</th>
          <td>{{ subject.credit }}</td>
        </tr>
        <tr>
          <th scope="row">Mô Tả</th>
          <td>{{ subject.description }}</td>
        </tr>
        </tbody>
      </table>
      <div>
        <div>
          <h6>Tạo Mới Nội Dung Môn Học</h6>
        </div>
        <el-form :model="newChapter" :rules="chapterRules" ref="createChapterForm" label-width="50px" :label-position="'left'" size="mini">
          <el-form-item label="Tên" prop="name">
            <el-input v-model="newChapter.name"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="createChapter('createChapterForm')">Tạo Mới</el-button>
            <el-button @click="resetForm('createChapterForm')">Hủy</el-button>
          </el-form-item>
        </el-form>
      </div>
    </div>
    <div class="col-md-7">
      <el-table v-loading="loading" :data="allChapters" border fit highlight-current-row style="width: 100%" size="mini">
        <!--              <el-table-column-->
        <!--                v-if="includeRoles(this.userRoles, [allRoles.admin], false)"-->
        <!--                type="selection" align="center">-->
        <!--              </el-table-column>-->
        <el-table-column align="center" label="STT" width="50">
          <template slot-scope="scope">
            <span>{{ scope.row.index }}</span>
          </template>
        </el-table-column>

        <el-table-column label="Tiều Đề">
          <template slot-scope="scope">
<!--            <span>{{ scope.row.name }}</span>-->
            <template v-if="scope.row.edit">
              <el-form :model="scope.row" :rules="chapterRules" ref="chapterEditForm" size="mini">
                <el-form-item prop="name">
                  <el-input v-model="scope.row.name" class="edit-input" size="mini" />
                </el-form-item>
                <el-form-item>
                  <el-button class="cancel-btn" size="mini" icon="el-icon-refresh" type="warning" @click="cancelEdit(scope.row)">
                    cancel
                  </el-button>
                </el-form-item>
              </el-form>
            </template>
            <span v-else>{{ scope.row.name }}</span>
          </template>

        </el-table-column>

        <el-table-column label="Số Câu Hỏi" align="center" width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.question_num }}</span>
          </template>
        </el-table-column>

        <el-table-column align="center" label="Thao Tác" width="150">
          <template slot-scope="scope">
            <el-button v-if="scope.row.edit" type="success" size="mini" icon="el-icon-circle-check-outline" @click="confirmEdit(scope.row, 'chapterEditForm')" title="Cập Nhật">
              Cập Nhật
            </el-button>
            <el-button v-else type="primary" size="mini" icon="el-icon-edit" @click="scope.row.edit=!scope.row.edit" title="Chỉnh Sửa">
            </el-button>
<!--            <router-link :to = "{ name: 'SubjectEdit', params: { slug: scope.row.slug }}" v-if="includeRoles(userRoles, [allRoles.admin], false)">-->
<!--              <el-button type="primary" size="mini" icon="el-icon-edit" title="Chỉnh sửa">-->
<!--              </el-button>-->
<!--            </router-link>-->
<!--            <el-button type="danger" size="mini" icon="el-icon-delete" @click="handleDelete(scope.row);"  title="Xóa">-->
<!--            </el-button>-->
<!--            <router-link :to = "{ name: 'SubjectEdit', params: { slug: scope.row.slug }}" v-if="includeRoles(userRoles, [allRoles.exams_maker], false)">-->
<!--              <el-button type="warning" size="mini" icon="el-icon-document" title="Nội dung">-->
<!--              </el-button>-->
<!--            </router-link>-->
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
import SubjectResource from '@/api/subject';
// import UserResource from '@/api/user';
// import RoleResource from '@/api/role';

// import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
// import InfoTag from './components/tabs/Info';
//
// import { Message } from 'element-ui';
// import { ALL_ROLES } from '@/utils/auth';
// import { include as includeRole } from '@/utils/role';

const subjectResource = new SubjectResource();
// const userResource = new UserResource();
// const roleResource = new RoleResource();

export default {
  name: 'SubjectContentTab',
  props: {
  },
  data() {
    return {
      loading: true,
      subject: {},
      newChapter: {},
      allChapters: [],
      chapterRules: {
        name: [
          { required: true, message: 'Nhập tên nội dung môn học', trigger: ['blur', 'change'] },
          { min: 3, max: 250, message: 'Độ dài trường tên nội dung môn học tư 3 đến 250 ký tự', trigger: ['blur', 'change'] },
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
        this.listChapters();
      }).catch(() => {

      }).finally(() => {
        this.loading = false;
      });
    },
    listChapters() {
      this.loading = true;
      subjectResource.chapters({
        limit: 100,
      }, this.subject.id).then(response => {
        const { data } = response;
        this.allChapters = data;
        this.allChapters.forEach((element, index) => {
          element['index'] = index + 1;
        });

        this.allChapters = this.allChapters.map(v => {
          this.$set(v, 'edit', false); // https://vuejs.org/v2/guide/reactivity.html
          v.originalName = v.name; //  will be used when user click the cancel botton
          return v;
        });
      }).catch(error => {
        console.log(error);
      }).finally(() => {
        this.loading = false;
      });
    },
    createChapter(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          subjectResource.storeChapter(this.newChapter, this.subject.id).then(response => {
            this.resetForm(formName);
            this.loading = true;
            this.listChapters();
            if (response.error) {
              this.$message({
                message: 'Tạo mới nội dung môn học không thành công do dữ liệu  trùng lặp hoặc không hợp lệ.',
                type: 'error',
                duration: 5 * 1000,
              });
            } else {
              this.$message({
                message: 'Tạo mới nội dung môn học thành công.',
                type: 'success',
                duration: 5 * 1000,
              });
            }
          }).catch(error => {
            console.log(error);
            this.$message({
              message: 'Tạo mới nội dung môn học không thành công.',
              type: 'error',
              duration: 5 * 1000,
            });
          }).finally(() => {
            this.loading = false;
          });
        } else {
          console.log('error submit!!');
          this.$message({
            message: 'Dữ liệu không hợp lệ. Vui lòng nhập lại!',
            type: 'error',
            duration: 5 * 1000,
          });
          return false;
        }
      });
    },
    cancelEdit(row) {
      row.name = row.originalName;
      row.edit = false;
      // this.$message({
      //   message: 'The title has been restored to the original value',
      //   type: 'warning',
      // });
    },
    confirmEdit(row, formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          row.edit = false;
          row.originalName = row.name;
          this.loading = true;
          subjectResource.updateChapter(row, this.subject.id, row.id).then(response => {
            if (response.error) {
              this.$message({
                message: 'Cập nhật nội dung môn học không thành công do dũ liệu trùng lặp hoặc không hợp lệ',
                type: 'error',
                duration: 5 * 1000,
              });
            } else {
              this.listChapters();
              this.$message({
                message: 'Cập nhật nội dung môn học thành công',
                type: 'success',
                duration: 5 * 1000,
              });
            }
          }).catch(error => {
            console.log(error);
            this.$message({
              message: 'Cập nhật nội dung môn học không thành công',
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
          return false;
        }
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    },
  },
  async created() {
    const subjectName = this.$route.params.slug;
    await this.subjectDetail(subjectName);
  },
};

</script>

<style scoped>

</style>
