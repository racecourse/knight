<template>
  <div class="pagination">
    <div class="prev" @click="prev"><mu-icon-button icon="arrow_back"/> </div>
    <div class="next" @click="next"><mu-icon-button icon="arrow_forward"/></div>
  </div>
</template>
<style>
  .pagination {
    margin-top: 1.2em;
    width: 100%;
    height: 1.2em;
    font-size: 1.5em;
  }
  .pagination .next{
    float: right;
    height: 8em;
    vertical-align: baseline;
    padding: 1em;
    cursor: pointer;
  }
  .pagination .prev{
    float: left;
    vertical-align: baseline;
    padding: 1em;
    font-size: 1.2rem;
    cursor: pointer;
  }
</style>
<script>
  import querystring from 'querystring';
  export default {
    props: {
      page: { type: Number, default: 1 },
      total: { type: Number, default: 20 },
      pageSize: { type: Number, default: 20 },
    },
    data: function() {
      return {
        ok: false,
        message: '',
      }
    },
    methods: {
      async next() {
        let page = 0;
        const total = this.total;
        const pageSize = this.pageSize;
        if(!page) page = this.$route.query.page || 1;
        page = Number(page) + 1;
        if (page * pageSize >= total) return null;
        let uri = this.$route.path;
        let query = Object.assign({}, this.$route.query, {page});
        query = querystring.stringify(query);
        uri = uri + '?' + query;
        this.$router.push(uri);

      },
      prev() {
        let page = this.page;
        if(!page) page = this.$route.query.page || 1;
        page = page - 1;
        if (page < 1) return null;
        const uri = this.$route.path;
        let query = Object.assign({}, this.$route.query, {page});
        query = querystring.stringify(query);
        this.$router.push(uri + '?' + query);
      }
    }
  }
</script>