<template>
  <div id="app">
    <div class="vld-parent">
      <loading :active.sync="loaded"
        :can-cancel="false"
        :is-full-page="true">
      </loading>
    </div>
    <div class="columns is-centered">
      <div class="column is-12-tablet is-11-desktop is-10-widescreen is-9-fullhd">
        <div class="container">
          <div class="columns is-vcentered">
            <div class="column is-narrow">
              <div class="logo"></div>
            </div>
            <div class="column has-text-right">
              <b-button @click="$router.go(-1)" type="is-info">Back</b-button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="columns  is-centered">
      <div class="column is-11-tablet is-10-desktop is-9-widescreen is-8-fullhd">
        <router-view/>
      </div>
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
.pagination-link, .pagination-next, .pagination-previous {
  border-color: #FFFFFF;
  color: #FFFFFF;
  min-width: 2.25em;
}
div.formgroup h3 {
  font-size: 1.5rem;
  margin-bottom: .5rem !important;
}
div.formgroup hr {
  margin-top: 0rem;
  margin-bottom: 0rem;
}
.datepicker .datepicker-table .datepicker-body .datepicker-cell.is-selectable {
  color: #FFFFFF;
}
</style>
