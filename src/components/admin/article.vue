<template>
  <div>
    <mu-table :fixedFooter="fixedFooter" fixedHeader enableSelectAll multiSelectable selectable showCheckbox>
      <mu-thead slot="header">
        <mu-tr>
          <mu-th tooltip="ID">ID</mu-th>
          <mu-th tooltip="ID"><span>category</span></mu-th>
          <mu-th tooltip="ID"><span>permission</span></mu-th>
          <mu-th tooltip="ID">
            <mu-icon-button icon="title"/>
            <span>title</span>
          </mu-th>
          <mu-th tooltip="ID"><span>created at</span></mu-th>
          <mu-th><span>action</span></mu-th>
        </mu-tr>
      </mu-thead>
      <mu-tbody>
        <mu-tr v-for="row in posts" :key="row.id">
          <mu-td> {{row.id}} </mu-td>
          <mu-td> {{row.category}}</mu-td>
          <mu-td> {{row.permission|permit}}</mu-td>
          <mu-td> {{row.title}} </mu-td>
          <mu-td> {{row.created}} </mu-td>
          <mu-td>
            <div class="action">
              <button class="action-btn" @click="edit(row.id)">编辑</button>
              <button class="action-btn" @click="del(row.id)">删除</button>
            </div>
          </mu-td>
        </mu-tr>
      </mu-tbody>
      <mu-tfoot slot="footer">
        <mu-tr>
          <mu-td>
            <mu-pagination :total="total" :pageSize="pageSize" :current="page" @pageChange="onPagination">
            </mu-pagination>
          </mu-td>
        </mu-tr>
      </mu-tfoot>
    </mu-table>
  </div>
</template>
<style>
  .action .action-btn {
    background-color: #b2b2bb;
    margin-left: 0.1em;
    border: 0;
  }

</style>
<script>
  export default {
    data: () => ({
      page: 1,
      pageSize: 0,
      total: 20,
      list: [],
      category: [],
      selected: [],
      fixedHeader: true,
      fixedFooter: true,
      selectable: true,
      multiSelectable: true,
      enableSelectAll: false,
      showCheckbox: true,
    }),
    methods: {
      async onPagination() {
        let page = this.page;
        const total = this.total;
        const pageSize = this.pageSize;
        if(!page) {
          page = this.$route.query.page || 1;
        }
        page = page + 1;
//        if (page * pageSize >= total) return null;
        await this.$store.dispatch('article', {page, pageSize, total});
        this.loadArticle();
      },
      edit (id) {
        this.$router.push('/admin/article/' + id + '/edit');
      },
      del (id) {
        this.$store.dispatch('delArt', {id});
      },
      loadArticle() {
        const data = this.$store.state.article;
        const article = data.article || {};
        const {page, list, total, pageSize} = article;
        this.list = list || [];
        this.page = page || 1;
        this.pageSize = pageSize || 20;
        console.log('xxxxxxx----xxxxxx', article)
        this.total = total || 0;
      }
    },
    async beforeMount() {
      const params = {
        page: this.page,
      };
      await this.$store
        .dispatch('article', params)
        .then(() => {
          return this.$store.dispatch('category')
        });
      this.category = this.$store.getters.getCategory;
      this.loadArticle();
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
    }
  }
</script>