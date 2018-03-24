<template>
  <div class="upload-wraper">
    <div class="upload-album">
      <mu-select-field label="choose album">
        <mu-menu-item value="1" title="阴阳师"/>
        <mu-menu-item value="2" title="影之刃"/>
        <mu-menu-item value="3" title="天下HD"/>
        <mu-menu-item value="4" title="穿越火线"/>
        <mu-menu-item value="5" title="英雄联盟"/>
        <mu-menu-item value="6" title="王者荣耀"/>
      </mu-select-field>
    </div>
    <Uploader v-on:uploaded="showSuccess"/>
  </div>
</template>
<style>
  .upload-wraper{
    margin: 20px auto;
    width: 860px;
    padding: 1em;
    text-align: center;
  }
  .upload-album {
    display: block;
  }
</style>
<script>
  import Uploader from '../editor/upload.vue'
  export default {
    components: {
      Uploader
    },
    methods: {
      showSuccess: function (file) {
        console.log('A file was successfully uploaded')
      }
    }
  }
</script>
