<template>
  <div class="common-bottom">
    <div class="copyright">
      <mu-badge >
        <span style="padding: 10px">copyright &nbsp;2017-2020</span>
      </mu-badge>
      <span style="padding-left:1rem">powered by <a href="//github.com/eclogue">eclogue</a></span>
      <a href="//github.com/superbogy/knight">
        <img class="repo" src="./github.png">
      </a>
      <div>
        <div>备案号:<span>浙ICP备18031483号-1</span></div>
      </div>
    </div>
  </div>
</template>
<style>
  .common-bottom {
    bottom:0;
    width: 100%;
    text-align: center;
    /* background-color: #ffffff; */
    color: #b2b2b2;
  }

  .common-bottom .mu-badge{
    background-color: inherit;
    color: inherit;
  }
  .copyright {
    font-weight: 200;
    padding: 12PX;
  }
  .repo {
    margin-left: 1em;
  }
</style>
<script>
  export default {
    data: function() {
      return {
        now: new Date().getFullYear(),
      }
    }
  }
</script>
