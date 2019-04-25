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
  .UppyDragDrop-Progress {
    position: relative;
  }
</style>
<script>
  const Webcam = require("@uppy/webcam");
  const XHRUpload = require("@uppy/xhr-upload");
  const Uppy = require('@uppy/core')
  const Dashboard = require('@uppy/dashboard')
  import config from "../../config";
  import Storage from '../../util/storage';

  export default {
    props: {
      uploaded: {
        type: Function,
        required: false,
        default: function () {
          return console.log;
        }
      },
      album: {
        type: Number,
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
      const storage = new Storage();
      const token = storage.getItem('token');
      if (!token) {
        return this.$router.push('/login');
      }

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
          // ]
        })
        .use(Webcam, { target: Dashboard })
      const self = this;
      uppy.use(XHRUpload, {
          endpoint: 'http://' + config.api + '/admin/photos',
          headers: {
            Authorization: 'Bearer ' + token
          },
          getResponseData(xhr) {
            let image = [];
            console.log(xhr)
            if (xhr.status === 200) {
              const response = JSON.parse(xhr.response);
              image = response.data;
              self.images = image;
              self.uploaded(image)
            }

            return image;
          }
        })
      uppy.on('upload-success', (file, body) => {
        console.log(file, body)
      })   
      uppy.upload().then((result) => {
        console.info('Successful uploads:', result.successful)

  if (result.failed.length > 0) {
  console.error('Errors:')
  result.failed.forEach((file) => {
      console.error(file.error)
    })
  }
})
      uppy.on("complete", function (result) {
        console.log("successful files:", result.successful);
        console.log("failed files:", result.failed);
      });
    }
  };
</script>
