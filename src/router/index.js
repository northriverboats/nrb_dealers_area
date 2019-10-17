import Vue from 'vue'
import Router from 'vue-router'
import Main from '@/pages/Main'
import OPRview from '@/pages/OPRview'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Main',
      component: Main
    },
    {
      path: '/oprview',
      name: 'OPRview',
      component: OPRview
    }
  ]
})
