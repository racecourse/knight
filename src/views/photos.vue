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
      image: "https://photo.yupoo.com/courser/a7314bce/69b05f70.jpg"
    };
  },
  mounted() {
    pannellum.viewer("panorama", {
      type: "equirectangular",
      panorama: "https://photo.yupoo.com/courser/a7314bce/69b05f70.jpg",
      autoLoad: true,
      title: "Cool dog",
      autoRotate: 1.5
    });
  }
};
</script>
