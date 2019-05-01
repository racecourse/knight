<template>
  <div class="sur-wrap">
    <section>
      <div class="sur-desc" v-if="system">
        <div class="sur-title">system</div>
      </div>
      <hr class="sur-divider">
      <div class="sur-desc">
        <div class="sur-title">访问流浪</div>
        <div class="sur-li">昨日 pv: {{survey.pv}}</div>
        <div class="sur-li">访问 ip 数: {{survey.ip}}</div>
      </div>
      <hr class="sur-divider">
      <div class="sur-desc">
        <div class="sur-title">文章概况</div>
        <div class="sur-li">发表文章: {{survey.articleNumber}}</div>
        <div class="sur-li">评论数: {{survey.commentNumber}}</div>
        <div class="sur-li">
          <mu-button icon><mu-icon value="create"></mu-icon></mu-button>
        </div>
      </div>
      <hr class="sur-divider">
      <div class="sur-item">
        <div class="sur-title">图片数</div>
        <div class="sur-li">相册: {{survey.albumNumber}}</div>
        <div class="sur-li">总共上传图片: {{survey.photoNumber}}</div>
        <div class="sur-li">
          <mu-button icon><mu-icon value="cloud_upload"></mu-icon></mu-button>
        </div>
      </div>
      <hr class="sur-divider">
      <div class="sur-item">
        <div class="sur-title">快捷入口</div>
        <mu-button icon><mu-icon value="create"></mu-icon></mu-button>
        <mu-button icon><mu-icon value="sort"></mu-icon></mu-button>
        <mu-button icon><mu-icon value="photo"></mu-icon></mu-button>
      </div>
    </section>
  </div>
</template>
<style>
.sur-wrap {
  position: relative;
  min-height: 100%;
  text-align: left;
  margin-bottom: 5rem;
}

.sur-desc {
  max-width: 972px;
  -webkit-box-pack: justify;
  -webkit-justify-content: space-between;
  -ms-flex-pack: justify;
  justify-content: space-between;
  margin: 2em;
  margin-bottom: 3em;
  margin-top: 3em;
}
.sur-divider {
  margin: 0;
  height: 1px;
  border: none;
  background-color: rgba(0, 0, 0, 0.12);
  width: 100%;
}
.sur-item {
  padding: 1em;
  position: relative;
  display: inline-block;
  text-align: left;
  margin: 2em;
}

.sur-title {
  width: 100%;
  display: inline-block;
  vertical-align: middle;
  margin-right: 1.8em;
  font-size: 1.2em;
  line-height: 1.5em;
  margin-bottom: 1.2em;
}

.sur-li {
  display: inline;
  margin-left: 1em;
  font-weight: 200;
}
.sur-li .pk {
  width: 100px;
  font-size: 1.2em;
  color: rgb(11, 65, 32);
  display: inline-block;
}
.sur-li span {
  padding: 0 8px;
  color: #a4aa6f;
}
hr {
  display: block;
  -webkit-margin-before: 0.5em;
  -webkit-margin-after: 0.5em;
  -webkit-margin-start: auto;
  -webkit-margin-end: auto;
  border-style: inset;
  border-width: 1px;
}
</style>
<script>
export default {
  data: function() {
    return {
      survey: {},
      GB: Math.pow(1024, 3)
    };
  },
  async beforeMount() {
    await this.$store.dispatch("survey");
    const admin = this.$store.state.admin;
    this.survey = admin.survey || {};
    // this.system = this.survey.system;
  },

  computed: {
    system: function() {
      if (this.survey.system) {
        return this.survey.system;
      }

      return {}
    }
  }
};
</script>
