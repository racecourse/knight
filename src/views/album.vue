<template>
  <div class="album-wrap">
    <div class="album-name">
      {{album.name}}
    </div>
    <mu-row>
      <mu-col width="100"
        tablet="50"
        desktop="50"
        v-for="(photo, index) in picture"
        :key="index"
      >
        <div class="image-box">
          <div class="image-cover">
            <div v-if="photo.panorama">
              <img class="panorama-thumb" :src="'https://' + imageDomain + photo.url + '!thumb'" />
              <div class="panorama-zoom"  @click="showPanorama(photo)">
                全景图片点击观看
              </div>
            </div>
            <div v-else>
              <vue-preview :slides="photo.preview" ></vue-preview>
            </div>
          </div>
          <div class="image-title">{{photo.name}}</div>
        </div>
      </mu-col>

    </mu-row>
    <div v-if="total > 1">
      <div class="photo-load-more">
        <div class="photo-load-text" @click="more">查看更多</div>
      </div>
    </div>
    <mu-dialog class="panoram-dialog"
      :open="show"
      :title="panorama.title"
      @close="closePanorama"
      bodyClass="dialog-body"
    >
      <mu-button flat label="close" slot="actions"  @click="closePanorama"/>
      <Panorama :panorama="panorama.url"></Panorama>
    </mu-dialog>

  </div>
</template>

<script>
import Panorama from "../components/panorama/index.vue";
import fecha from "fecha";
import path from "path";
import config from '../config'
export default {
  data() {
    return {
      panorama: {},
      album: {},
      show: false,
      photos: [],
      page: 1,
      pageSize: 20,
      total: 0,
      last: 0,
      imageDomain: config.imageDomain
    };
  },
  methods: {
    showPanorama(photo) {
      this.panorama = photo;
      this.show = true;
    },
    closePanorama() {
      this.show = false;
    },
    async more() {
      let page = this.$route.query.page || 1;
      let pageSize = this.pageSize;
      const albumId = this.$route.params.albumId;
      page++;
      if (page * pageSize >= this.total) {
        return;
      }

      const params = {
        page,
        pageSize,
        albumId,
      };
      const last = this.photos[pageSize - 1];
      if (last) {
        params.last = last.id;
      }

      await this.$store.dispatch('albumPhotos', params);
      const data = this.$store.state.album.photos;
      const { list, total } = data;
      if (list.length > 0) {
        this.photos = this.photos.concat(list);
      }

      this.total = total;
      this.page = data.page;
      this.pageSize = data.pageSize;
      // @todo 改变 query page， pageSize
    },
  },
  async beforeMount() {
    const page = this.$route.query.page || 1;
    const pageSize = this.$route.query.pageSize || 20;
    const albumId = this.$route.params.albumId;
    await this.$store.dispatch('albumPhotos', { page, pageSize, albumId });
    const data = this.$store.state.album.photos;
    const { list, total } = data;
    this.photos = list;
    this.total = total;
    this.page = data.page;
    this.pageSize = data.pageSize;
  },
  components: {
    Panorama
  },
  computed: {
    picture: function() {
      const data = [];
      for (const photo of this.photos) {
        if (!photo) {
          continue;
        }

        let attr = {};
        try {
          attr = JSON.parse(photo.attrs);
        } catch (err) {
          console.log(err.message);
        }

        const preview = {
          src: "//" + config.imageDomain + photo.url,
          msrc: "//" + config.imageDomain + photo.url + "!thumb",
          alt: photo.name,
          title: photo.name,
          w: Number(attr.width) || 400,
          h: Number(attr.height) || 600
        };
        photo.preview = [preview];
        data.push(photo);
      }

      return data;
    }
  }
};
</script>
<style>
 @import '../assets/album.css';

.photo-load-more {
  position: relative;
  margin-top: 1rem;
  text-align: center;
  color: #999999;
  padding: 2rem;
  width: 100%;
  font-size: 1.2rem;
  display: block;
}
.photo-load-text {
  position: absolute;
  top: 50%;
  left: 25%;
  width: 50%;
  padding: 20px;
  text-align: center;
  transform: translateY(-50%);
  cursor: pointer;
}
</style>

