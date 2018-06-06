<template>
  <div>
    <div class="post">
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
  </div>
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
      }
    },
    methods: {
      async change() {
        const page = this.$route.query.page || 1;
        await this.$store.dispatch('posts', { page });
        const res = this.$store.getters.getPost;
        const { post, ok, message } = res;
        this.posts = post.list;
        this.total = Number(post.total) || 0;
        this.page = Number(post.page) || 1;
        this.pageSize = Number(post.pageSize) || 0;
        this.ok = ok;
        this.message = message;
      }
    },
    async beforeMount() {
      this.change();
    },
    components: {
      Post,
      Pagination,
    }
  }
</script>