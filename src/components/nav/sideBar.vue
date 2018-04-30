<template>
  <div>
    <div class="menu" @click="toggle()">
      <mu-flat-button
        icon="list"
        primary />
    </div>
    <mu-drawer left :open="open" @close="toggle()">
      <div class="resume" @click="toggle()">
        <div class="avatar">
          <img src="../../assets/avatar.png">
        </div>
      </div>
      <div>
        <mu-list>
          <mu-list-item title="博客" @click="navigate('/posts')">
            <mu-icon value="toys" slot="left"/>
          </mu-list-item>
          <mu-list-item title="相册" @click="navigate('/albums')">
            <mu-icon value="toys" slot="left"/>
          </mu-list-item>
          <mu-list-item title="时间线" @click="navigate('/timeline')">
            <mu-icon value="toys" slot="left"/>
          </mu-list-item>
          
        </mu-list>
      </div>
    </mu-drawer>
  </div>
</template>

<style type="css">
  .menu {
    position: flex;
    z-index: 100;
  }
  
  .resume {
    padding: 1em;
    text-align: center;
    max-height: 20em;
  }
  
  .avatar {
    margin: 1em auto;
    border: 1px;
    border-radius: 50%;
    height: 10em;
    width: 10em;
    overflow: hidden;
  }
</style>

<script>
  export default {
    data: function () {
      return {
        category: [],
        open: false,
      };
    },
    methods: {
      async toggle() {
        if(!this.category.length) {
          await this.$store.dispatch('category');
          this.category = this.$store.getters.getCategory;
        }
        this.open = !this.open;
      },
      close(ref) {

        console.log('Closed: ' + ref);
      },
      whisper() {
        this.$router.push('/posts?cate=whisper'); // @todo microblog
      },
      navigate(path) {
        this.open = !this.open;
        this.$router.push(path);
      }
    },
  }
</script>