<template>
  <div class="layout">
    <div class="header">
      <SideNav></SideNav>
    </div>
    <div class="main-wrapper">
      <router-view></router-view>
    </div>
    <div class="footer">
      <Bottom></Bottom>
    </div>
  </div>
</template>
<style>
@import './main.css';
</style>
<script>
import SideNav from '../nav/index.vue';
import Bottom from '../common/bottom.vue';
export default {
  components: {
    SideNav,
    Bottom,
  },
}
</script>
<style>
.layout{
  background-color: rgb(236, 236, 236);
}

.header{
  background-color: #7e57c2;
}

.logo{
  font-size: 24px;
  color: white;
  display: inline-block;
  padding: 10px 20px;
}

.nav{
  display: inline-block;
  width: calc(100% - 150px);
  margin: 0 auto;
}

.tab{
  margin: 0 auto;
  width: 400px;
  background-color: rgba(0, 0, 0, 0);
}

.content{
  overflow: hidden;
  width: 90%;
  margin: 0 auto;
}

.content-left{
  width: 30%;
  float: left;
  background-color: white;
  margin-bottom: -4000px;
  padding-bottom: 4000px;
}

.content-right{
  width: 70%;
  display: inline-block;
  float: right;
  background-color: rgba(0, 0, 0, 0)
}

.breadcrumb{
  margin: 10px 0;
}

.body{
  background-color: white;
  min-height: 500px;
}

.footer{
  padding: 20px 0;
  text-align: center;
}
</style>