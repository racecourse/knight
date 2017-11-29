<template>
  <div>
    <div class="editor">
      <markdownEditor :configs="configs" ref="editor" v-model="art.content"></markdownEditor>
      <div class="editor-option">
        <mu-text-field hintText="created at" v-model="art.created"/>
        <mu-text-field hintText="title" v-model="art.title"/>
        <mu-select-field v-model="art.cateId" :labelFocusClass="['label-foucs']" label="category">
          <mu-menu-item v-for="(cate,index) in category" :key="index" :value="cate.id" :title="cate.name" />
          <div class="newcate" v-on:keydown.enter="addCate">
            <mu-text-field hintText="new category enter完成输入" v-model="newCate"/>
          </div>
        </mu-select-field>
        <br/>
        <div v-on:keydown.enter="tag">
          <mu-text-field v-model="tagValue" hintText="标签，enter 完成输入"/>
        </div>
        <br/>
        <mu-chip v-for="(tag,index) in tags" :key="index" @delete="deleteTag(index)" showDelete>
          {{tag}}
        </mu-chip>
        <br/>
        <div class="permission">
          <mu-radio label="public" name="permission" :nativeValue="permission" v-model="art.permission" />
          <mu-radio label="hidden" name="permission" :nativeValue="permission" v-model="art.permission" />
          <mu-radio label="private" name="permission" :nativeValue="permission" v-model="art.permission" />
        </div>
        <br/>
        <mu-raised-button label="submit" @click="commit"/>
      </div>
    </div>
    <mu-snackbar v-if="snackbar.show" :message="snackbar.message" 
      action="close" @actionClick="hideSnackbar" @close="hideSnackbar">
    </mu-snackbar>  
  </div>
</template>
<style lang='sass'>
  @import './editor.scss';
  @import '../admin/main.css';
</style>
<style>
  .newcate {
    padding: 5px 25px;
  }
</style>
<script>
import { markdownEditor } from 'vue-simplemde';
import SimpleMDE from 'simplemde';
import fecha from 'fecha';

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
        initialValue: '###',
        renderingConfig: {
          codeSyntaxHighlighting: true,
          highlightingTheme: 'github'
        }
      }
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
    markdownEditor
  },
}
</script>

