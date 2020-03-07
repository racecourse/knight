<template>
  <mu-container>
    <mu-paper :z-depth="1">
      <mu-data-table :columns="columns" :data="posts" border>
        <template slot-scope="scope">
          <td>{{scope.row.category}}</td>
          <td>{{scope.row.title}}</td>
          <td>{{scope.row.permission}}</td>
          <td>{{scope.row.created}}</td>
          <td>
            <div class="action">
              <button class="action-btn" @click="edit(scope.row.id)">编辑</button>
              <button class="action-btn" @click="confirm(scope.row)">删除</button>
            </div>
          </td>
        </template>
      </mu-data-table>
    </mu-paper>
    <div class="a-page">
      <Pagination :current="page"
        :total="total"
        :pageSize="pageSize"
        @query="onPagination"
      ></Pagination>
    </div>
    <mu-dialog title="Are you sure?" width="600" max-width="80%" :esc-press-close="false" :overlay-close="false" :open.sync="dialog">
      Do you want delete article: {{currentItem.title}}
      <mu-button slot="actions" flat color="primary" @click="closeDialog">cancel</mu-button>
      <mu-button slot="actions" flat color="primary" @click="del(currentItem.id)">Ok</mu-button>
    </mu-dialog>
  </mu-container>
</template>
<style>
  .action .action-btn {
    background-color: #b2b2bb;
    margin-left: 0.1em;
    border: 0;
  }

</style>
<script>
  import Pagination from '../pagination/general.vue';
  export default {
    data: () => ({
      page: 1,
      pageSize: 10,
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
      dialog: false,
      currentItem: {},
      columns:[
        { title: '类别', name: 'calories', width: 150, align: 'center', sortable: true },
        { title: '标题', name: 'fat', align: 'center', sortable: true },
        { title: '权限', name: 'carbs', width: 120, align: 'center', sortable: true },
        { title: '创建时间', name: 'protein', width: 130, align: 'center', sortable: true },
        { title: '操作', name: 'carbs', width: 150, align: 'center', sortable: true },
      ],
    }),
    components: {
      Pagination,
    },
    methods: {
      async onPagination() {
        const total = this.total;
        const page = this.$route.query.page || 1;
        const pageSize = this.$route.query.page || 10;
        await this.$store.dispatch('article', {page, pageSize, total});
        this.loadArticle();
      },
      confirm(current) {
        this.dialog = true;
        this.currentItem = current;
      },
      closeDialog() {
        this.dialog = false;
      },
      edit (id) {
        this.$router.push('/admin/article/' + id + '/edit');
      },
      async del (id) {
        await this.$store.dispatch('delArt', {id});
        this.dialog = false;
      },
      loadArticle() {
        const data = this.$store.state.article;
        const article = data.article || {};
        const {page, list, total, pageSize} = article;
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
