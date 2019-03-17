<template>
  <div>
    <div class="header">
      <div class="nav-header">
        <div class="site-title">
          <span class="logo">
            <router-link :to="{ path: '/'}">
              <div class="nav-name"></div>
            </router-link>
            <div class="nav-toggle">
              <SideBar></SideBar>
            </div>
          </span>
        </div>
        <div class="item-box">
            <div class="nav-item">
              <router-link :to="{ path: '/posts' }">
                <mu-flat-button label="文章" icon="gesture" primary/>
              </router-link>
            </div>
            <div class="nav-item">
              <router-link :to="{ path: '/albums' }">
              <mu-flat-button label="相册" icon="image" primary/>
              </router-link>
            </div>
            <div class="nav-item">
              <router-link :to="{ path: '/timeline' }">
                <mu-flat-button label="时间轴" icon="linear_scale" primary/>
              </router-link>
            </div>
            <div class="nav-item">
              <mu-flat-button label="标签" icon="flag" primary/>
            </div>
          </div>
        </div>
    </div>
    <div class="main">
      <div class="container">
        <mu-row>
          <mu-col width="100" tablet="95" desktop="100" class="content-warp">
            <div>
              <transition>
                <router-view></router-view>
              </transition>
            </div>
          </mu-col>
        </mu-row>
      </div>
    </div>
    <div class="footer"><Bottom></Bottom></div>
  </div>
</template>
<style>
  * {
   box-sizing: border-box;
  }
  .main {
    display: flex;
    min-height: calc(100vh - 40vh);
  }
  .main > .container {
    flex: 1;
    min-width: 100%;
  }
  .main > .nav,
  .main > .sider {
    flex: 0 0 20vw;
    background: beige;
  }
  .main > .nav {
    order: -1;
  }
  .header, .footer, .container, .nav, .sider {
    padding: 1em;
  }
  .header, .footer {
    height: 20vh;
  }
  .layout {
    padding: 0;
    color: rgb(28, 28, 28);
    background-color: #fefefe;
  }
  .container {
    height: 100%;
    padding-bottom: 0;
    margin: 0 auto;
    padding-top: 10px;
  }

  .description {
    display: block;
  }

  .logo{
    font-size: 24px;
    color: white;
    display: block;
  }

  .item-box {
    float: right;
    margin-right: 10rem;
  }
  @media screen and (max-width: 768px) {
    .item-box {
      display: none;
    }
  }
  .nav-toggle {
    display: none;
  }
  .logo a {
    color: rgb(16, 170, 132);
  }
  .header .nav-header {
    display: -webkit-flex;
    display: flex;
    text-align: center;
    flex-wrap: nowrap;
    flex-direction: row;
    justify-content: flex-start;
  }

  .nav-header .nav-name::before {
    content: '桑'
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

</style>
<script>
  import SideBar from '../nav/sideBar.vue';
  import Bottom from './bottom.vue';
  export default {
    data() {
      return {
        isShow: this.$route.path === '/'
      }
    },
    mounted() {
      if (this.$route.path !== '/') {
        this.isShow = false;
      }
    },
    components: {
      SideBar,
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
