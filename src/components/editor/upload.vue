<template>
  <div>
    <button class="UppyModalOpenerBtn" style="display: none;">
      upload
    </button>
    <div class="DashboardContainer"></div>
    <div class="upload-form"></div>
  </div> 
</template>
<style>
  @import "~uppy/dist/uppy.min.css";
  .UppyDragDrop-Progress {
    position: relative;
  }
</style>
<script>
  const Uppy = require("uppy/lib/core");
  const Dashboard = require("uppy/lib/plugins/Dashboard");
  const Webcam = require("uppy/lib/plugins/Webcam");
  const XHRUpload = require("uppy/lib/plugins/XHRUpload");

  import config from "../../config";

  export default {
    props: {
      notify: {
        type: Function,
        required: false,
        default: function () {
          return console.log;
        }
      },
      album: {
        type: String,
        required: false,
        default: 1,
      }
    },
    data() {
      return {
        images: []
      };
    },
    mounted() {
      console.log('uploaddddddder----+', this.album)
      const uppy = Uppy({
        debug: true,
        autoProceed: false,
        onBeforeUpload: (files) => {
          if (Number(this.album) < 1) {
            return Promise.reject('none of album be choosed')
          }

          uppy.setMeta({ album: this.album })

        },
        restrictions: {
          maxFileSize: 100000000,
          maxNumberOfFiles: 50,
          minNumberOfFiles: 1,
          allowedFileTypes: ["image/*", "video/*"]
        }
      })
        .use(Dashboard, {
          trigger: ".UppyModalOpenerBtn",
          inline: true,
          target: ".DashboardContainer",
          replaceTargetContent: true,
          note: "Images and video only, 1–50 files, up to 10 MB",
          maxHeight: 450,
          // metaFields: [
          //   { id: "license", name: "License", placeholder: "specify license" },
          //   {
          //     id: "caption",
          //     name: "Caption",
          //     placeholder: "describe what the image is about"
          //   }
          // ]
        })
        .use(Webcam, { target: Dashboard })
      const self = this;
      uppy.use(XHRUpload, {
          endpoint: 'http://' + config.api + '/photos',
          getResponseData(xhr) {
            let image = [];
            if (xhr.status === 200) {
              const response = JSON.parse(xhr.response);
              image = response.data;
              self.images = image;
            }

            return image;
          }
        })
        .run();
      uppy.on("complete", function (result) {
        console.log("successful files:", result.successful);
        console.log("failed files:", result.failed);
      });
    }
  };
</script>
