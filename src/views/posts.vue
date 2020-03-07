<template>
  <mu-container v-loading="loading">
    <div class="post" data-mu-loading-color="secondary" v-if="posts.length">
      <Post v-for="post in posts"
            :article="post"
            :key="post.id">
      </Post>
      <div class="a-page">
        <Pagination :current="page"
          :total="total"
          :pageSize="pageSize"
          v-if="posts.length"
          @query="change"
        >
        </Pagination>
      </div>
    </div>
  </mu-container>
</template>

<script>
  import Post from '../components/post/index.vue';
  import Pagination from '../components/pagination/general.vue';

  export default {
    data() {
      return {
        posts: {},
        page: 1,
        pageSize: 20,
        total: 0,
        ok: false,
        message: '',
        loading: true,
        query: this.$route.query,
      }
    },
    methods: {
      async change() {
        this.loading = true;
        const query = this.query || {};
        this.posts = [];
        await this.$store.dispatch('posts', query);
        const res = this.$store.getters.getPost;
        const { post, ok, message } = res;
        this.posts = post.list;
        this.total = Number(post.total) || 0;
        this.page = Number(post.page) || 1;
        this.pageSize = Number(post.pageSize) || 0;
        this.ok = ok;
        this.message = message;
        this.loading = false;
      }
    },
    async beforeMount() {
      await this.change();
    },
    watch: {
      async '$route' (next)  {
        this.query = Object.assign({}, next.query);
        await this.change();
      }
    },
    components: {
      Post,
      Pagination,
    }
  }
</script>
