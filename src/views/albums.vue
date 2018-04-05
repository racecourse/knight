<template>
  <div class="album-wrap">
    <div v-for="album in preview" :key="album.id">
      <div class="album-name">{{album.name}}</div>
      <mu-row gutter>
        <mu-col width="100" tablet="50" desktop="33"
          v-for="photo in album.photos"  :key="photo.id">
          <div class="image-box">
            <div class="image-cover">
              <vue-preview :slides="photo.preview" @close="handleClose"></vue-preview>
            </div>
            <div class="image-title">{{photo.name}}</div>
          </div>
        </mu-col>
      </mu-row>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      albums: [],
      page: 1,
      pageSize: 20
    }
  },
  methods: {
    handleClose () {
      console.log('close event')
    },
  },
  async beforeMount() {
    const page = this.$route.query.page || 1;
    const pageSize = this.$route.query.pageSize || 1
    await this.$store.dispatch('albums', { page, pageSize });
    const data = this.$store.state.album.albums;
    const { list } = data;
    this.albums = list;
  },
  computed: {
    preview: function() {
      const data = [];
      for (const album of this.albums) {
        console.log(album);
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
  
</style>

