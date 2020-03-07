<template>
  <div class="album-wrap" v-loading="loading">
    <div v-for="album in preview" :key="album.id">
      <div class="album-name">
        <router-link :to="{ path: '/albums/' + album.id + '/photos'}">{{album.name}}</router-link>
      </div>
      <vue-preview :slides="album.photos" ></vue-preview>
      <div v-if="album.photoNumber > 10">
        <div class="photo-more">
          <div class="photo-more-text" @click="more(album.id)">查看更多</div>
        </div>
      </div>
    </div>
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
import Pagination from '../components/pagination/general.vue';
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
      imageDomain: config.imageDomain,
      loading: false,
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
    this.loading = true
    await this.loadAlbums();
    const data = this.$store.state.album.albums;
    const { domain } = data;
    this.imageDomain = domain;
    this.loading = false
  },
  components: {
    Pagination,
  },
  computed: {
    preview: function() {
      const data = [];
      for (const album of this.albums) {
        const item = Object.assign({}, album);
        if (Array.isArray(album.photos)) {
          item.photos = item.photos.map(photo => {
            if (photo) {
              let attr = {};
              try {
                attr = JSON.parse(photo.attrs)
              } catch(err) {
                console.log(err.message);
              }

              const preview = {
                src: '//' + this.imageDomain + photo.url,
                msrc: '//' + this.imageDomain + photo.url + '!thumb',
                alt: photo.name,
                title: photo.name,
                w: Number(attr.width) || 400,
                h: Number(attr.height) || 600,
              };
              return preview;
            }
          });
        }
        data.push(item);
      }

      return data;
    }
  }
}
</script>
