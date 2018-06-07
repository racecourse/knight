export default {
  getAuthorization(state) {
    return state.user.auth;
  },
  getUser({ user }) {
    return user;
  },
  getPost({post}) {
    return post;
  },
  getCategory({category}) {
    return category.category;
  },
  getPhoto({photo}) {
    return photo.photos;
  }
}