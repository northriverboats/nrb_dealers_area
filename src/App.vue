<template>
  <div id="app">
    <div class="vld-parent">
      <loading :active.sync="loaded"
        :can-cancel="false"
        :is-full-page="true">
      </loading>
    </div>
    <div class="container is-full-desktop notification">
      <div class="columns is-vcentered">
        <div class="column is-narrow">
          <div class="logo"></div>
        </div>
        <div class="column has-text-right">
          <b-button @click="$router.go(-1)" type="is-info">Back</b-button>
        </div>
      </div>
    </div>
    <div class="container is-full-mobile is-four-fifths-desktop notification">
      <router-view/>
    </div>
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
  },
  methods: {
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
body, html  {
  font-family: Helvetica Neue,Helvetica,Arial,sans-serif;
  height: 100%;
  background-image: url("https://www.northriverboats.com/wp-content/uploads/2016/01/bg_main.jpg");
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  background-attachment: fixed;
}
.logo {
  background-image: url("https://www.northriverboats.com/wp-content/uploads/2016/01/logo.png");
  width: 250px;
  height: 85px;
}
</style>
