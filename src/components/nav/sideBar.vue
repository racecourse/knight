<template>
  <div class="side">
    <div class="menu" @click="toggle()">
      <mu-button flat primary>
        <mu-icon right value="list"></mu-icon>
      </mu-button>
    </div>
    <mu-drawer left :open="open" @close="toggle()" width="400">
      <div class="resume" @click="toggle()">
        <div class="avatar">
          <img src="../../assets/avatar.png">
        </div>
      </div>
      <div>
<!--        <mu-list>-->
<!--          <mu-sub-header inset>no close button</mu-sub-header>-->
<!--          <mu-list-item avatar button :ripple="false">-->
<!--            <mu-list-item-action>-->
<!--              <mu-avatar>-->
<!--                <mu-icon value="toys"></mu-icon>-->
<!--              </mu-avatar>-->
<!--            </mu-list-item-action>-->
<!--            <mu-list-item-content @click="navigate('/posts')">-->
<!--              <mu-list-item-title >Article</mu-list-item-title>-->
<!--            </mu-list-item-content>-->
<!--          </mu-list-item>-->
<!--          <mu-list-item avatar button :ripple="false">-->
<!--            <mu-list-item-action>-->
<!--              <mu-avatar>-->
<!--                <mu-icon value="toys"></mu-icon>-->
<!--              </mu-avatar>-->
<!--            </mu-list-item-action>-->
<!--            <mu-list-item-content @click="navigate('/albums')">-->
<!--              <mu-list-item-title >Gallery</mu-list-item-title>-->
<!--            </mu-list-item-content>-->
<!--          </mu-list-item>-->
<!--          <mu-list-item avatar button :ripple="false">-->
<!--            <mu-list-item-action>-->
<!--              <mu-icon value="gallery" color="pink"></mu-icon>-->
<!--            </mu-list-item-action>-->
<!--            <mu-list-item-title>Mike Li</mu-list-item-title>-->
<!--            <mu-list-item-action>-->
<!--              <mu-avatar>-->
<!--               L-->
<!--              </mu-avatar>-->
<!--            </mu-list-item-action>-->
<!--          </mu-list-item>-->
<!--        </mu-list>-->
        <mu-list>
          <mu-list-item avatar button :ripple="false">
            <mu-list-item-action>
              <mu-icon value="star" color="pink"></mu-icon>
            </mu-list-item-action>
            <mu-list-item-title>Mike Li</mu-list-item-title>
            <mu-list-item-action>
              <mu-avatar>
              </mu-avatar>
            </mu-list-item-action>
          </mu-list-item>
          <mu-list-item avatar button :ripple="false">
            <mu-list-item-action></mu-list-item-action>
            <mu-list-item-title>Maco Mai</mu-list-item-title>
            <mu-list-item-action>
              <mu-avatar>
              </mu-avatar>
            </mu-list-item-action>
          </mu-list-item>
          <mu-list-item avatar button :ripple="false">
            <mu-list-item-action></mu-list-item-action>
            <mu-list-item-title>Alex Qin</mu-list-item-title>
            <mu-list-item-action>
              <mu-avatar>
              </mu-avatar>
            </mu-list-item-action>
          </mu-list-item>
          <mu-list-item avatar button :ripple="false">
            <mu-list-item-action></mu-list-item-action>
            <mu-list-item-title>Allen Qun</mu-list-item-title>
            <mu-list-item-action>
              <mu-avatar>
              </mu-avatar>
            </mu-list-item-action>
          </mu-list-item>
        </mu-list>
      </div>
    </mu-drawer>
  </div>
</template>

<style type="css">
  .menu {
    z-index: 100;
    display: inline;
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
