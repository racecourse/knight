<template>
  <div>
    <mu-container ref="container" class="demo-loadmore-content">
      <mu-load-more
        :loading="loading"
        @refresh="refresh"
        @load="load">
        <div class="arch-wrap">
          <div v-for="(posts, month) in archive" :key="month">
            <div class="month">{{month}}</div>
            <div v-if="Array.isArray(posts)">
              <mu-list v-for="post in posts">
                <mu-list-item avatar :ripple="false" button>
                  <mu-list-item-action>
                    <mu-avatar color="teal">
                      {{post.title[0]}}
                    </mu-avatar>
                  </mu-list-item-action>
                  <mu-list-item-content>
                    <mu-list-item-title>{{post.title}}</mu-list-item-title>
                    <mu-list-item-sub-title>
                      {{moment(post.created * 1000).format()}}
                    </mu-list-item-sub-title>
                  </mu-list-item-content>
                </mu-list-item>
                <mu-divider></mu-divider>
              </mu-list>
            </div>
          </div>
        </div>
      </mu-load-more>
    </mu-container>
<!--    <div class="a-page" @click="more" v-if="page * pageSize < total">-->
<!--      <div class="p-more">more</div>-->
<!--      <mu-icon value="more_horiz"></mu-icon>-->
<!--    </div>-->
  </div>
</template>
<script>
  import Pagination from '../components/pagination/post.vue';
  export default {
    data() {
      return {
        page: 1,
        pageSize: 20,
        total: 0,
        list: [],
        loading: false,
        moment: this.$moment
      }
    },
    beforeMount() {
      this.load();
    },
    computed: {
      archive: function () {
        const data = {};
        this.list.map(function (post) {
          const date = new Date(post.created * 1000);
          const month = date.getMonth() + 1;
          const year = date.getFullYear();
          const archive = year + '年' + month + '月';
          if (!data[archive]) {
            data[archive] = [post];
          } else {
            data[archive].push(post);
          }
        });
        return data;
      }
    },
    methods: {
      async load() {
        // @todo 加载逻辑有问题
        this.loading = false;
        const query = Object.assign({}, this.$route.query);
        await this.$store.dispatch('posts', query);
        const res = this.$store.state.post;
        const data = res.post;
        const { list, page, pageSize, total } = data;
        const ids = this.list.map(item => item.id);
        const more = list.filter(item => !ids.includes(item.id));
        this.list = this.list.concat(more);
        this.page = page;
        this.pageSize = pageSize;
        this.total = total;
        this.loading = false;
      },
      async more() {
        let page = this.$route.query.page || 1;
        if (page * this.pageSize < this.total) {
          page++;
          this.$router.push({ query: { page } });
          await this.load();
        }
      },
      async refresh() {
        this.$router.replace({name:"archive", hash: '#archive'})
      }
    },
    components: {
      Pagination,
    }
  }
</script>
<style>
  .month {
    width: 100%;
    padding: 1em;
    line-height: 1.2em;
    font-size: 16px;
    display: block;
    text-align: center;
  }

  .arch-box {
    width: 45%;
    margin-left: 2%;
    margin-top: 0.5em;
    display: inline-block;
  }

  .arch-wrap {
    margin: 0 auto;
    max-width: 800px;
  }
  .p-more {
    padding: 10px;
    cursor: pointer;
    color: rgba(118, 201, 136, 0.842);
    font-weight: 400;
    font-style: normal;
    font-size: 20px;
    margin-top: -15px;
  }
</style>
