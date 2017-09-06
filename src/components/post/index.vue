<template>
  <div>
    <div class="content">
      <mu-card>
        <div @click="detail(post.id)">
          <mu-card-header :title="post.title"
            :subTitle="new Date(post.created * 1000).toLocaleDateString()">
              <mu-avatar slot="avatar"color="Teal" backgroundColor="lightGreen500">桑</mu-avatar>
          </mu-card-header>
        </div>
        <mu-card-text>
          <div v-html="post.content"></div>
        </mu-card-text>
      </mu-card>
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
        post.content =  marked(post.content);
        console.log(post.content);
        return post;
      }
    }
  }
</script>

