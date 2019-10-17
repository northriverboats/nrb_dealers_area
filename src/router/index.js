import Vue from 'vue'
import Router from 'vue-router'
import Main from '@/pages/Main'
import OPRview from '@/pages/OPRview'
import DRIview from '@/pages/DRIview'
import ContactUsView from '@/pages/ContactUsView'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Main',
      component: Main
    },
    {
      path: '/conctactusview',
      name: 'ContactUsView',
      component: ContactUsView
    },
    {
      path: '/driview',
      name: 'DRIview',
      component: DRIview
    },
    {
      path: '/oprview',
      name: 'OPRview',
      component: OPRview
    }
  ]
})
