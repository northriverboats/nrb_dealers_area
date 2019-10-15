// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import 'babel-polyfill'
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store'
import _ from 'lodash'

Object.defineProperty(Vue.prototype, '$_', { value: _ })

Vue.config.productionTip = false

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
