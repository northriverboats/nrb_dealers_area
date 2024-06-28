import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import VueAxios from 'vue-axios'

/* SHORTCUTS 's=store 'g=getters 'm=mutations 'a=actions */ 

/* eslint no-undef: "error" */
axios.defaults.headers.common['X-WP-Nonce'] = window.nonce
axios.defaults.baseURL = 'https://www.northriverboats.com/wp-json/nrb_dealers_area/'

Vue.use(Vuex)
Vue.use(VueAxios, axios)

/* ****************************************************************************
 *
 * NOTES:
 * - opr/dri Hulls are just a list of serial numbers of Uncompleted forms
 * - opr/dri all completed OPRs
 *
 * ****************************************************************************/

export default new Vuex.Store({
  state: {
    contactList: [],
    debug: true,
    dealers: [],
    dealership: '',
    dealerships: [],
    dealersGroup: [],
    driHulls: [], // Uncompleted DRIs -- serial numbers
    driList: [], // Completed DRIs -- partial info
    dris: [], // actual full dri's
    fileName: '',
    allHulls: [],
    loadCount: 0,
    oprHulls: [], // Uncompleted OPRs -- serial numbers
    oprList: [], // Completed OPRs -- partial info
    oprs: [], // actual full opr's
    serviceRates: [],
    serviceReasons: [],
    userInfo: '',
    warrantyList: [],
    boatUseList: [
      'Lake',
      'Ocean',
      'River'
    ],
    recReasonList: [
      'Parts',
      'Sales',
      'Service'
    ],
    boatModelList: [
      'Seahawk Hardtop',
      'Seahawk Inboard',
      'Seahawk Outboard',
      'LE',
      'Offshore C-Series',
      'Offshore S-Series',
      'Offshore Walk Around',
      'Offshore VXL',
      'Cascade',
      'Commander',
      'Endurance',
      'Osprey',
      'Scout',
      'Other'
    ],
    boatLengthList: [
      '16',
      '17',
      '18\'6',
      '19',
      '20',
      '21',
      '21\'6',
      '22',
      '23',
      '24',
      '25',
      '26',
      '27',
      '28',
      '29',
      '30',
      '31',
      '32',
      '33',
      '34',
      '35',
      '37'
    ],
    engineMakeList: [
      'Yamaha',
      'Honda',
      'Mercury',
      'Suzki',
      'Evinrude',
      'Other'
    ],
    trailerList: [
      'EZ Loader 3100',
      'EZ Loader 4000',
      'EZ Loader 4700',
      'EZ Loader 5200',
      'EZ Loader 6800',
      'EZ Loader 7500',
      'EZ Loader 8500',
      'EZ Loader 12000',
      'EZ Loader 13500',
      'EZ Loader 14500',
      'NorthWest S/A',
      'NorthWest T/A',
      'Other'
    ],
    stateList: [
      'Alabama',
      'Alaska',
      'Arizona',
      'Arkansas',
      'California',
      'Colorado',
      'Connecticut',
      'Delaware',
      'Florida',
      'Georgia',
      'Guam',
      'Hawaii',
      'Idaho',
      'Illinois',
      'Indiana',
      'Iowa',
      'Kansas',
      'Kentucky',
      'Louisiana',
      'Maine',
      'Maryland',
      'Massachusetts',
      'Michigan',
      'Minnesota',
      'Mississippi',
      'Missouri',
      'Montana',
      'Nebraska',
      'Nevada',
      'New Hampshire',
      'New Jersey',
      'New Mexico',
      'New York',
      'North Carolina',
      'North Dakota',
      'Ohio',
      'Oklahoma',
      'Oregon',
      'Pennsylvania',
      'Rhode Island',
      'South Carolina',
      'South Dakota',
      'Tennessee',
      'Texas',
      'Utah',
      'Vermont',
      'Virginia',
      'Washington',
      'West Virginia',
      'Wisconsin',
      'Wyoming',
      'Alberta',
      'British Columbia',
      'Manitoba',
      'New Brunswick',
      'Newfoundland and Labrador',
      'Nova Scotia',
      'Nunavut',
      'Northwest Territories',
      'Ontario',
      'Prince Edward Island',
      'Quebec',
      'Saskatchewan',
      'Yukon',
      'Not Applicable' // if selected replace with null
    ]
  },
  getters: {
    contactList: state => state.contactList,
    debug: state => state.debug,
    dealers: state => state.dealers,
    dealersGroup: state => state.dealersGroup,
    dealership: state => state.dealership,
    dealerships: state => state.dealerships,
    driHulls: state => state.driHulls,
    driList: state => state.driList,
    dris: state => state.dris,
    fileName: state => state.fileName,
    allHulls: state => state.allHulls,
    isNRB: state => { return state.userInfo === 'North River Boats' },
    isLoading: state => state.loadCount,
    oprHulls: state => state.oprHulls,
    oprList: state => state.oprList,
    oprs: state => state.oprs,
    serviceRates: state => state.serviceRates,
    serviceReasons: state => state.serviceReasons,
    userInfo: state => state.userInfo,
    warrantyList: state => state.warrantyList,
    boatUseList: state => state.boatUselist,
    boatLengthList: state => state.boatLengthList,
    engineMakeList: state => state.engineMakeList,
    trailerList: state => state.trailerList,
    recReasonList: state => state.recReasonList,
    stateList: state => state.stateList
  },
  mutations: {
    // load screen releated
    LOADCOUNT (state, count) {
      state.loadCount = count
    },
    DECLOAD (state, count) {
      state.loadCount = Math.max(state.loadCount - count, 0)
    },
    INCLOAD (state, count) {
      state.loadCount = state.loadCount + count
    },
    // temp file name
    FILENAME_SET (state, fileName) {
      state.fileName = fileName
    },
    // Based on CRUD terminology
    ALLHULLS_READ (state, hulls) {
      state.allHulls = hulls
    },
    CONTACTLIST_READ (state, contacts) {
      state.contactList = contacts
    },
    CONTACTS_UPDATE (state, contact) {
      var index = Vue._.findIndex(state.contactList, { id: contact.id })
      state.contactList.splice(index, 1, contact)
    },
    DEALERS_CREATE (state, dealer) {
      state.dealers.push(dealer)
    },
    DEALERS_READ (state, dealers) {
      state.dealers = dealers
      // build list of dealerships
      var prev = ''
      dealers.forEach(function (dealer) {
        if (prev !== dealer['dealer_group']) {
          prev = dealer['dealer_group']
          state.dealersGroup.push({
            'id': dealer['dealer_group'],
            'label': dealer['dealer_group']
          })
        }
      })
    },
    DEALERS_UPDATE (state, dealer) {
      var index = Vue._.findIndex(state.dealers, { uid: dealer.uid })
      state.dealers.splice(index, 1, dealer)
    },
    DEALERS_DELETE (state, uid) {
      var index = Vue._.findIndex(state.dealers, { uid: uid })
      state.dealers.splice(index, 1)
    },
    DEALERSHIPS_READ (state, dealerships) {
      state.dealerships = dealerships
    },
    DRI_CREATE (state, dri) {
      state.driList.push(dri)
    },
    DRI_READ (state, driList) {
      state.driList = driList
    },
    DRI_DELETE (state, id) {
      var index = Vue._.findIndex(state.driList, { id: id })
      if (state.debug) { console.log(`    DRILIST_DELETE driList: ${state.driList.length}`) }
      if (state.debug) { console.log(`    DRILIST_DELETE index: ${index}`) }
      state.driList.splice(index, 1)
      if (state.debug) { console.log(`    DRILIST_DELETE driList: ${state.driList.length}`) }
    },
    DRIHULLS_READ (state, driHulls) {
      state.driHulls = driHulls
    },
    DRIHULLS_DELETE (state, id) {
      var index = Vue._.findIndex(state.driHulls, { id: id })
      if (state.debug) { console.log(`    DRIHULLS_DELETE driHulls: ${state.driHulls.length}`) }
      if (state.debug) { console.log(`    DRIHULLS_DELETE index: ${index}`) }
      state.driHulls.splice(index, 1)
      if (state.debug) { console.log(`    DRIHULLS_DELETE driHulls: ${state.driHulls.length}`) }
    },
    DRIS_CREATE (state, dri) {
      state.dris.push(dri)
    },
    DRIS_READ (state, dri) {
      state.dris.push(dri)
    },
    DRIS_DELETE (state, id) {
      var index = Vue._.findIndex(state.dris, { id: id })
      if (state.debug) { console.log(`    DRIS_DELETE driHulls: ${state.dris.length}`) }
      if (state.debug) { console.log(`    DRIS_DELETE index: ${index}`) }
      state.dris.splice(index, 1)
      if (state.debug) { console.log(`    DRIHULLS_DELETE dris: ${state.dris.length}`) }
    },
    OPR_CREATE (state, opr) {
      state.oprList.push(opr)
    },
    OPR_READ (state, oprList) {
      state.oprList = oprList
    },
    OPRHULLS_READ (state, oprHulls) {
      state.oprHulls = oprHulls
    },
    OPR_DELETE (state, id) {
      var index = Vue._.findIndex(state.oprList, { id: id })
      if (state.debug) { console.log(`    OPRLIST_DELETE oprList: ${state.oprList.length}`) }
      if (state.debug) { console.log(`    OPRLIST_DELETE index: ${index}`) }
      state.oprList.splice(index, 1)
      if (state.debug) { console.log(`    OPRLIST_DELETE oprList: ${state.oprList.length}`) }
    },
    OPRHULLS_DELETE (state, id) {
      var index = Vue._.findIndex(state.oprHulls, { id: id })
      state.oprHulls.splice(index, 1)
    },
    OPRS_CREATE (state, opr) {
      state.oprs.push(opr)
    },
    OPRS_READ (state, opr) {
      state.oprs.push(opr)
    },
    OPRS_DELETE (state, id) {
      var index = Vue._.findIndex(state.oprs, { id: id })
      if (state.debug) { console.log(`    OPRS_DELETE oprHulls: ${state.oprs.length}`) }
      if (state.debug) { console.log(`    OPRS_DELETE index: ${index}`) }
      state.oprs.splice(index, 1)
      if (state.debug) { console.log(`    OPRHULLS_DELETE oprs: ${state.oprs.length}`) }
    },
    SERVICERATES_READ (state, rates) {
      state.serviceRates = rates
    },
    SERVICEREASONS (state, reasons) {
      state.serviceReasons = reasons
    },
    USERINFO_READ (state, userInfo) {
      state.userInfo = userInfo
    },
    WARRANTY_READ (state, warrantyList) {
      state.warrantyList = warrantyList
    }
  },
  actions: {
    setLoadCount ({ commit }, count) {
      commit('LOADCOUNT', count)
    },
    decreaseLoadCount ({ commit }, count) {
      commit('DECLOAD', count)
    },
    increaseLoadCount ({ commit }, count) {
      commit('INCLOAD', count)
    },
    // ALL HULLS CRUD
    allHullsRead ({ commit, state }) {
      if (state.allHulls.length > 0) {
        if (state.debug) { console.log('  ALREADY READ: /api/hulls') }
        return
      }
      commit('INCLOAD', 1)
      return axios
        .get('hulls')
        .then((response) => {
          commit('ALLHULLS_READ', response.data)
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  READ: /api/hulls') }
        })
    },
    // CONTACT CRUD
    contactListRead ({ commit, state }) {
      if (state.contactList.length > 0) {
        if (state.debug) { console.log('  ALREADY READ: /api/contact_us') }
        return
      }
      commit('INCLOAD', 1)
      return axios
        .get('contact_us')
        .then((response) => {
          commit('CONTACTLIST_READ', response.data)
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  READ: /api/contact_us') }
        })
    },
    contactUsUpdate ({ commit, state }, form) {
      commit('INCLOAD', 1)
      return axios
        .post('contact_us/' + form.id, form)
        .then(() => {
          commit('CONTACTS_UPDATE', form)
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  UPDATED: /api/contact_us') }
        })
    },
    // DEALERS CRUD
    dealersCreate ({ commit, state }, form) {
      return axios
        .post('dealers', form)
        .then((response) => {
          form['uid'] = response.data.uid
          commit('DEALERS_CREATE', form)
          if (state.debug) { console.log('  CREATED: /api/dealers') }
        })
    },
    dealersRead ({ commit, state }) {
      if (state.dealers.length > 0) {
        if (state.debug) { console.log('  ALREADY READ: /api/dealers') }
        return
      }
      commit('INCLOAD', 1)
      return axios
        .get('dealers')
        .then((response) => {
          commit('DEALERS_READ', response.data)
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  READ: /api/dealers') }
        })
    },
    dealersUpdate ({ commit, state }, form) {
      return axios
        .put('dealers/' + form.uid, form)
        .then(() => {
          commit('DEALERS_UPDATE', form)
          if (state.debug) { console.log('  UPDATE: /api/dealers') }
        })
    },
    // DEALERSHIPS cRud
    dealershipsRead ({ commit, state }) {
      if (state.dealerships.length > 0) {
        if (state.debug) { console.log('  ALREADY READ: /api/dealerships') }
        return
      }
      return axios
        .get('dealerships')
        .then((response) => {
          commit('DEALERSHIPS_READ', response.data)
          if (state.debug) { console.log('  READ: /api/dealerships') }
        })
    },
    // DRIHULLS cRud
    driHullsRead ({ commit, state }, always) {
      if (state.driHulls.length > 0 && !always) {
        if (state.debug) { console.log('  ALREADY READ: /api/drihulls') }
        return
      }
      commit('INCLOAD', 1)
      return axios
        .get('drihulls')
        .then((response) => {
          commit('DRIHULLS_READ', response.data)
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  READ: /api/drihulls') }
        })
    },
    // DRIS Crud 
    drisCreate ({ commit, state }, form) {
      commit('INCLOAD', 1)
      return axios
        .post('dri', form)
        .then((response) => {
          commit('DRI_CREATE', response.data)
          commit('DRIHULLS_DELETE', form.get('hull_serial_number'))
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  CREATE: /api/dri') }
        })
    },
    // DRIS cRud
    drisRead ({ commit, state }, id) {
      if (typeof (state.dris.find(hull => hull.id === id)) !== 'undefined') {
        if (state.debug) { console.log('  ALREADY READ: /api/dri' + id) }
        return
      }
      commit('INCLOAD', 1)
      return axios
        .get('dri/' + id)
        .then((response) => {
          commit('DRIS_READ', response.data)
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  READ: /api/dri/' + id) }
        })
    },
    // DRIS cruD
    drisDelete ({ dispatch, commit, state }, id) {
      if (state.debug) { console.log('  DELETE: /api/dri' + id) }
      commit('INCLOAD', 1)
      return axios
        .delete('dri/' + id)
        .then(() => {
          commit('DRI_DELETE', id)
          commit('DRIS_DELETE', id)
          dispatch('driHullsRead', true)
          commit('DECLOAD', 1)
        })
    },
    // DRILIST CRUD
    driCreate ({ commit, state }, form) {
      commit('INCLOAD', 1)
      return axios
        .post('dri', form)
        .then((response) => {
          commit('DRI_CREATE', response.data)
          commit('DRIHULLS_DELETE', form.get('hull_serial_number'))
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  CREATE: /api/dri') }
        })
    },
    driRead ({ commit, state }) {
      if (state.driList.length > 0) {
        if (state.debug) { console.log('  ALREADY READ: /api/dri') }
        return
      }
      commit('INCLOAD', 1)
      return axios
        .get('dri')
        .then((response) => {
          commit('DRI_READ', response.data)
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  READ: /api/dri') }
        })
    },
    // OPRHULLS cRud
    oprHullsRead ({ commit, state }) {
      if (state.oprHulls.length > 0) {
        if (state.debug) { console.log('  ALREADY READ: /api/oprhulls') }
        return
      }
      commit('INCLOAD', 1)
      return axios
        .get('oprhulls')
        .then((response) => {
          commit('OPRHULLS_READ', response.data)
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  READ: /api/oprhulls') }
        })
    },
    // OPRLIST CRUD
    oprCreate ({ commit, state }, form) {
      commit('INCLOAD', 1)
      return axios
        .post('opr', form)
        .then((response) => {
          commit('OPR_CREATE', response.data)
          // commit('OPRHULLS_DELETE', form.get('hull_serial_number'))
          commit('OPRHULLS_DELETE', form['hull_serial_number'])
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  CREATE: /api/opr') }
        })
    },
    // OPR cRud
    oprRead ({ commit, state }) {
      if (state.oprList.length > 0) {
        if (state.debug) { console.log('  ALREADY READ: /api/opr') }
        return
      }
      commit('INCLOAD', 1)
      return axios
        .get('opr')
        .then((response) => {
          commit('OPR_READ', response.data)
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  READ: /api/opr') }
        })
    },
    // OPRS cRud
    oprsRead ({ commit, state }, id) {
      if (typeof (state.oprs.find(hull => hull.id === id)) !== 'undefined') {
        if (state.debug) { console.log('  ALREADY READ: /api/opr' + id) }
        return
      }
      commit('INCLOAD', 1)
      return axios
        .get('opr/' + id)
        .then((response) => {
          commit('OPRS_READ', response.data)
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  READ: /api/opr/' + id) }
        })
    },
    // OPRS cruD
    oprsDelete ({ dispatch, commit, state }, id) {
      if (state.debug) { console.log('  DELETE: /api/opr' + id) }
      commit('INCLOAD', 1)
      return axios
        .delete('opr/' + id)
        .then(() => {
          commit('OPR_DELETE', id)
          commit('OPRS_DELETE', id)
          dispatch('oprHullsRead', true)
          commit('DECLOAD', 1)
        })
    },
    // SERVICE RATES CRUD
    serviceRatesRead ({ commit, state }) {
      if (state.serviceRates.length > 0) {
        if (state.debug) { console.log('  ALREADY READ: /api/rates') }
        return
      }
      commit('INCLOAD', 1)
      return axios
        .get('rates')
        .then((response) => {
          commit('SERVICERATES_READ', response.data)
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  READ: /api/rates') }
        })
    },
    // SERVICE REAONS CRUD
    serviceReasonsRead ({ commit, state }) {
      if (state.serviceReasons.length > 0) {
        if (state.debug) { console.log('  ALREADY READ: /api/warranty/serviceReasons') }
        return
      }
      commit('INCLOAD', 1)
      return axios
        .get('warranty/serviceReasons')
        .then((response) => {
          commit('SERVICEREASONS_READ', response.data)
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  READ: /api/warranty/serviceReasons') }
        })
    },
    // USERINFO cRud
    userInfoRead ({ commit, state }) {
      if (state.userInfo) {
        if (state.debug) { console.log('  ALREADY READ: /api/userinfo') }
        return
      }
      commit('INCLOAD', 1)
      return axios
        .get('userInfo')
        .then((response) => {
          commit('USERINFO_READ', response.data.dealership)
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  READ: /api/userinfo') }
        })
    },
    // cRud PDFs
    readContactUsPDF ({ commit, state }, id) {
      commit('INCLOAD', 1)
      return axios
        .get('contact_us/pdf/' + id)
        .then((response) => {
          commit('FILENAME_SET', response.data.filename)
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  READ: /api/contact_us/pdf/' + id) }
        })
    },
    readDRIPDF ({ commit, state }, id) {
      commit('INCLOAD', 1)
      return axios
        .get('dri/pdf/' + id)
        .then((response) => {
          commit('FILENAME_SET', response.data.filename)
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  READ: /api/dri/pdf/' + id) }
        })
    },
    readOPRPDF ({ commit, state }, id) {
      commit('INCLOAD', 1)
      return axios
        .get('opr/pdf/' + id)
        .then((response) => {
          commit('FILENAME_SET', response.data.filename)
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  READ: /api/opr/pdf/' + id) }
        })
    },
    // Warranty CRUD
    warrantyListRead ({ commit, state }) {
      if (state.driList.length > 0) {
        if (state.debug) { console.log('  ALREADY READ: /api/warranty') }
        return
      }
      commit('INCLOAD', 1)
      return axios
        .get('warranty')
        .then((response) => {
          commit('WARRANTY_READ', response.data)
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  READ: /api/warranty') }
        })
    }
  }
})
