import Vue from 'vue'
import Router from 'vue-router'
import Main from '@/pages/Main'
import ContactUsForm from '@/pages/ContactUsForm'
import ContactUsView from '@/pages/ContactUsView'
import DRIform from '@/pages/DRIform'
import DRIview from '@/pages/DRIview'
import OPRform from '@/pages/OPRform'
import OPRview from '@/pages/OPRview'
import WarrantyVue from '@/pages/WarrantyView'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Main',
      component: Main
    },
    {
      path: '/contactusform/:id?',
      name: 'ContactUsForm',
      component: ContactUsForm
    },
    {
      path: '/conctactusview',
      name: 'ContactUsView',
      component: ContactUsView
    },
    {
      path: '/driform/:id?',
      name: 'DRIform',
      component: DRIform
    },
    {
      path: '/driview',
      name: 'DRIview',
      component: DRIview
    },
    {
      path: '/oprform/:id?',
      name: 'OPRform',
      component: OPRform
    },
    {
      path: '/oprview',
      name: 'OPRview',
      component: OPRview
    },
    {
      path: '/warrantyview',
      name: 'Warrantyview',
      component: WarrantyVue
    }

  ]
})
