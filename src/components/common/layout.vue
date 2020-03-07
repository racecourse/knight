<template>
  <mu-paper>
    <div class="header">
      <div class="nav-header">
        <router-link :to="{ path: '/'}">
          <div class="nav-name"></div>
        </router-link>
        <div class="item-box">
            <div class="nav-item">
              <router-link :to="{ path: '/posts' }">
                <mu-button flat>
                  技术
                </mu-button>
              </router-link>
            </div>
            <div class="nav-item">
              <router-link :to="{ path: '/albums' }">
              <mu-button flat>
                相册
              </mu-button>
              </router-link>
            </div>
            <div class="nav-item">
              <router-link :to="{ path: '/timeline' }">
                <mu-button flat>
                  时间抽
                </mu-button>
              </router-link>
            </div>
            <div class="nav-item">
              <router-link :to="{ path: '/posts', query: { type: '桑下语'} }">
                <mu-button flat>
                  生活
                </mu-button>
              </router-link>
            </div>
          </div>
        </div>
    </div>
    <div class="main">
      <div class="container"  >
        <mu-row>
          <mu-col width="100" tablet="100" desktop="100" class="content-warp">
            <div>
              <transition>
                <router-view></router-view>
              </transition>
            </div>
          </mu-col>
        </mu-row>
      </div>
      <div class="nav"></div>
      <div class="sider"></div>
    </div>
    <div class="footer"><Bottom></Bottom></div>
  </mu-paper>
</template>
<style>
  * {
   box-sizing: border-box;
  }
  .main {
    display: flex;
    min-height: calc(100vh - 20vh);
    justify-content: center;
    align-items: center;
    background-color: #eeeeee;
    min-height: 100%;
  }
  .main > .container {
    flex: 1;
  }
  .main > .nav,
  .main > .sider {
    flex: 0 0 15vw;
  }
  .main > .nav {
    order: -1;
  }
  .header, .footer, .container, .nav, .sider {
    padding: 1rem 0.5rem;
  }

  /*.header {*/
  /*  border-top: 3px solid #d6e69c;*/
  /*  box-shadow: 0px 2px 10px 0px r  gba(0,0,0,0.1), 0 1px rgba(0,0,0,0.1);*/
  /*  background: #fafafa;*/
  /*}*/

  .layout {
    padding: 0;
    color: rgb(28, 28, 28);
    background-color: #fefefe;
  }
  .container {
    height: 100%;
    padding-bottom: 0;
    padding-top: 10px;
  }

  .description {
    display: block;
  }

  .nav-toggle {
    display: none;
  }

  .header .nav-header {
    display: -webkit-flex;
    display: flex;
    text-align: center;
    flex-wrap: nowrap;
    flex-direction: row;
    justify-content: center;
    align-items: center;
  }

  .nav-header .nav-name::before {
    content: '桑';
    color: #d6e69c;
  }
  @media screen and (max-width: 768px) {
    .nav-toggle {
      display: flex;
    }
    .nav-name {
      display: none;
    }
  }
  .nav-header > .site-title {
    width: 100px;
    text-align: left;
    font-size: 18px;
  }

  .nav-item {
    padding:0 0.1em;
    display: inline-block;
    color:rgb(139, 195, 74);
  }

  .icon img {
    width: 80px;
  }
  .footer {
    padding: 1em 0;
    background: #eeeeee;
    color: #105028;
  }
  .footer-content {
    margin: auto;
    display: block;
    max-width: 720px;
    padding: 1em 2em;
  }
  .footer-left {
    float: left;
  }

  .footer-rigth {
    float: right;
  }

  .line {
    border-bottom: 1px solid #585a58;
    margin: 2rem 0;
  }
  .blank {
    width: 330px;
  }

</style>
<script>
  import Bottom from './bottom.vue';
  export default {
    data() {
      return {
        isShow: this.$route.path === '/',
        loading: this.loading,
      }
    },
    mounted() {
      if (this.$route.path !== '/') {
        this.isShow = false;
      }
    },
    components: {
      Bottom,
    },
    methods: {
      search() {
        const keyword = document.getElementById('keyword').value;
        alert('search api is close');
      }
    }
  }
</script>
