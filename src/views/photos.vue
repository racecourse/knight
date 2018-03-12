<template>
  <div>
    <div id="panorama">
    </div>
  </div>
</template>

<style>
@import "../util/pannellum.css"; 
#panorama {
  margin: auto;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  width: 600px;
  height: 400px;
}
.pnlm-about-msg {
  display: none !important;
}
</style>

<script>
import "../util/pannellum";
export default {
  data() {
    return {
      image: ""
    };
  },
  mounted() {
    pannellum.viewer("panorama", {
      type: "equirectangular",
      panorama:
        "https://img30.360buyimg.com/n1/s760x500_jfs/t18799/57/699938415/172048/8d916a0a/5a9fa7c0Naf130614.jpg",
      autoLoad: true,
      title: "Cool dog",
      autoRotate: 1.5
    });
  }
};
</script>
