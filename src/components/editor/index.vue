<template>
  <div>
    <div class="editor">
      <markdownEditor :configs="configs" ref="editor" v-model="article.content"></markdownEditor>
      <div class="editor-option">
        <mu-text-field help-text="created at" v-model="article.created" :value="article.created"/>
        <mu-text-field help-text="title" v-model="article.title"/>
        <mu-select v-model="article.cateId" label="category" full-width>
          <mu-option v-for="(cate, index) in category" :key="index" :value="cate.id" :label="cate.name" ></mu-option>
        </mu-select>
        <br/>
        <div v-on:keydown.enter="tag">
          <mu-text-field v-model="article.tags" help-text="tag，press enter split"/>
        </div>
        <br/>
        <mu-chip v-for="(tag,index) in tags" :key="index" @delete="deleteTag(index)" showDelete>
          {{tag}}
        </mu-chip>
        <br/>
        <div class="permission">
          <mu-radio v-model="article.permission" label="public" value="1"></mu-radio>
          <mu-radio v-model="article.permission" label="hidden" value="2"></mu-radio>
          <mu-radio v-model="article.permission" label="private" value="3"></mu-radio>
        </div>
        <br/>
        <div class="upload-wrap" @click="showUploadBox">
          <mu-icon value="cloud_upload" :size="32"/>
        </div>
        <br />
        <mu-button @click="commit">submit</mu-button>
      </div>
    </div>
    <mu-snackbar :position="snackbar.position" :open.sync="snackbar.show">
      {{snackbar.message}}
      <mu-button flat slot="action" color="secondary" @click="snackbar.show = false">Close</mu-button>
    </mu-snackbar>
    <div>
      <mu-dialog :open="dialog" title="upload" @close="closeUploadBox">
        <span>upload</span>
        <Uploader :uploaded="uploadNotify" :album="1" />
        <mu-button flat slot="actions" @click="closeUploadBox" primary label="取消"/>
        <mu-button flat slot="actions" primary @click="closeUploadBox" label="确定"/>
      </mu-dialog>
    </div>
  </div>
</template>
<style lang='sass'>
  @import './editor.scss';
  @import '../admin/main.css';
  @import '~simplemde/dist/simplemde.min.css';
</style>
<style>
  .newcate {
    margin: 0 auto;
    width: 80%;
    overflow: hidden;
  }
  .upload-wrap {
    text-align: center;
    padding: 5px;
    border: 1px dashed #b2b2b2;
    border-radius: 5px;
    cursor: pointer;
  }
</style>
<script>
import markdownEditor from 'vue-simplemde/src/markdown-editor'
import fecha from 'fecha';
import Uploader from './upload.vue';
import config from '../../config'

export default {
  props: {
    article: {
      type: Object,
      required: false,
      default: function () {
        return {
          permission: "1",
          tags: [],
          title: '',
          content: '',
          cateId: 1,
          created: fecha.format(new Date(), 'YYYY-MM-DD HH:mm:ss'),
        };
      },
    }
  },
  data: function () {
    return {
      snackbar: {
        message: '',
        show: false,
        snackTimer: 3000,
      },
      tagValue: '',
      tags: [],
      editor: null,
      category: [],
      newCate: '',
      content: '',
      title: '',
      cateId: 1,
      permission: "1",
      created: '',
      configs: {
        initialValue: '',
        autosave: {
          delay: 5000,
          uniqueId: 'knight_noun'
        },
        spellChecker: false,
        autoDownloadFontAwesome: true,
        renderingConfig: {
          codeSyntaxHighlighting: false,
          highlightingTheme: 'github'
        }
      },
      dialog: false,
    }
  },
  async mounted() {
    await this.$store.dispatch('category');
    this.category = this.$store.getters.getCategory;
    // const created = this.created ? new Date(this.created * 1000) : new Date();
    // if (!this.created) {
    //   this.created = fecha.format(created, 'YYYY-MM-DD HH:mm:ss');
    // }
    // // const data = Object.assign({}, this.article);
    // const data = this.article
    // console.log('cccddddddcdcd', data)
    // data.permission = String(data.permission);
    // data.created =  fecha.format(created, 'YYYY-MM-DD HH:mm:ss');
    // data.tags = data.tags ? data.tags.split(',') : [];
    // this.article = data;
  },
  methods: {
    tag() {
      this.tags.push(this.tagValue);
      this.tagValue = '';
    },
    deleteTag(index) {
      this.tags.splice(index, 1);
    },
    async addCate() {
      if (!this.newCate) {
        return this.tip('category name reuqired');
      }

      this.$store.dispatch('addCategory', this.newCate);
      await this.$store.dispatch('category');
      this.category = this.$store.getters.getCategory;
      this.tip('add new category success~!');
      this.newCate = '';
    },
    async commit() {
      const id = this.$route.params.id;
      console.log(this.article)
      if (!this.article.title) {
        const message = 'title required~!'
        return this.tip(message);
      }

      if (! this.article.content) {
        return this.tip('content can not be empty~!')
      }

      const data = Object.assign({},  this.article)
      if (!id) {
        await this.$store.dispatch('addArticle', data)
      } else {
        data.id = id;
        await this.$store.dispatch('editArticle', data)
      }

      this.tip('success~!');
    },
    tip(message) {
      this.snackbar.show = true
      this.snackbar.message = message;
      if (this.snackbar.snackTimer) {
        clearTimeout(this.snackbar.snackTimer);
      }
      this.snackbar.snackTimer = setTimeout(() => { this.snackbar.show = false }, 2000);
    },
    hideSnackbar () {
      this.snackbar.show = false;
      this.snackbar.message = '';
      if (this.snackbar.snackTimer) clearTimeout(this.snackbar.snackTimer)
    },
    showUploadBox() {
      this.dialog = true;
    },
    closeUploadBox() {
      this.dialog = false;
    },
    uploadNotify(images) {
      if (Array.isArray(images)) {
        images.map(image => {
          this.article.content += `![](http://${config.imageDomain}${image.url})`;
        });
      }
    }
  },
  computed: {
    // art: function() {
    //   const data = Object.assign({}, this.article);
    //   data.permission = String(data.permission);
    //   const created = data.created ? new Date(data.created * 1000) : new Date();
    //   data.created =  fecha.format(created, 'YYYY-MM-DD HH:mm:ss');
    //   data.tags = data.tags ? data.tags.split(',') : [];
    //   console.log(data)
    //   return data;
    // },
    simplemde (){
      return this.$refs.editor.simplemde
    }
  },
  components: {
    // quillEditor
    markdownEditor,
    Uploader
  },
}
</script>

