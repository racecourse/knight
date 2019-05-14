<template>
  <div>
    <mu-drawer left docked :open="open" @close="toggle()">
      <mu-appbar title="knight" />
      <div>
        <mu-list>
          <mu-list-item to="/admin/dashboard">
            <mu-list-item-action><mu-icon value="dashboard"></mu-icon></mu-list-item-action>
            <mu-list-item-title>dashboard</mu-list-item-title>
          </mu-list-item>
          <mu-list-item to="/admin/create">
            <mu-list-item-action><mu-icon value="create"></mu-icon></mu-list-item-action>
            <mu-list-item-title>write</mu-list-item-title>
          </mu-list-item>
          <mu-list-item to="/admin/article">
            <mu-list-item-action><mu-icon value="sort"></mu-icon></mu-list-item-action>
            <mu-list-item-title>article</mu-list-item-title>
          </mu-list-item>
          <mu-list-item to="/admin/comment">
            <mu-list-item-action><mu-icon value="flip_to_front"></mu-icon></mu-list-item-action>
            <mu-list-item-title>comment</mu-list-item-title>
          </mu-list-item>
          <mu-list-item to="/admin/image">
            <mu-list-item-action><mu-icon value="perm_media"></mu-icon></mu-list-item-action>
            <mu-list-item-title>image manage</mu-list-item-title>
          </mu-list-item>
          <mu-list-item to="/admin/upload">
            <mu-list-item-action><mu-icon value="cloud_upload"></mu-icon></mu-list-item-action>
            <mu-list-item-title>upload</mu-list-item-title>
          </mu-list-item>
          <mu-list-item>
            <mu-list-item-action><mu-icon value="settings">=</mu-icon></mu-list-item-action>
            <mu-list-item-title>setting</mu-list-item-title>
          </mu-list-item>
          <mu-list-item to="/">
            <mu-list-item-action><mu-icon value="home">=</mu-icon></mu-list-item-action>
            <mu-list-item-title>home</mu-list-item-title>
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
