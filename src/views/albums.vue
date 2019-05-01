<template>
  <div class="album-wrap">
    <div v-for="album in preview" :key="album.id">
      <div class="album-name">
        {{album.name}}
      </div>
      <vue-preview :slides="album.photos" ></vue-preview>
      <div v-if="album.photoNumber > 10">
        <div class="photo-more">
          <div class="photo-more-text" @click="more(album.id)">查看更多</div>
        </div>
      </div>
      <!-- <mu-grid-list>
        <div v-for="(photo, index) in album.photos" :key="index">
          <div class="image-box">
            <div class="image-cover">
              <div v-if="photo.panorama">
                <img class="panorama-thumb" :src="'http://' + photo.url + '!thumb'" />
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
        </div>
        <div>
          <div v-if="album.photoNumber > 10">
            <div class="photo-more">
              <div class="photo-more-text" @click="more(album.id)">查看更多</div>
            </div>
          </div>
        </div>
      </mu-grid-list> -->
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
    <div class="a-page" v-if="total > pageSize">
      <Pagination :total="total"
       :current="page"
       :pageSize="pageSize"
       @query="loadAlbums"
      />
    </div>

  </div>
</template>
<style>
 @import '../assets/album.css';
</style>

<script>
import Panorama from '../components/panorama/index.vue';
import Pagination from '../components/pagination/general.vue';
import fecha from 'fecha';
import path from 'path';
import config from '../config'
export default {
  data() {
    return {
      albums: [],
      panorama: {},
      show: false,
      page: 1,
      pageSize: 20,
      total: 0,
      imageDomain: config.imageDomain
    }
  },
  methods: {
    showPanorama(photo) {
      this.panorama = photo;
      this.show = true;
    },
    closePanorama() {
      this.show = false;
    },
    more(id){
      this.$router.push('/albums/' + id + '/photos');
    },
    async loadAlbums() {
      const page = this.$route.query.page || 1;
      const pageSize = this.$route.query.pageSize || 20;
      await this.$store.dispatch('albums', { page, pageSize });
      const data = this.$store.state.album.albums;
      const { list, total} = data;
      this.albums = list;
      this.total = total;
      this.page = data.page;
    }
  },
  async beforeMount() {
    await this.loadAlbums();
  },
  components: {
    Panorama,
    Pagination,
  },
  computed: {
    preview: function() {
      const data = [];
      for (const album of this.albums) {
        if (Array.isArray(album.photos)) {
          const item = Object.assign({}, album);
          item.photos = item.photos.map(photo => {
            if (photo) {
              let attr = {};
              try {
                attr = JSON.parse(photo.attrs)
              } catch(err) {
                console.log(err.message);
              }

              const preview = {
                src: '//' + config.imageDomain + photo.url,
                msrc: '//' + config.imageDomain + photo.url + '!thumb',
                alt: photo.name,
                title: photo.name,
                w: Number(attr.width) || 400,
                h: Number(attr.height) || 600,
              };
              return preview;
            }
          });
          data.push(item);
        }
      }
      return data;
    }
  }
}
</script>
