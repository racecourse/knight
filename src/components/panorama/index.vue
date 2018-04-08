<template>
  <div>
    <div id="panorama"> </div>
  </div>
</template>

<style>
@import "../../util/pannellum.css"; 
#panorama {
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  margin: 0 auto;
  width: 600px;
  height: 400px;
  overflow: hidden;
}
.pnlm-about-msg {
  display: none !important;
}
</style>

<script>
import "../../util/pannellum";
export default {
  props: {
    panorama: {
      type: String,
      required: true,
    },
    title: {
      type: String,
      require: false,
      default: '',
    },
    autoRotate: {
      type: Number,
      require: false,
      default: 1.5
    },
    width: {
      type: Number,
      require: false,
      default: 600
    },
    height: {
      type: Number,
      require: false,
      default: 400,
    }
  },
  data() {
    return {
      image: "https://photo.yupoo.com/courser/a7314bce/69b05f70.jpg"
    };
  },
  mounted() {
    console.log(this.panorama);
    pannellum.viewer("panorama", {
      type: "equirectangular",
      // panorama: "https://photo.yupoo.com/courser/a7314bce/69b05f70.jpg",
      panorama: this.panorama,
      autoLoad: true,
      title: this.title,
      autoRotate: this.autoRotate
    });
  }
};
</script>
