
export default [
  {
    path: '/',
    component: require('./components/common/layout.vue').default,
    meta: { auth: false },
    children: [
      {
        path: '/',
        component: require('./views/posts.vue').default
      },
      {
        path: '/posts',
        component: require('./views/posts.vue').default
      },
      {
        path: '/posts/:id',
        component: require('./views/detail.vue').default
      },
      {
        path: '/timeline',
        component: require('./views/archive.vue').default
      },
      {
        path: '/photos',
        component: require('./views/photos.vue').default
      },
      {
        path: '/albums',
        component: require('./views/albums.vue').default
      },
      {
        path: '/albums/:albumId/photos',
        component: require('./views/album.vue').default
      },
    ]
  },
  {
    path: '/admin',
    component: require('./components/admin/index.vue').default,
    children: [
      {
        path: 'dashboard',
        meta: { auth: true },
        component: require('./components/admin/dashboard.vue').default
      },
      {
        path: 'create',
        meta: { auth: true },
        component: require('./components/editor/index.vue').default
      },
      {
        path: 'article',
        meta: { auth: true },
        component: require('./components/admin/article.vue').default,
      },
      {
        path: 'article/:id/edit',
        meta: { auth: true },
        component: require('./components/admin/edit.vue').default,
      },
      {
        path: 'comment',
        meta: { auth: true },
        component: require('./components/admin/comment.vue').default,
      },
      {
        path: 'upload',
        // meta: { auth: true },
        component: require('./components/admin/upload.vue').default,
      },
      {
        path: 'image',
        // meta: { auth: true },
        component: require('./components/admin/image.vue').default,
      },
    ]
  },
  {
    path: '/login',
    component: require('./views/login.vue').default
  },
  {
    path: '/register',
    component: require('./views/register.vue').default
  },
  {
    path: '*',
    component: require('./views/starry.vue').default
  },
]
