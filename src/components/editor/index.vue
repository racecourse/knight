<template>
  <div>
    <div class="editor">
      <markdownEditor :configs="configs" ref="editor" v-model="art.content"></markdownEditor>
      <div class="editor-option">
        <mu-text-field hintText="created at" v-model="art.created"/>
        <mu-text-field hintText="title" v-model="art.title"/>
        <mu-select-field v-model="art.cateId" label="category">
          <mu-menu-item v-for="(cate,index) in category" :key="index" :value="cate.id" :title="cate.name" />
          <div class="newcate" v-on:keydown.enter="addCate">
            <mu-text-field hintText="new category" v-model="newCate"/>
          </div>
        </mu-select-field>
        <br/>
        <div v-on:keydown.enter="tag">
          <mu-text-field v-model="tagValue" hintText="tag，press enter split"/>
        </div>
        <br/>
        <mu-chip v-for="(tag,index) in tags" :key="index" @delete="deleteTag(index)" showDelete>
          {{tag}}
        </mu-chip>
        <br/>
        <div class="permission">
          <mu-radio label="public" name="permission" nativeValue="1" v-model="art.permission" />
          <mu-radio label="hidden" name="permission" nativeValue="2" v-model="art.permission" />
          <mu-radio label="private" name="permission" nativeValue="3" v-model="art.permission" />
        </div>
        <br/>
        <div class="upload-wrap" @click="showUploadBox">
          <mu-icon value="cloud_upload" :size="32"/>
        </div>
        <br />
        <mu-raised-button label="submit" @click="commit"/>
      </div>
    </div>
    <mu-snackbar v-if="snackbar.show" :message="snackbar.message" 
      action="close" @actionClick="hideSnackbar" @close="hideSnackbar">
    </mu-snackbar>  
    <div>
      <mu-dialog :open="dialog" title="upload" @close="closeUploadBox">
        <span>upload</span>
        <Uploader v-on:uploaded="uploadNotify" :album="1" />
        <mu-flat-button slot="actions" @click="closeUploadBox" primary label="取消"/>
        <mu-flat-button slot="actions" primary @click="closeUploadBox" label="确定"/>
      </mu-dialog>
    </div>
  </div>
</template>
<style lang='sass'>
  @import './editor.scss';
  @import '../admin/main.css';
  @import '~simplemde-theme-base/dist/simplemde-theme-base.min.css';
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
import { markdownEditor } from 'vue-simplemde';
import SimpleMDE from 'simplemde';
import fecha from 'fecha';
import Uploader from './upload.vue';

export default {
  props: {
    article: {
      type: Object,
      required: false,
      default: function () {
        return {
          permission: "1",
          tags: '',
          title: '',
          content: '',
          cateId: 1,
          created: '',
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
      tags: [],
      cateId: 1,
      permission: "1",
      configs: {
        autosave: true,
        status: true,
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
      const article = Object.assign({}, this.art);
      if (!article.title) {
        const message = 'title required~!';
        return this.tip(message);
      }

      if (!article.content) {
        return this.tip('content can not be empty~!');
      }
      
      const data = {
        title: article.title,
        cateId: article.cateId,
        permission: article.permission,
        content: article.content,
        tags: article.tags,
        created: article.created,
      }
      if (!id) {
        await this.$store.dispatch('addArticle', data);
      } else {
        data.id = id;
        await this.$store.dispatch('editArticle', data);
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
    uploadNotify(result, images) {
      if (Array.isArray(images)) {
        images.map(image => {
          console.log(this);
          this.art.content += `<br>![](http://${image.url})`;
        });
      }
    }
  },
  computed: {
    art: function() {
      const data = Object.assign({}, this.article);
      data.permission = String(data.permission);
      const created = data.created ? new Date(data.created * 1000) : new Date();
      data.created =  fecha.format(created, 'YYYY-MM-DD HH:mm:ss');
      data.tags = data.tags ? data.tags.split(',') : [];
      this.tags = data.tags;
      return data;
    },
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

