<template>
  <div>
    <mu-drawer left docked :open="open" @close="toggle()">
      <mu-appbar title="knight" />
      <div>
        <mu-list>
          <mu-list-item to="/admin/dashboard">
            <mu-icon>create</mu-icon>
            <span>dashboard</span>
          </mu-list-item>
          <mu-list-item to="/admin/create">
            <mu-icon>create</mu-icon>
            <span>write</span>
          </mu-list-item>
          <mu-list-item to="/admin/article">
            <mu-icon>sort</mu-icon>
            <span>article</span>
          </mu-list-item>
          <mu-list-item to="/admin/comment">
            <mu-icon>sort</mu-icon>
            <span>comment</span>
          </mu-list-item>
          <mu-list-item to="/admin/image">
            <mu-icon>perm_media</mu-icon>
            <span>image manage</span>
          </mu-list-item>
          <mu-list-item to="/admin/upload">
            <mu-icon>photo</mu-icon>
            <span>upload</span>
          </mu-list-item>
          <mu-list-item>
            <mu-icon>settings</mu-icon>
            <span>setting</span>
          </mu-list-item>
          <mu-list-item to="/">
            <mu-icon>home</mu-icon>
            <span>home</span>
          </mu-list-item>
        </mu-list>
      </div>
    </mu-drawer>
  </div>
</template>
<style>
  .side-bg {
    background: rgba(50,50,0, 0.5);
  }
  .mu-drawer {
    max-width: 200px;
  }
</style>
<script>
  export default {
    data () {
      return {
        open: true,
      }
    },
    methods: {
      close() {
        console.log('Opened: ');
      },
      toggle() {
        this.open = !this.open;
      },
    },
    created() {
    }
  }

</script>
