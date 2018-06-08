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
    <div class="a-page" @click="more" v-if="page * pageSize < total">
      <div class="p-more">more</div>
      <mu-icon value="more_horiz"></mu-icon>
    </div>
  </div>
</template>
<script>
  import Pagination from '../components/pagination/post.vue';
  import Bottom from '../components/common/bottom.vue';
  export default {
    data() {
      return {
        page: 1,
        pageSize: 20,
        total: 0,
        list: [],
      }
    },
    beforeMount() {
      this.load();
    },
    // beforeUpdate() {
    //   const page = Number(this.$route.query.page);
    //   console.log(page * this.pageSize , this.total)
      
    //   if(page && page * this.pageSize <= this.total ) {
    //     this.load();
    //   }
    // },
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
        const query = Object.assign({}, this.$route.query);
        await this.$store.dispatch('posts', query);
        const res = this.$store.state.post;
        const data = res.post;
        const { list, page, pageSize, total } = data;
        this.list = this.list.concat(list);
        this.page = page;
        this.pageSize = pageSize;
        this.total = total;
      },
      async more() {
        let page = this.$route.query.page || 1;
        if (page * this.pageSize < this.total) {
          page++;
          this.$router.push({ query: { page } });
          await this.load();
        }
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