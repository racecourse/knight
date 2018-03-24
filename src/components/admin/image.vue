<template>
  <div class="image-grid">
    <div class="img-box">
      <img src="http://pic.yupoo.com/craber_v/7db3c4f9/9305776c.jpeg">
      <div class="img-date">2018-03-02 12:00:00</div>
      <div class="img-action">
        <div class="img-select">
          <mu-checkbox class="img-checkbox"/>
        </div>
        <div class="img-info">
          <span >name</span>
        </div>
      </div>
    </div>
    <div class="img-box">
      <img src="http://pic.yupoo.com/craber_v/1961fda4/7436b084.jpeg">
      <div class="img-date">2018-03-02 12:00:00</div>
      <div class="img-action">
        <div class="img-select">
          <mu-checkbox class="img-checkbox"/>
        </div>
        <div class="img-info">
          <span >name</span>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    
  }
</script>
<style lang="css">
  .image-grid {
    margin: 20px;
    padding: 20px;
  }
  .img-box {
    width: 280px;
    height: 250px;
    overflow: hidden;
    border: 1px solid#f2f2f2;
    display: inline-block;
    text-align: center;
  }
  .img-box img {
    height:200px;
    overflow: hidden;
    z-index: 1;
  }
  .img-date {
    position: relative;
    margin-top: -32px;
    height: 24px;
    width: 100%;
    color: rgba(29, 28, 28, 0.5);
    z-index: 10;
  }
  .img-box .img-action {
    text-align: left;
    padding: 5px 10px;
    display: block;
    border-top: 1px dashed #b2b2b2;
  }
  .img-box .img-select {
    display: inline;
  }
  .img-select .mu-checkbox {
    height: 20px;
    line-height: 20px;
    vertical-align: middle;
  }
  .img-box .img-info {
    display: inline;
    text-align: left;
  }
  .img-info span {
    padding: 5px;
  }
  .image-grid div[class*="col-"] {
    background: #fff;
    text-align: center;
    color: #000;
    border: 1px solid #ddd;
    padding: 8px;
    margin-bottom: 8px;
  }
</style>