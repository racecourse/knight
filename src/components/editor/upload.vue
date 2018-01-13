<<template>
  <div>
    <button class="UppyModalOpenerBtn" style="display: none;">Open Uppy Dashboard Modal</button>
    <div class="DashboardContainer"></div>
  </div> 
</template>
<style>
  @import '~uppy/dist/uppy.min.css';
  .UppyDragDrop-Progress {
    position: relative;
  }
  .UppyDragDrop-Upload {
    display: block;
    margin: auto;
    padding: 5px 15px;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    border: 0;
    border: 1px solid gray;
    background: none;
    cursor: pointer;
    margin-top: 15px;
  }
  .UppyDragDrop-Upload:hover {
    background: gray;
  }
</style>
<script>
  const Uppy = require('uppy/lib/core')
  const Dashboard = require('uppy/lib/plugins/Dashboard')
  const GoogleDrive = require('uppy/lib/plugins/GoogleDrive')
  const Dropbox = require('uppy/lib/plugins/Dropbox')
  const Instagram = require('uppy/lib/plugins/Instagram')
  const Webcam = require('uppy/lib/plugins/Webcam')
  const Tus = require('uppy/lib/plugins/Tus')

  export default {
    data () {
      return {
        images: [],
      }
    },
     mounted () {
       const uppy = Uppy({
         debug: true,
         autoProceed: false,
         restrictions: {
           maxFileSize: 1000000,
           maxNumberOfFiles: 3,
           minNumberOfFiles: 2,
           allowedFileTypes: ['image/*', 'video/*']
         }
       })
       .use(Dashboard, {
         trigger: '.UppyModalOpenerBtn',
         inline: true,
         target: '.DashboardContainer',
         replaceTargetContent: true,
         note: 'Images and video only, 2–3 files, up to 1 MB',
         maxHeight: 450,
         metaFields: [
           { id: 'license', name: 'License', placeholder: 'specify license' },
           { id: 'caption', name: 'Caption', placeholder: 'describe what the image is about' }
         ]
       })
       .use(GoogleDrive, { target: Dashboard, host: 'https://server.uppy.io' })
       .use(Dropbox, { target: Dashboard, host: 'https://server.uppy.io' })
       .use(Instagram, { target: Dashboard, host: 'https://server.uppy.io' })
       .use(Webcam, { target: Dashboard })
       .use(Tus, { endpoint: 'https://master.tus.io/files/' })
       .run()
       uppy.on('complete', result => {
         console.log('successful files:', result.successful)
         console.log('failed files:', result.failed)
       })
     }
  }
</script>
