<template>
  <div>
    <Editor :article="article" name="article"></Editor>
  </div>
</template>
<style lang='sass'>
</style>
<script>
  import Editor from '../editor/index.vue';
  export default {
    data () {
      return {
        article: {
          permission: "1",
          category: ['0'],
        }
      }
    },
    async beforeMount () {
      const id = this.$route.params.id;
      await this.$store.dispatch('getArt', id);
      const state = this.$store.state;
      const article = state.admin.article;
      this.article = article;
    },
    components: {
      Editor
    }

  }

</script>