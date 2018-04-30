<template>
  <div class="layout">
    <div class="layout-header">
      <div class="nav">
        <div class="site-title">
          <span class="logo">
            <router-link :to="{ path: '/'}">桑下语</router-link>
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
        <div class="nav-toggle">
          <SideBar></SideBar>
        </div>
      </div>
    </div>
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
</template>
<style>
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

  .layout-header {
    width: 100%;
    justify-content: center;
    align-items: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    text-align: center;
    overflow: hidden;
  }

  .about {
    margin: 2.6rem;
  }

  .description {
    display: block;
  }

  .logo{
    font-size: 24px;
    color: white;
    display: block;
  }

  .nav {
    margin: 0px auto;
    display: block;
    min-height: 3rem;
    background-color:rgba(0, 0, 0, .9);
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
    color: #fff;
  }
  @media screen and (max-width: 768px) {
    .nav-toggle {
      /* float: right; */
      display: inline;
    }
  }
  .nav .site-title {
    float: left;
    text-align: left;
    margin-left: 5rem;
    padding-top: 0.5rem;
    font-size: 18px;
    color:#fff;
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
    margin-top: 2em;
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
      console.log(this.$route.path);
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
