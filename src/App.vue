<template>
  <div md-name="'default'">
    <router-view></router-view>
    <Bottom />
  </div>
</template>
<script>
  import Bottom from './components/common/bottom.vue';
  export default {
    components: {
      Bottom
    }
  }
</script>