<template>
  <div class="common-bottom">
    <div class="copyright">
      <mu-badge >
        <span style="padding: 10px">copyright &nbsp;2017-2018</span>
        <span style="font-size: 20px;" slot="content">&copy;</span>
      </mu-badge>
      <span style="padding-left:1rem">powered by <a href="//github.com/eclogue">eclogue</a></span>
      <a href="//github.com/eclogue/knight">
        <img class="repo" src="./github.png">
      </a>
      <div>
        <div>备案号:<span>这家伙比较懒没有备案</span></div>
      </div>
    </div>
  </div>
</template>
<style>
  .common-bottom {
    /* position: absolute; */
    bottom:0;
    width: 100%;
    padding: 2em;
    text-align: center;
    background-color: #ffffff;
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