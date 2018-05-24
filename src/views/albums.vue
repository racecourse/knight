<template>
  <div class="album-wrap">
    <div v-for="album in preview" :key="album.id">
      <div class="album-name">
        {{album.name}}
      </div>
      <mu-row>
        <mu-col width="100"
          tablet="50"
          desktop="33"
          v-for="(photo, index) in album.photos"
          :key="index"
        >
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
        </mu-col>

        <mu-col width="100"
          tablet="50"
          desktop="33"
        >
          <div v-if="album.photoNumber > 10">
            <div class="photo-more">
              <div class="photo-more-text" @click="more(album.id)">查看更多</div>
            </div>
          </div>
        </mu-col>
      </mu-row>
    </div>
    <mu-dialog class="panoram-dialog"
      :open="show"
      :title="panorama.title"
      @close="closePanorama"
      bodyClass="dialog-body"
    >
      <mu-flat-button label="close" slot="actions"  @click="closePanorama"/>
      <Panorama :panorama="panorama.url"></Panorama>
    </mu-dialog>
  </div>
</template>

<script>
import Panorama from '../components/panorama/index.vue';
import fecha from 'fecha';
import path from 'path';
export default {
  data() {
    return {
      albums: [],
      panorama: {},
      show: false,
      page: 1,
      pageSize: 20
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
    }
  },
  async beforeMount() {
    const page = this.$route.query.page || 1;
    const pageSize = this.$route.query.pageSize || 1
    await this.$store.dispatch('albums', { page, pageSize });
    const data = this.$store.state.album.albums;
    const { list, total } = data;
    this.albums = list;
  },
  components: {
    Panorama,
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
                attr = JSON.parse(photo.attr)
              } catch(err) {
                console.log(err.message);
              }

              const preview = {
                src: '//' + photo.url,
                msrc: '//' + photo.url + '!thumb',
                alt: photo.name,
                title: photo.name,
                w: Number(attr.width) || 400,
                h: Number(attr.height) || 600,
              };
              photo.preview = [preview];
              return photo;
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
<style>
 @import '../assets/album.css';
</style>

