<template>
  <div class="album-wrap">
    <div v-for="album in preview" :key="album.id">
      <div class="album-name">{{album.name}}</div>
      <mu-row>
        <mu-col width="100" tablet="50" desktop="33"
          v-for="photo in album.photos"  :key="photo.id">
          <div class="image-box">
            <div class="image-cover">
              <div v-if="photo.panorama">
                <img class="panorama-thumb" :src="photo.url" />
                <div class="panorama-zoom"  v-on:click="showPanorama(photo)">
                  全景图片点击观看
                </div>
              </div>
              <div v-else>
                <vue-preview :slides="photo.preview" @close="handleClose"></vue-preview>
              </div>
            </div>
            <div class="image-title">{{photo.name}}</div>
          </div>
        </mu-col>
      </mu-row>
      <mu-dialog :open="dialog" :title="panorama.name" @close="closePanorama">
        <Panorama :panorama="panorama.url" :title="panorama.name"></Panorama>
        <mu-flat-button label="close" slot="actions" primary @click="closePanorama"/>
      </mu-dialog>
    </div>
  </div>
</template>

<script>
import Panorama from '../components/panorama/index.vue';
export default {
  data() {
    return {
      albums: [],
      panorama: '',
      dialog: false,
      page: 1,
      pageSize: 20
    }
  },
  methods: {
    handleClose () {
      console.log('close event')
    },
    showPanorama(photo) {
      this.panorama = photo;
      this.dialog = true;
    },
    closePanorama() {
      this.dialog = false;
    }
  },
  async beforeMount() {
    const page = this.$route.query.page || 1;
    const pageSize = this.$route.query.pageSize || 1
    await this.$store.dispatch('albums', { page, pageSize });
    const data = this.$store.state.album.albums;
    const { list } = data;
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
                msrc: '//' + photo.url,
                alt: photo.name,
                title: photo.name,
                w: Number(attr.width) || 400,
                h: Number(attr.height) || 600,
              };
              photo.preview = [preview];
              console.log(photo);
              return photo;
            }
          });
          data.push(item);
        }
      }

      console.log('xxx', data);
      return data;
    }
  }
}
</script>
<style>
  .album-wrap {
    margin: 0 auto;
    padding: 20px;
    background-color: #58c5580c;
    max-width: 860px;
    padding: 0 15px;
    min-height: 100%;
  }
  .album-name {
    margin: 2em 0;
    padding-left: 20px;
    border-left: 3px solid #9fdb93;
    background: #f9f7f7;
  }
  .image-box {
    /* background: #ffffff; */
    padding: 5px;
    height: 240px;
    /* border: 1px solid #f2f2f2; */
  }
  .image-cover {
    height: 200px;
    width: 100%;
    overflow: hidden;
  }
  .image-cover img {
    width: 100%;
  }

  .image-cover figure {
    -webkit-margin-before: 0.1em;
    -webkit-margin-after: 0.1em;
    -webkit-margin-start: 10px;
    -webkit-margin-end: 10px;
  }
  .image-title {
    font-size: 12px;
    text-align: center;
  }
  .panorama-zoom {
    /* height: 200px; */
    position: absolute;
    margin-top: -100px;
    z-index: 10;
    width: 250px;
    text-align: center;
    cursor: pointer;
  }
  .panorama-thumb {
    height: 200px;
    z-index: -1;
    -webkit-filter: blur(2px); filter: blur(2px);
  }
</style>

