<template>
  <div>
    <Editor :article="article" name="article"></Editor>
  </div>
</template>
<style lang='sass'>
</style>
<script>
import Editor from '../editor/index.vue';
import fecha from 'fecha';

export default {
  data () {
    return {
      article: {
        permission: "1",
        category: ['0'],
        created: new Date(),
        tags: '',
      }
    }
  },
  async mounted() {
    const id = this.$route.params.id;
    await this.$store.dispatch('getArt', id);
    const state = this.$store.state;
    const article = state.admin.article
    console.log(article)
    this.article = Object.assign({}, article)
    this.article.created = fecha.format(this.article.created * 1000, 'YYYY-MM-DD HH:mm:ss')
    this.article.tags = this.article.tags.split(',')
  },
  components: {
    Editor
  }

}
</script>
