<template>
  <div class="comment">
    <mu-icon-button  icon="filter_list" />
    <mu-icon-button icon="search"/>
    <mu-table fixedHeader enableSelectAll multiSelectable selectable showCheckbox>
      <mu-thead slot="header">
        <mu-tr>
          <mu-th>ID</mu-th>
          <mu-th><span>username</span></mu-th>
          <mu-th><span>email</span></mu-th>
          <mu-th><span>site</span></mu-th>
          <mu-th>
            <span>content</span>
          </mu-th>
          <mu-th><span>created</span></mu-th>
          <mu-th><span>action</span></mu-th>
        </mu-tr>
      </mu-thead>
      <mu-tbody>
        <mu-tr v-for="row in list" :key="row.id" :mu-item="{id:row.id}">
          <mu-td> {{row.id}} </mu-td>
          <mu-td> {{row.username}}</mu-td>
          <mu-td> {{row.email}}</mu-td>
          <mu-td> {{row.site}} </mu-td>
          <mu-td> {{row.content}} </mu-td>
          <mu-td> {{row.created}} </mu-td>
          <mu-td>
            <div class="action"><router-link to="/admin/edit/1">edit &nbsp;</router-link>|
              <span @click="del(row.id)">&nbsp; delete</span></div>
          </mu-td>
        </mu-tr>
      </mu-tbody>
    </mu-table>
  </div>
</template>
<style>
  .comment .mu-table .mu-th {
    text-align: center;
  }
  .action {
    width: 8em;
  }
</style>
<script>
  export default {
    data: () => ({
      page: 1,
      pageSize: 0,
      total: 20,
      list: [],
      selected: [],
    }),
    methods: {
      async onPagination() {
        let page = this.page;
        const total = this.total;
        const pageSize = this.pageSize;
        if(!page) page = this.$route.query.page || 1;
        page = page + 1;
//        if (page * pageSize >= total) return null;
        await this.$store.dispatch('comments', {page, pageSize, total});
        this.load();
      },
      onSort() {

      },
      edit() {
        console.log(1111)
      },
      del(id) {
        this.$store.dispatch('delArt', {id});
      },
      load() {
        const data = this.$store.state.comment;
        console.log(data);
        const comments = data.comment || {};
        const {page, list, total, pageSize} = comments;
        this.list = list || [];
        this.page = page || 1;
        this.pageSize = pageSize || 20;
        this.total = total || 0;
      }
    },
    async beforeMount() {
      const params = {
        page: this.page,
      };
      await this.$store.dispatch('comments', params);
      this.load();
    },
    computed: {
      posts() {
        const category = this.category;
        return this.list.map(function (post) {
          post.category = '';
          category.map(function (cate) {
            if(cate.id === post.cateId) {
              post.category = cate.name;
            }
          });
          return post;
        });
      }
    },
    filters: {
      permit(level) {
        const data = {
          0: 'public',
          1: 'hidden',
          2: 'private',
        };
        return data[level] ? data[level] : 'unknown';
      }
    },
  }
</script>