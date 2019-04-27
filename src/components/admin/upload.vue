<template>
  <div class="upload-wraper">
    <div class="create-album">
      <div class="create-btn" @click="showCreate">create</div>
    </div>
    <div class="upload-album">
      <select v-model="currentAlbum" class="album-select">
        <option v-for="album in albums" :key="album.id" :value="album.id">{{album.name}}</option>
      </select>
    </div>
    <Uploader v-on:uploaded="showSuccess" :album="currentAlbum"/>
    <mu-dialog :open="dialog" title="创建相册" @close="close">
      <div class="create-box">
        <div class="create-item">
          <mu-text-field hintText="相册名" v-model="albumName"/>
        </div>
        <div class="create-item">
          <mu-text-field hintText="描述"
          v-model="albumDetail"
          multiLine
          :rows="3"
          :rowsMax="6"/>
        </div>
        <div class="create-item">
          <mu-switch label="是否可见" v-model="albumShow" />
        </div>
      </div>
      <mu-button flat slot="actions" @click="showCreate" primary label="取消"/>
      <mu-button flat slot="actions" primary @click="createAlbum" label="确定"/>
    </mu-dialog>
  </div>
</template>
<style>
  .upload-wraper{
    margin: 20px auto;
    width: 860px;
    padding: 1em;
  }
  .upload-album {
    float: right;
    margin-right: 5rem;
  }
  .create-album {
    display: inline-block;
  }
  .create-btn {
    vertical-align: middle;
    padding: 2px 15px;
    border: 1px solid #b2b2b2;
    border-radius: 5px;
    margin-bottom: 10px;
  }
  .create-item {
    display: block;
  }
  .create-box {
    text-align: center;
  }
  .album-select {
    appearance: none;
    -moz-appearance: none;
    -webkit-appearance: none;
    border: solid 1px #b2b2b2;
    padding: 1px 10px;
    margin-bottom: 10px;
  }

</style>
<script>
  import Uploader from '../editor/upload.vue'
  export default {
    data() {
      return {
        dialog: false,
        albums: [],
        albumName: '',
        albumDetail: '',
        albumShow: true,
        currentAlbum: '0',
      }
    },
    components: {
      Uploader
    },
    async mounted() {
      await this.$store.dispatch('albumNames');
      const albums = this.$store.state.album.albums;
      this.loadAlbum(albums.list);
    },
    methods: {
      showSuccess: function (file) {
        console.log('A file was successfully uploaded')
      },
      loadAlbum (albums) {
        this.albums = [{ id: "0", name: '--请选出相册--'}].concat(albums);
        console.log(this.albums);
      },
      close() {
        this.dialog = !this.dialog;
      },
      showCreate() {
        this.dialog = !this.dialog;
      },
      chooseAlbum() {
        console.log(this.currentAlbum);
      },
      async createAlbum() {
        const name = this.albumName;
        const isShow = this.albumShow;
        const detail = this.albumDetail;
        const data = {
          name,
          isShow,
          detail,
        }
        await this.$store.dispatch('createAlbum', data);
        await this.$store.dispatch('albumNames');

        const albums = this.$store.state.album.albums;
        this.currentAlbum = albums.list[0].id; // @todo
        this.loadAlbum(albums.list);
        this.dialog = !this.dialog;
      }
    }
  }
</script>
