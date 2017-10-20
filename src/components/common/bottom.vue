<template>
  <div class="common-bottom">
    <div class="copyright">
      <span>Copyright (c) 2017-{{now}}</span>
      <span>powered by <a href="//github.com/racecourse">racecourse</a></span>
      <span></span>
      <a href="//github.com/racecourse/knight">
        <img class="repo" src="./github.png">
      </a>
    </div>
  </div>
</template>
<style>
  .common-bottom {
    position: absolute;
    width: 100%;
    padding: 2em;
    text-align: center;
    background-color: #ffffff;
  }
  .common-bottom span {
    margin-left: 1em;
  }
  .copyright {
    font-weight: 200;
  }
  .repo {
    margin-left: 4em;
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