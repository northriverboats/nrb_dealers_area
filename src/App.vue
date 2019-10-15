<template>
  <div id="app">
    <div class="vld-parent">
      <loading :active.sync="loaded"
        :can-cancel="false"
        :is-full-page="true">
      </loading>
    </div>
    <img src="./assets/logo.png">
    <router-view/>
  </div>
</template>

<script>
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import { mapGetters } from 'vuex'

export default {
  name: 'App',
  components: {
    Loading
  },
  computed: {
    ...mapGetters([
      'isLoading'
    ]),
    loaded: function () {
      return this.isLoading > 0
    }
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
body {
  background-image: url(https://www.northriverboats.com/wp-content/uploads/2016/01/bg_main.jpg);
   font-family: Helvetica Neue,Helvetica,Arial,sans-serif;
}
</style>
