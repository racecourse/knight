<template>
  <div class="bg">
    <div class="login-wrap">
      <span class="md-error">{{message}}</span>
      <mu-text-field label="username" hintText="username" class="container" v-model="username" labelFloat/>
      <mu-text-field label="password" hintText="password" type="password" class="container" v-model="password" labelFloat/>
       <mu-flat-button label="login" @click="submit" primary/>
    </div>
  </div>
</template>
<style>
  .bg {
    position: absolute;
    margin: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background: #00a8c6;
    text-align: center;
  }

  .login-wrap {
    font-size: .7rem;
    padding: 3.57143em 5.57143em 2.85714em;
    position: relative;
    z-index: 1;
    width: 25%;
    margin: 15em auto;
  }

  .container {
    position: relative;
    width: 100%;
    margin: 10px auto;
    padding: 24px;
    box-shadow: 4px 4px 20px 0 rgba(0, 0, 0, 0.01);
    min-height: calc(100vh - 198px);
  }

</style>
<script>
  import actions from '../store/actions';
  import Storage from '../util/storage';

  const storage = new Storage();
  export default {
    data() {
      const { message, ok } = this.$store.state.user;
      return {
        username: '',
        password: '',
        message: '',
        ok: false,
      }
    },
    methods: {
      change(field, value) {
        const data = {};
        data[field] = value;
        this.$store.commit('USER_LOGIN_CHANGE', data);
      },
      async submit() {
        const userInfo = {
          username: this.username,
          password: this.password,
        };
        if (userInfo) {
          const { password, username } = userInfo;
          await this.$store.dispatch('login', { username, password });
          const { message, ok } = this.$store.getters.getUser;
          if (ok) {
            this.$router.push('/admin/dashboard');
          } else {
            this.message = message;
            this.ok = ok;
          }
        }
      }
    },
    beforeMount() {
      const token = storage.getItem('token');
      if (token) {
//        console.log(token);
      }
    }
  }
</script>
