import PhotoSwipe from 'photoswipe/dist/photoswipe';
import PhotoSwipeUI_Default from 'photoswipe/dist/photoswipe-ui-default';

const openPhotoSwipe = function (items, options = {}) {
  const pswpElement = document.querySelectorAll('.pswp')[0];

  // // build items array
  // const items = [{
  //     src: 'https://farm2.staticflickr.com/1043/5186867718_06b2e9e551_b.jpg',
  //     w: 964,
  //     h: 1024
  //   },
  //   {
  //     src: 'https://farm7.staticflickr.com/6175/6176698785_7dee72237e_b.jpg',
  //     w: 1024,
  //     h: 683
  //   }
  // ];
  if (typeof items === 'string') {
    const image = new Image();
    image.src = items;
    console.log('pppppp', image.width, image.height);
    items = [
      {
        src: items,
        w: image.width,
        h: image.height,
      }
    ]
  } else if (typeof items === 'object') {
    items = [items]
  }

  if (!Array.isArray(items)) {
    return;
  }

  const defaultOptions = {
    index: 0,
    history: false,
    focus: false,
    showAnimationDuration: 0,
    hideAnimationDuration: 0
  };
  options = Object.assign(defaultOptions, options);
  const gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
  gallery.init();
};

export default openPhotoSwipe;

