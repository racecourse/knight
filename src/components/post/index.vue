<template>
  <div>
    <div class="content">
      <mu-avatar slot="avatar"color="Teal" backgroundColor="lightGreen500">桑</mu-avatar>
      <div class="title" @click="detail(post.id)">
        <h4>{{post.title}}</h4>
      </div>
      <mu-sub-header>
        <span>{{new Date(post.created * 1000).toLocaleDateString()}}</span>
      </mu-sub-header>
      <mu-content-block>
        <section v-html="post.content"></section>
      </mu-content-block>
      <div class="post-footer">
        <div class="cate">桑下语</div>
        <div class="tags">php</div>
      </div>
      <div class="split"></div>
    </div>
  </div>
</template>
<<style>
@import './post.css';
</style>

<script>
  import marked from 'marked';
  export default {
    props: {
      article: {}
    },
    data () {
      return {
        comments: {},
      }

    },
    mounted () {
      console.log(this.article);
    },
    methods: {
      detail(id) {
        console.log(this.$router);
        this.$router.push('/posts/' + id);
      }
    },
    computed: {
      post()  {
        const post = Object.assign({}, this.article);
        const markedOptions = {
          highlight: function(code) {
            return window.hljs.highlightAuto(code).value;
          },
        };
        marked.setOptions(markedOptions);
        post.content = post.content.substr(0, 500);
        if (post.content.search(/```[^`]+$/) !== -1) {
          post.content += '```';
        }
        console.log(post.content);
        post.content =  marked(post.content);
        return post;
      }
    }
  }
</script>

