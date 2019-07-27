<template>
  <div class="app-container">
<!--    <div>-->
<!--      <h6 class="title-partial">Danh sách câu hỏi môn học </h6>-->
<!--    </div>-->

    <div class="filter-container">
      <div class="d-flex">
        <div>
          <el-select v-model="query.chapter" clearable placeholder="Chọn Nội Dung Môn Học" size="mini" @change="getQuestions(subject)">
            <el-option
              v-for="cht in allChapters"
              :key="cht.id"
              :label="cht.name + ' (' + cht.question_num + ')'"
              :value="cht.id">
            </el-option>
          </el-select>
        </div>
        <div class="d-flex">
          <div class="ml-auto">
            <el-button size="mini" class="filter-item ml-2" type="primary" icon="el-icon-plus" @click="handleCreate">
              {{ $t('table.add') }}
            </el-button>
          </div>
        </div>
      </div>
    </div>

    <el-table
      v-loading="loading"
      :data="questions"
      size="mini"
      style="width: 100%">
      <el-table-column
        type="selection">
      </el-table-column>
      <el-table-column type="expand">
        <template slot-scope="props">
          <ul class="p-0 mb-0 question-info">
            <li v-for="(option, index) in props.row.options" class="option-wrapper">
              <el-tag v-if="option.is_correct" type="success" size="mini"><i class="el-icon-check"></i></el-tag>
              <div v-html="option.content" class="d-inline-block m-0"></div>
              <el-divider v-if="index != 3"></el-divider>
            </li>
          </ul>
        </template>
      </el-table-column>
      <el-table-column
        label="Câu Hỏi">
        <template slot-scope="scope">
          <div v-html="scope.row.content" class="m-0 question-wrapper"></div>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Thao Tác" width="150">
        <template slot-scope="scope">
          <router-link :to = "{ name: 'EditQuestion', params: {subjectSlug: subject.slug, questionId: scope.row.id }}" v-if="!scope.row.deleted_at" >
            <el-button type="primary" size="mini" icon="el-icon-edit" title="Chỉnh sửa">
            </el-button>
          </router-link>
          <el-button class="m-0" v-if="!scope.row.is_actived" type="success" size="mini" icon="el-icon-check" @click="handleActiveQuestion(scope.row);"  title="Kích hoạt câu hỏi">
          </el-button>
          <el-button class="m-0" v-else type="warning" size="mini" icon="el-icon-close" @click="handleDeactiveQuestion(scope.row);"  title="Khóa câu hỏi">
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.per_page" @pagination="getQuestions(subject.id)" />
  </div>
</template>

<script>
import SubjectResource from '@/api/subject';
import { getNotification } from '@/utils/notification';
// import ExamFormatResource from '@/api/examFormat';
// import ChapterResource from '@/api/chapter';

const subjectResource = new SubjectResource();
// const chapterResource = new ChapterResource();

import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination

export default {
  name: 'QuestionTab',
  components: {
    Pagination,
  },
  data() {
    return {
      loading: true,
      subject: {},
      questions: [],
      tmpChapter: '',
      allChapters: [],
      query: {
        per_page: 10,
        page: 1,
        chapter: '',
        order_by: 'id',
        order: 'asc',
      },
      total: 0,
    };
  },
  watch: {
    tmpChapter: function(value) {
      this.query.chapter = value;
      this.getQuestions(this.subject);
    },
  },
  methods: {
    getQuestions(subject) {
      this.loading = true;
      subjectResource.questions(this.query, subject.slug).then(response => {
        const { data, meta } = response;
        this.questions = data;
        this.total = meta.total;
      }).catch(error => {
        console.log(error);
      }).finally(() => {
        this.loading = false;
      });
    },
    subjectDetail(idKey) {
      this.loading = true;
      subjectResource.get(idKey).then(response => {
        const { data } = response;
        this.subject = data;
        this.getChapters(this.subject);
        this.getQuestions(this.subject);
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
    handleActiveQuestion(question) {
      subjectResource.activeQuestion(this.subject.slug, question.id).then(response => {
        if (response.error) {
          getNotification('Kích hoạt', 'câu hỏi', 'error', 'Do dữ liệu không hợp lệ');
        } else {
          getNotification('Kích hoạt', 'câu hỏi', 'success', 'success');
          this.getQuestions(this.subject);
        }
      }).catch(error => {
        console.log(error);
        getNotification('Kích hoạt', 'câu hỏi', 'error');
      });
    },
    handleDeactiveQuestion(question) {
      subjectResource.deactiveQuestion(this.subject.slug, question.id).then(response => {
        if (response.error) {
          getNotification('Khóa', 'câu hỏi', 'error', 'Do dữ liệu không hợp lệ');
        } else {
          getNotification('Khóa', 'câu hỏi', 'success', 'success');
          this.getQuestions(this.subject);
        }
      }).catch(error => {
        console.log(error);
        getNotification('Khóa', 'câu hỏi', 'error');
      });
    },
    handleCreate() {
      this.$router.push({ name: 'CreateQuestion', params: { subjectSlug: this.subject.slug }});
    },
  },
  created() {
    const subjectName = this.$route.params.subjectSlug;
    this.subjectDetail(subjectName);
  },
  beforeDestroy() {
    this.questions = undefined;
    this.subject = undefined;
  },
};
</script>

<style scoped lang="scss">
  /*.question-info {*/
  /*  li.key {*/
  /*    &::before {*/
  /*      content: "• ";*/
  /*      color: red; !* or whatever color you prefer *!*/
  /*    }*/
  /*  }*/
  /*}*/
  .el-divider--horizontal{
    margin: 5px 0;
  }
  /* .option-wrapper {*/
  /*   #eJOY__extension_root {*/
  /*     display: none !important;*/
  /*   }*/
  /*   p:first-child {*/
  /*     margin-bottom: 0;*/
  /*   }*/
  /* }*/
  .question-wrapper {
    overflow: auto;
  }
  p {
    margin-bottom: 0 !important;
  }

  .el-table__expanded-cell {
    padding-left: 10px;
    padding: 0;
  }
</style>

