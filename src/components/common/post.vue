<template>
  <div class="container">
    <SideBar></SideBar>
      <div class="header">
        <ul class="nav">
          <li><router-link to="/">home</router-link></li>
          <li><router-link to="/archive">archive</router-link></li>
          <li><a href="//github.com/racecourse/knight">github</a></li>
          <li><router-link to="/">about</router-link></li>
        </ul>
        <div class="search">
          <!-- <input class="filter" id="keyword" type="text" name="search" placeholder="keyword"> -->
          <mu-text-field class="filter" hintText="keyword"/>
          <mu-icon-button icon="search" @click="search"/>
        </div>
      </div>
      <mu-row>
        <mu-col width="100" tablet="95" desktop="70" class="content-warp">
        <div>
          <transition>
            <router-view></router-view>
          </transition>
        </div>  
      </mu-col>
    </mu-row>
    <Bottom></Bottom>
  </div>
</template>
<style>
  .container {
    height: auto;
    min-height: 100%;
    padding-bottom: 0;
  }
  .col {
    margin: 0 auto;
  }
  .header {
    position: relative;
    margin: 0 auto;
    padding: 1em;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 10em;
    /* background: #00aaaa; */
    overflow: hidden;
    border: 1em solid#ffffff;
  }

  .header ul {
    margin: 0;
    padding: 0;
    list-style-type: none;
  }

  .header .nav {
    display: inline;
  }

  .header li {
    text-align: -webkit-match-parent;
    margin: 1em;
    padding: 1rem;
    display: inline;
    cursor: pointer;
  }

  .header li:hover {
    border-bottom: 2px dashed #0F769F;
  }
  .header .search {
    height: 2em;
  }

  .header .filter {
    margin-left: 5em;
    border: 0;
    padding-left: 5px;
    height: 32px;
    width: 10em;
    -ms-flex: 1;
    -webkit-box-flex: 1;
    flex: 1;
    border: none;
    line-height: 24px;
  }
  .search-btn {
    cursor: pointer;
  }
</style>
<script>
  import SideBar from '../nav/sideBar.vue';
  import Bottom from './bottom.vue';
  export default{
    components: {
      SideBar,
      Bottom,
    },
    methods: {
      search() {
        const keyword = document.getElementById('keyword').value;
        alert('search api is close');
      }
    }
  }
</script>
