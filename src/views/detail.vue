<template>
  <div class="post">
    <Detail v-bind:article="post"></Detail>
  </div>
</template>
<script>
  import Detail from '../components/post/post.vue';
  export default {
    data() {
      return {
        post: {
          content: '',
          title: '',
          created: '',
        },
        ok: false,
        message: '',
        comments: {
          page: 1,
          pageSize: 20,
          total: 0,
        },
      }
    },
    methods: {},
    async beforeMount() {
      const id = this.$route.params.id;
      await this.$store.dispatch('post', id);
      const res = this.$store.state.post;
      this.ok = res.ok;
      this.message = res.message;
      this.post = res.post;
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
    components: {
      Detail,
    }
  }
</script>
