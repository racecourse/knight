<template>
  <div>
    <div class="content">
      <div style="text-aligin:center">
        <mu-avatar slot="avatar" color="Teal" backgroundColor="lightGreen500">桑</mu-avatar>
        <div class="title"><h4>{{result.title}}</h4></div>
        <mu-sub-header>
          <span>{{result.created}}</span>
        </mu-sub-header>
      </div>
      
      <mu-content-block>
        <section v-html="result.content" @click="preview"></section>
      </mu-content-block>
      <div class="post-footer">
        <div class="cate">桑下语</div>
        <div class="tags">php</div>
      </div>
      <div class="split"></div>
    </div>
    <div class="comment-wrapper">
      <div v-for="comment in comments.list" :key="comment.id">
        <div class="comments">
          <div class="user">{{comment.username}}</div>
          <div class="text">
            <p>{{comment.content}}</p>
          </div>
        </div>
      </div>
      <div class="form-outside">
        <div class="form-inside">
          <mu-text-field label="username" hintText="username" v-model="username"/>
          <mu-text-field label="email" hintText="email" v-model="email"/>
          <mu-text-field label="site" hintText="site" v-model="site"/>
          <mu-text-field label="content" hintText="content" v-model="content" multiLine :rows="10" :rowsMax="60" :maxLength="200"/>
        </div>
        <div class="from-btn">
          <div @click="submit">
             <mu-raised-button label="发表评论"/>
          </div>
        </div>
      </div>
      <mu-snackbar v-if="snackbar.show" :message="snackbar.message" action="close" @actionClick="hideSnackbar" @close="hideSnackbar"/>
    </div>
    <vue-preview></vue-preview>
  </div>
</template>
<script>
  import marked from 'marked';
  import fecha from 'fecha';
  import PhotoSwipe from '../../components/preview';
  import Preview from '../../components/preview/index.vue'

  export default {
    props: {
      article: {
        type: Object,
        required: false,
        default: function () {
          return {
            content: '',
            title: '',
          };
        }
      },
    },
    data () {
      return {
        username: '',
        email: '',
        site: '',
        content: '',
        message: '',
        ok: false,
        comments: {},
      }
    },
    async mounted() {
      const id = this.$route.params.id;
      await this.$store.dispatch('getCommentsByPostId', id);
      let comments = this.$store.state.comment;
      if (comments && comments.comment) {
        const comment = comments.comment;
        this.comments = {
          list: comment.list || [],
          total: comment.total || 0,
          page: comment.page || 1,
          pageSize: comment.pageSize || 20,
        };
      }
    },
    methods: {
      async submit() {
        const username = this.username;
        if (!username || !this.content) {
          this.message = 'username and content required';
          this.ok = false;
          this.snackbar();
          return;
        }
        if (!this.article.id) {
          this.message = 'artile id required';
          this.ok = false;
          this.snackbar();
        }
        const data = {
          id: this.article.id,
          content: this.content,
          site: this.site,
          email: this.email,
          username: this.username,
        };
        await this.$store.dispatch('addComment', data);
        this.comments = this.$store.state.comment;
      },
      snackbar() {
        this.$refs.snackbar.open();
      },
      preview(event) {
        event = event || window.event;
        event.preventDefault ? event.preventDefault() : event.returnValue = false;
        const nodeName = event.target.nodeName;
        if (nodeName && nodeName === 'IMG') {
          const src = event.target.getAttribute('src');
          PhotoSwipe(src);
        }
      }
    },
    computed: {
      result: function () {
        const data = Object.assign({}, this.article);
        const markedOptions = {
          highlight: function(code) {
            return window.hljs.highlightAuto(code).value;
          },
        };
        marked.setOptions(markedOptions);
        data.content =  marked(data.content);
        const created = data.created ? new Date(data.created * 1000) : new Date();
        data.created =  fecha.format(created, 'YYYY-MM-DD HH:mm:ss');
        return data;
      }
    },
    components: {
      Preview
    }
  }
</script>
<style>
  @import './post.css';
  .comment-wrapper {
    position: relative;
    width: 100%;
    background-color: #ffffff;
    display: -webkit-box;
    display: flex;
    -ms-flex-direction: column;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    flex-direction: column;
    z-index: 1;
    /* box-shadow: 0 1px 5px rgba(0, 0, 0, .2), 0 2px 2px rgba(0, 0, 0, .14), 0 3px 1px -2px rgba(0, 0, 0, .12); */
  }

  .comments {
    width: 100%;
    padding: 20px;
    border-bottom: 1px dashed #f0ad4e;
    display: table;
  }

  .comments .user {
    margin-top: -1em;
    color: #99b2ff;
    display: table-cell;
  }

  .comments .text {
    padding: 2em;
    display: table-cell;
    font-weight: 300;
  }

  .form-outside {
    width: 100%;
    padding: 20px;
  }

  .form-inside {
    position: relative;
    width: 80%;
    margin: 1em auto;
    border: 1px solid #b2b2b2;
    padding: 2em;
  }

  .form-outside .from-btn {
    position: relative;
    width: 10em;
    margin: 1em auto;
  }
</style>
