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
                <img class="panorama-thumb" :src="photo.url" />
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
          <div v-if="album.photoNumber > 5">
            <div class="photo-more">
              <div class="photo-more-text">查看更多</div>
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
                msrc: '//' + photo.url,
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
  .panoram-dialog {
    width: calc(100% - 100px)
  }
  @media screen and (max-width: 768px) {
    .mu-dialog {
      position: absolute;
      top: 20px;
      width: calc(100% - 50px);
    }
  }
  
  .photo-more {
    position: relative;
    background: #8aaf8a0c;
    text-align: center;
    color: #999999;
    height: 200px;
    font-size: 1.2rem;
  }
  .photo-more-text {
    position: absolute;
    top: 50%;
    left: 25%;
    width: 50%;
    padding: 10px;
    border: 1px solid#b2b2b2;
    border-radius: 2px;
    text-align: center;
    
    transform: translateY(-50%);
  }
</style>

