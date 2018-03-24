<template>
  <div class="layout">
    <div class="layout-header">
      <div class="about">
        <div class="icon">
          <img src="../../assets/avatar.png"/>
        </div>
        <div class="descripton">
          <p>落花人独立，微雨燕双飞</p>
        </div>
      </div>
      <div class="nav">
        <div class="nav-item">
          <mu-flat-button label="文章" icon="gesture" primary/>
        </div>
        <div class="nav-item">
          <mu-flat-button label="图片" icon="image" primary/>
        </div>
        <div class="nav-item">
          <mu-flat-button label="时间轴" icon="linear_scale" primary/>
        </div>
        <div class="nav-item">
          <mu-flat-button label="标签" icon="flag" primary/>
        </div>
        <div class="nav-item">
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
  }

  .layout-header {
    width: 100%;
    min-height: 280px;
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
    position: relative;
    max-width: 860px;
    margin: 0 auto;
    padding: .3em 0;
    display: block;
    background-color: #c5c2581f ;
  }

  .nav-item {
    padding:0 0.5em;
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
