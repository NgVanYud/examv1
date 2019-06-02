<template>
  <div>
    <div class="document-editor">
      <div class="document-editor__toolbar" :id="toolbarId"></div>
      <div class="document-editor__editable-container">
        <div class="document-editor__editable" :id="editAreaId">
          <ckeditor :value="valueSaved" :editor="editor" :config="editorConfig" @ready="onReady" v-on:input="updateValue($event)"></ckeditor>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import CKEditor from '@ckeditor/ckeditor5-vue';
import DecoupledEditor from '@ckeditor/ckeditor5-build-decoupled-document/build/ckeditor';

import FileResource from '@/api/files';
const fileResource = new FileResource();

class UploadAdapter {
  constructor(loader) {
    this.loader = loader;
  }
  upload() {
    return this.loader.file
      .then(uploadedFile => {
        return new Promise((resolve, reject) => {
          const formData = new FormData();
          formData.append('upload', uploadedFile);
          fileResource.upload(formData).then(response => {
            resolve({
              default: response,
            });
          }).catch(error => {
            console.log(error);
          });
        });
      });
  }
  abort() {
    console.log('huy roi');
  }
}

export default {
  name: 'CKEditorItem',
  components: {
    'ckeditor': CKEditor.component,
  },
  props: {
    toolbarId: {
      type: String,
      required: true,
    },
    editAreaId: {
      type: String,
      required: true,
    },
    valueSaved: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      editor: DecoupledEditor,
      editorConfig: {
        extraPlugins: [this.customUploadAdapterPlugin],
        tagName: 'textarea',
      },
    };
  },
  computed: {
    content: function() {
      return this.valueSaved;
    },
  },
  methods: {
    customUploadAdapterPlugin(editor) {
      editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
        return new UploadAdapter(loader);
      };
    },
    onReady(editor) {
      const toolbarContainer = document.querySelector('#' + this.toolbarId);
      toolbarContainer.appendChild(editor.ui.view.toolbar.element);
      // DecoupledEditor
      //   .create(document.querySelector('#' + this.editAreaId), {
      //     extraPlugins: [this.customUploadAdapterPlugin],
      //   })
      //   .then(editor => {
      //     const toolbarContainer = document.querySelector('#' + this.toolbarId);
      //
      //     toolbarContainer.appendChild(editor.ui.view.toolbar.element);
      //
      //     window.editor = editor;
      //   })
      //   .catch(err => {
      //     console.error(err);
      //   });
    },
    updateValue: function (value) {
      this.$emit('changeValue', value);
    },
  },
};
</script>

<style scoped lang="scss">
  .document-editor {
    border: 1px solid var(--ck-color-base-border);
    /*border-radius: var(--ck-border-radius);*/

    /* This element is a flex container for easier rendering. */
    display: flex;
    flex-flow: column nowrap;
  }
  .document-editor__toolbar {
    /* Make sure the toolbar container is always above the editable. */
    z-index: 1;

    /* Create the illusion of the toolbar floating over the editable. */
    box-shadow: 0 0 5px hsla( 0,0%,0%,.2 );

    /* Use the CKEditor CSS variables to keep the UI consistent. */
    /*border-bottom: 1px solid var(--ck-color-toolbar-border);*/
  }

  /* Adjust the look of the toolbar inside the container. */
  .document-editor__toolbar .ck-toolbar {
    border: 0;
    border-radius: 0;
  }

  .ck-editor__editable_inline {
    min-height: 250px !important;
  }

  /* Make the editable container look like the inside of a native word processor application. */
  /*.document-editor__editable-container {*/
  /*  padding: calc( 2 * var(--ck-spacing-large) );*/
  /*  background: var(--ck-color-base-foreground);*/

  /*  !* Make it possible to scroll the "page" of the edited content. *!*/
  /*  overflow-y: scroll;*/
  /*}*/

  /*.document-editor__editable-container .ck-editor__editable {*/
  /*  !* Set the dimensions of the "page". *!*/
  /*  width: 15.8cm;*/
  /*  min-height: 21cm;*/

  /*  !* Keep the "page" off the boundaries of the container. *!*/
  /*  padding: 1cm 2cm 2cm;*/

  /*  border: 1px hsl( 0,0%,82.7% ) solid;*/
  /*  border-radius: var(--ck-border-radius);*/
  /*  background: white;*/

  /*  !* The "page" should cast a slight shadow (3D illusion). *!*/
  /*  box-shadow: 0 0 5px hsla( 0,0%,0%,.1 );*/

  /*  !* Center the "page". *!*/
  /*  margin: 0 auto;*/
  /*}*/
  /*.document-editor .ck-content,*/
  /*.document-editor .ck-heading-dropdown .ck-list .ck-button__label {*/
  /*  font: 16px/1.6 "Helvetica Neue", Helvetica, Arial, sans-serif;*/
  /*}*/
</style>
