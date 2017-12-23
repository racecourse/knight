<template>
  <div class="layout">
    <div class="header">
      <div class="nav">
        <div class="nav-item">
          <SideBar></SideBar>
        </div>
        <div class="nav-item">首页</div>
        <div class="nav-item">归档</div>
        <div class="nav-item">关于</div>
        <div class="filter">
          <!-- <input type="text" name="keyword"/> -->
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
  <!-- <div class="container">
    <SideBar></SideBar>
    <mu-row>
      <mu-col width="100" tablet="95" desktop="70" class="content-warp">
        <div>
          <transition>
            <router-view></router-view>
          </transition>
        </div>  
      </mu-col>
    </mu-row>
    <Bottom></Bottom>
  </div> -->
</template>
<style>
  .layout {
    padding: 0;
    color: rgb(28, 28, 28);
    background-color: #fefefe;
  }
  .container {
    height: 100%;
    /* min-height: 760px; */
    padding-bottom: 0;
    margin: 0 auto;
  }

  .header {
    width: 100%;
    justify-content: center;
    align-items: center;
    background: #5e90b8;
    overflow: hidden;
  }

  .logo{
    font-size: 24px;
    color: white;
    display: block;
  }

  .nav {
    position: relative;
    max-width: 720px;
    margin: 0 auto;
    padding: .3em 0;
    display: block;
  }

  .nav-item {
    padding:0 1.5em;
    color: #ffffff;
    display: inline-block;
  }

  .header .filter {
    display: inline-block;
    margin-left: 5em;
    border: 0;
    padding-left: 5px;
    height: 32px;
    width: 10em;
    -ms-flex: 1;
    -webkit-box-flex: 1;
    flex: 1;
    border: none;
    line-height: 24px;
  }
  .filter input {
    padding: 0 1em;
    line-height: 2em;
    height: 2em !important;
    -webkit-border-radius: 21px;
    border-radius: 21px;
  }
  .search-btn {
    cursor: pointer;
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
</style>
<script>
  import SideBar from '../nav/sideBar.vue';
  import Bottom from './bottom.vue';
  export default{
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
