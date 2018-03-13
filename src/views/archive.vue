<template>
  <div>
    <div class="arch-wrap">
      <div v-for="(posts, month) in archive" :key="month">
        <div class="month">{{month}}</div>
        <div v-if="Array.isArray(posts)">
          <mu-timeline
            lineColor="black"
            lineType="dashed"
          >
            <div v-for="post in posts" :key="post.id">
              <mu-timeline-item iconColor="red" iconType="dotted">
                <span slot="time">
                  {{new Date(post.created * 1000).toLocaleString()}}
                </span>
                <span slot="des">{{post.title}}</span>
              </mu-timeline-item>
            </div>
          </mu-timeline>
        </div>
      </div>
    </div>
    <Pagination :page="page" :total="total" :pageSize="pageSize"></Pagination>
  </div>
</template>
<script>
  import Pagination from '../components/pagination/post.vue';
  import Bottom from '../components/common/bottom.vue';
  export default {
    data() {
      return {
        page: 1,
        pageSize: 0,
        total: 0,
        list: [],
      }
    },
    beforeMount() {
      this.load();
    },
    beforeUpdate() {
      const page = Number(this.$route.query.page);
      if(page && page !== Number(this.page)) {
        this.load();
      }
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
        const query = Object.assign({}, this.$route.query);
        await this.$store.dispatch('posts', query);
        const res = this.$store.state.post;
        const data = res.post;
        const { list, page, pageSize, total } = data;
        this.list = list;
        this.page = page;
        this.pageSize = pageSize;
        this.total = total;
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
    width: 50%;
  }
</style>