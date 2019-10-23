// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import 'babel-polyfill'
import Buefy from 'buefy'
import 'buefy/dist/buefy.css'
import 'bulmaswatch/cyborg/bulmaswatch.min.css'
import Vue from 'vue'
import Vuelidate from 'vuelidate'
import App from './App'
import router from './router'
import store from './store'
import VueLodash from 'vue-lodash'
import VueMoment from 'vue-moment'
import moment from 'moment-timezone'

Vue.config.productionTip = false

Vue.use(Buefy)
Vue.use(VueLodash)
Vue.use(Vuelidate)
Vue.use(VueMoment, { moment })

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  created () {
    this.$store.dispatch('userInfoRead')
  },
  template: '<App/>'
})
