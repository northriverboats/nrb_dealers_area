import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import VueAxios from 'vue-axios'

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
    fileName: '',
    allHulls: [],
    loadCount: 0,
    oprHulls: [], // Uncompleted OPRs -- serial numbers
    oprList: [], // Completed OPRs -- partial info
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
      'Offshore C-Series',
      'Offshore S-Series',
      'Offshorw Walk Around',
      'Cascade',
      'Commander',
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
      '35'
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
      {id: 'Alabama', label: 'Alabama'},
      {id: 'Alaska', label: 'Alaska'},
      {id: 'Arizona', label: 'Arizona'},
      {id: 'Arkansas', label: 'Arkansas'},
      {id: 'California', label: 'California'},
      {id: 'Colorado', label: 'Colorado'},
      {id: 'Connecticut', label: 'Connecticut'},
      {id: 'Delaware', label: 'Delaware'},
      {id: 'Florida', label: 'Florida'},
      {id: 'Georgia', label: 'Georgia'},
      {id: 'Guam', label: 'Guam'},
      {id: 'Hawaii', label: 'Hawaii'},
      {id: 'Idaho', label: 'Idaho'},
      {id: 'Illinois', label: 'Illinois'},
      {id: 'Indiana', label: 'Indiana'},
      {id: 'Iowa', label: 'Iowa'},
      {id: 'Kansas', label: 'Kansas'},
      {id: 'Kentucky', label: 'Kentucky'},
      {id: 'Louisiana', label: 'Louisiana'},
      {id: 'Maine', label: 'Maine'},
      {id: 'Maryland', label: 'Maryland'},
      {id: 'Massachusetts', label: 'Massachusetts'},
      {id: 'Michigan', label: 'Michigan'},
      {id: 'Minnesota', label: 'Minnesota'},
      {id: 'Mississippi', label: 'Mississippi'},
      {id: 'Missouri', label: 'Missouri'},
      {id: 'Montana', label: 'Montana'},
      {id: 'Nebraska', label: 'Nebraska'},
      {id: 'Nevada', label: 'Nevada'},
      {id: 'New Hampshire', label: 'New Hampshire'},
      {id: 'New Jersey', label: 'New Jersey'},
      {id: 'New Mexico', label: 'New Mexico'},
      {id: 'New York', label: 'New York'},
      {id: 'North Carolina', label: 'North Carolina'},
      {id: 'North Dakota', label: 'North Dakota'},
      {id: 'Ohio', label: 'Ohio'},
      {id: 'Oklahoma', label: 'Oklahoma'},
      {id: 'Oregon', label: 'Oregon'},
      {id: 'Palau', label: 'Palau'},
      {id: 'Pennsylvania', label: 'Pennsylvania'},
      {id: 'Rhode Island', label: 'Rhode Island'},
      {id: 'South Carolina', label: 'South Carolina'},
      {id: 'South Dakota', label: 'South Dakota'},
      {id: 'Tennessee', label: 'Tennessee'},
      {id: 'Texas', label: 'Texas'},
      {id: 'Utah', label: 'Utah'},
      {id: 'Vermont', label: 'Vermont'},
      {id: 'Virginia', label: 'Virginia'},
      {id: 'Washington', label: 'Washington'},
      {id: 'West Virginia', label: 'West Virginia'},
      {id: 'Wisconsin', label: 'Wisconsin'},
      {id: 'Wyoming', label: 'Wyoming'},
      {id: 'Alberta', label: 'Alberta'},
      {id: 'British Columbia', label: 'British Columbia'},
      {id: 'Manitoba', label: 'Manitoba'},
      {id: 'New Brunswick', label: 'New Brunswick'},
      {id: 'Newfoundland and Labrador', label: 'Newfoundland and Labrador'},
      {id: 'Nova Scotia', label: 'Nova Scotia'},
      {id: 'Nunavut', label: 'Nunavut'},
      {id: 'Northwest Territories', label: 'Northwest Territories'},
      {id: 'Ontario', label: 'Ontario'},
      {id: 'Prince Edward Island', label: 'Prince Edward Island'},
      {id: 'Quebec', label: 'Quebec'},
      {id: 'Saskatchewan', label: 'Saskatchewan'},
      {id: 'Yukon', label: 'Yukon'},
      {id: 'NA', label: 'Not Applicable'} // if selected replace with null
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
    fileName: state => state.fileName,
    allHulls: state => state.allHulls,
    isNRB: state => { return state.userInfo === 'North River Boats' },
    isLoading: state => state.loadCount,
    oprHulls: state => state.oprHulls,
    oprList: state => state.oprList,
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
    DRIHULLS_READ (state, driHulls) {
      state.driHulls = driHulls
    },
    DRIHULLS_DELETE (state, id) {
      var index = Vue._.findIndex(state.driHulls, { id: id })
      state.driHulls.splice(index, 1)
    },
    DRI_CREATE (state, dri) {
      state.driList.push(dri)
    },
    DRI_READ (state, driList) {
      state.driList = driList
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
    OPRHULLS_DELETE (state, id) {
      var index = Vue._.findIndex(state.oprHulls, { id: id })
      state.oprHulls.splice(index, 1)
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
    setLoadCount ({ commit, state }, count) {
      commit('LOADCOUNT', count)
    },
    decreaseLoadCount ({ commit, state }, count) {
      commit('DECLOAD', count)
    },
    increaseLoadCount ({ commit, state }, count) {
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
        .then((response) => {
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
        .then((response) => {
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
    driHullsRead ({ commit, state }) {
      if (state.driDealerHulls.length > 0) {
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
          commit('OPRHULLS_DELETE', form.get('hull_serial_number'))
          commit('DECLOAD', 1)
          if (state.debug) { console.log('  CREATE: /api/opr') }
        })
    },
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
