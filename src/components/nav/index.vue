<template>
  <div>
    <mu-drawer left docked :open="open" @close="toggle()">
      <mu-appbar title="knight"/>
      <div>
        <mu-list>
          <mu-list-item to="/admin/dashboard">
            <mu-icon>create</mu-icon>
            <span>dashboard</span>
          </mu-list-item>
          <mu-list-item to="/admin/create">
            <mu-icon>create</mu-icon>
            <span>写文章</span>
          </mu-list-item>
          <mu-list-item to="/admin/article">
            <mu-icon>sort</mu-icon>
            <span>文章管理</span>
          </mu-list-item>
          <mu-list-item to="/admin/comment">
            <mu-icon>sort</mu-icon>
            <span>评论管理</span>
          </mu-list-item>
          <mu-list-item>
            <mu-icon>perm_media</mu-icon>
            <span>图片管理</span>
          </mu-list-item>
          <mu-list-item to="/admin/upload">
            <mu-icon>photo</mu-icon>
            <span>上传图片</span>
          </mu-list-item>
          <mu-list-item>
            <mu-icon>settings</mu-icon>
            <span>设置</span>
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