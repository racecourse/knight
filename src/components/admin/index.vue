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