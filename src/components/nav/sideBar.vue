<template>
  <div>
    <div class="menu">
      <mu-icon-button icon="menu" @click="toggle()"/>
    </div>
    <mu-drawer left :open="open" @close="toggle()">
      <div class="resume" @click="toggle()">
        <div class="avatar">
          <img src="../../assets/avatar.png">
        </div>
      </div>
      <div>
        <mu-list>
          <mu-list-item title="桑下语">
            <mu-icon value="gesture" slot="left"/>
          </mu-list-item>
          <mu-list-item title="如是我闻">
            <mu-icon value="toys" slot="left"/>
          </mu-list-item>
        </mu-list>
      </div>
    </mu-drawer>
  </div>
</template>

<style type="css">
  .menu {
    position: fixed;
    left: 1em;
    top: 1em;
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
    async beforeMount() {

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
      }
    },
  }
</script>