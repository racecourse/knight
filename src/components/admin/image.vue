<template>
  <div class="image-grid">
    <div class="img-box" v-for="(photo, index) in photos" :key="index">
      <img :src="'//' + imageDomain + photo.url">
      <div class="img-date">
        {{new Date(photo.created * 1000).toLocaleDateString()}}</div>
      <div class="img-action">
        <div class="img-select">
          <mu-checkbox class="img-checkbox"/>
        </div>
        <div class="img-info">
          <span >{{photo.name}}</span>
        </div>
      </div>
    </div>
    <div class="a-page">
      <Pagination
        :total="total"
        :list="photos"
        :current="page"
        :pageSize="pageSize"
        @query="loadPhotos"
      />
    </div>
  </div>
</template>
<script>
import Pagination from '../pagination/general.vue'
import config from '../../config'
export default {
  data() {
    return {
      total: 0,
      page: 1,
      pageSize: 21,
      imageDomain: config.imageDomain,
      photos: [],
    }
  },
  async beforeMount() {
    await this.loadPhotos();
  },
  components: {
    Pagination,
  },
  methods: {
    async loadPhotos() {
      const page = this.$route.query.page || 1;
      await this.$store.dispatch('photos', { page });
      const photos = this.$store.getters.getPhoto;
      this.photos = photos.list;
      this.total = Number(photos.total) || 0;
      this.page = Number(photos.page) || 1;
      this.pageSize = Number(photos.pageSize) || 0;
    }
  }
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
