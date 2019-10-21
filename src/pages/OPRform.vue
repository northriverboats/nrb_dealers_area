<template>
  <div>
    <div class="columns is-multiline">
      <div class="column is-full formgroup">
        <h3 class="title">Original Purchaser Registration for {{ dealership }}</h3>
        <hr class="has-background-white">
      </div>
      <div class="column is-6-tablet is-5-desktop">
        <b-field label="First Name">
          <b-input v-model="form.first_name"></b-input>
        </b-field>
      </div>
      <div class="column is-6-tablet is-7-desktop">
        <b-field label="Last Name">
          <b-input v-model="form.last_name"></b-input>
        </b-field>
      </div>
      <div class="column is-6-tablet is-3-desktop">
        <b-field label="Home Phone Number">
          <b-input v-model="form.phone_home"></b-input>
        </b-field>
      </div>
      <div class="column is-6-tablet is-3-desktop">
        <b-field label="Work Phone Number">
          <b-input v-model="form.phone_work"></b-input>
        </b-field>
      </div>
      <div class="column is-6-tablet is-6-desktop">
        <b-field label="Email Address">
          <b-input v-model="form.email"></b-input>
        </b-field>
      </div>
    </div>

    <!-- STREET ADDRESS INFO -->
    <div class="columns is-multiline">
      <div class="column is-full formgroup">
        <h3 class="title">Street Address</h3>
        <hr class="has-background-white">
      </div>
      <div class="column is-full">
        <b-field label="Address">
          <b-input v-model="form.street_address" @blur="blurAddress"></b-input>
        </b-field>
      </div>
      <div class="column is-5-tablet is-6-desktop">
        <b-field label="City">
          <b-input v-model="form.street_city" @blur="blurCity"></b-input>
        </b-field>
      </div>
      <div class="column is-5-tablet is-3-desktop">
        <b-field label="State">
          <b-autocomplete
            v-model="form.street_state"
            :data="filteredStreetStateArray"
            placeholder="e.g. Oregon"
            :keep-first="true"
            @input="blurState"
            >
            <template slot="empty">No results found</template>
          </b-autocomplete>
        </b-field>
      </div>
      <div class="column is-2-tablet is-3-desktop">
        <b-field label="Zip">
          <b-input v-model="form.street_zip" @blur="blurZip"></b-input>
        </b-field>
      </div>
    </div>

    <!-- MAILING ADDRESS INFO -->
    <div class="columns is-multiline">
      <div class="column is-full formgroup">
        <h3 class="title">Mailing Address</h3>
        <hr class="has-background-white">
      </div>
      <div class="column is-full">
        <b-switch v-model="same" type="is-success" @input="flipAddressBlock">
          {{ isSame }}
        </b-switch>
      </div>
      <div class="column is-full" v-if="!same">
        <b-field label="Address">
          <b-input v-model="form.mailing_address"></b-input>
        </b-field>
      </div>
      <div class="column is-5-tablet is-6-desktop" v-if="!same">
        <b-field label="City">
          <b-input v-model="form.mailing_city"></b-input>
        </b-field>
      </div>
      <div class="column is-5-tablet is-3-desktop" v-if="!same">
        <b-field label="State">
          <b-autocomplete
            v-model="form.mailing_state"
            :data="filteredMailingStateArray"
            placeholder="e.g. Oregon"
            :keep-first="true"
            >
            <template slot="empty">No results found</template>
          </b-autocomplete>
        </b-field>
      </div>
      <div class="column is-2-tablet is-3-desktop" v-if="!same">
        <b-field label="Zip">
          <b-input v-model="form.mailing_zip"></b-input>
        </b-field>
      </div>
    </div>

    <!-- BOAT INFO -->
    <div class="columns is-multiline">
      <div class="column is-full formgroup">
        <h3 class="title">Boat Information</h3>
        <hr class="has-background-white">
      </div>
      <div class="column is-6-tablet is-4-desktop">
        <b-field label="Hull Serial Number">
          <b-autocomplete
            v-model="form.hull_serial_number"
            :data="filteredHullsArray"
            field="id"
            placeholder="e.g. NRB 21045 B818"
            :keep-first="true"
            @select="hullChanged"
            >
            <template slot="empty">No results found</template>
          </b-autocomplete>
        </b-field>
      </div>
      <div class="column is-6-tablet is-4-desktop">
        <b-field label="Model">
          <b-input v-model="form.model" readonly></b-input>
        </b-field>
      </div>
      <div class="column is-one-half-tablet is-4-desktop">
        <b-field label="Date Purchased">
          <b-datepicker
            is-info
            v-model="form.date_purchased"
            placeholder="Type or select a date..."
            icon="calendar-today"
            editable>
        </b-datepicker>
        </b-field>
      </div>
      <div class="column is-one-half-tablet is-5-desktop">
        <b-field label="Dealership">
          <b-input v-model="form.dealership" readonly></b-input>
        </b-field>
      </div>
      <div class="column is-one-half-tablet is-7-desktop">
        <b-field label="Salesperson">
          <b-input v-model="form.salesperson"></b-input>
        </b-field>
      </div>
</div>

    <!-- TRAILER INFO -->
    <div class="columns is-multiline">
      <div class="column is-full formgroup">
        <h3 class="title">Trailer Information</h3>
        <hr class="has-background-white">
      </div>
      <div class="column is-5-tablet is-3-desktop">
        <b-field label="Trailer Model">
          <b-autocomplete
            v-model="form.trailer_model"
            :data="filteredTrailerListArray"
            placeholder="e.g. EZ Loader 3100"
            :keep-first="true"
            >
            <template slot="empty">No results found</template>
          </b-autocomplete>
        </b-field>
      </div>
      <div class="column is-7-tablet is-9-desktop">
        <b-field label="Trailer Serial">
          <b-input v-model="form.trailer_serial_number"></b-input>
        </b-field>
      </div>
    </div>

    <!-- Motor INFO -->
    <div class="columns is-multiline">
      <div class="column is-full formgroup">
        <h3 class="title">Motor Information</h3>
        <hr class="has-background-white">
      </div>
      <div class="column is-4-tablet is-4-desktop">
        <b-field label="Engine 1 Make">
          <b-autocomplete
            v-model="form.engine_1_make"
            :data="filteredEngine1MakeListArray"
            placeholder="e.g. Yamaha"
            :keep-first="true"
            >
            <template slot="empty">No results found</template>
          </b-autocomplete>
        </b-field>
      </div>
      <div class="column is-4-tablet is-4-desktop">
        <b-field label="Engine 1 Model">
          <b-input v-model="form.engine_1_model"></b-input>
        </b-field>
      </div>
      <div class="column is-4-tablet is-4-desktop">
        <b-field label="Engine 1 Serial Number">
          <b-input v-model="form.engine_1_serial_number"></b-input>
        </b-field>
      </div>
      <div class="column is-4-tablet is-4-desktop">
        <b-field label="Engine 2 Make">
          <b-autocomplete
            v-model="form.engine_2_make"
            :data="filteredEngine2MakeListArray"
            placeholder="e.g. Yamaha"
            :keep-first="true"
            >
            <template slot="empty">No results found</template>
          </b-autocomplete>
        </b-field>
      </div>
      <div class="column is-4-tablet is-4-desktop">
        <b-field label="Engine 2 Model">
          <b-input v-model="form.engine_2_model"></b-input>
        </b-field>
      </div>
      <div class="column is-4-tablet is-4-desktop">
        <b-field label="Engine 2 Serial Number">
          <b-input v-model="form.engine_2_serial_number"></b-input>
        </b-field>
      </div>
      <div class="column is-4-tablet is-4-desktop">
        <b-field label="Engine 3 Make">
          <b-autocomplete
            v-model="form.engine_3_make"
            :data="filteredEngine3MakeListArray"
            placeholder="e.g. Yamaha"
            :keep-first="true"
            >
            <template slot="empty">No results found</template>
          </b-autocomplete>
        </b-field>
      </div>
      <div class="column is-4-tablet is-4-desktop">
        <b-field label="Engine 3 Model">
          <b-input v-model="form.engine_3_model"></b-input>
        </b-field>
      </div>
      <div class="column is-4-tablet is-4-desktop">
        <b-field label="Engine 3 Serial Number">
          <b-input v-model="form.engine_3_serial_number"></b-input>
        </b-field>
      </div>
    </div>

    <div class="columns is-multiline">
      <div class="column is-full">
      </div>
      <div class="column is-full has-text-right">
        <b-button @click="$router.go(-1)" outlined>Cancel</b-button>
        <b-button @click="submitForm" type="is-dark" inverted>Submit</b-button>
      </div>
      <div class="column is-full">
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

import Cleave from 'cleave.js'
// require('cleave.js/dist/addons/cleave-phone.us.js')
const cleave = {
  name: 'cleave',
  bind (el, binding) {
    const input = el.querySelector('input')
    input._vCleave = new Cleave(input, binding.value)
  },
  unbind (el) {
    const input = el.querySelector('input')
    input._vCleave.destroy()
  }
}

export default {
  name: 'DRIform',
  directives: { cleave },
  data () {
    return {
      selected: null,
      name: '',
      id: 0,
      dealership: 0,
      date_received_start: '',
      date_received_end: '',
      hull_serial_number: '',
      isFormValid: false,
      same: true,
      submit_locked: false,
      form: {
        // owner
        agency: '',
        contract: '',
        first_name: '',
        last_name: '',
        // contact
        phone_home: '',
        phone_work: '',
        email: '',
        // street address
        street_address: '',
        street_city: '',
        street_state: '',
        street_zip: '',
        // mailing address
        mailing_address: '',
        mailing_city: '',
        mailing_state: '',
        mailing_zip: '',
        // dealership
        salesperson: '',
        // purchased
        date_purchased: null,
        dealership: '',
        hull_serial_number: '',
        // trailer
        trailer_model: '',
        trailer_serial_number: '',
        // engines
        engine_1_make: '',
        engine_1_model: '',
        engine_1_serial_number: '',
        engine_2_make: '',
        engine_2_model: '',
        engine_2_serial_number: '',
        engine_3_make: '',
        engine_3_model: '',
        engine_3_serial_number: ''
      },
      masks: {
        creditCard: {
          creditCard: true,
          delimiter: '-'
        },
        numeral: {
          numeral: true,
          numeralThousandsGroupStyle: 'thousand',
          prefix: '$ '
        }
      }
    }
  },
  computed: {
    ...mapGetters([
      'debug',
      'engineMakeList',
      'isNRB',
      'oprHulls',
      'stateList',
      'trailerList',
      'userInfo'
    ]),
    isSame () {
      return this.same ? 'Addresses are the Same' : 'Addresses are Different'
    },
    filteredHullsArray () {
      return this.oprHulls.filter((option) => {
        return option.id
          .toString()
          .replace(/\s+/g, '')
          .toLowerCase()
          .indexOf(this.form.hull_serial_number.toLowerCase().replace(/\s+/g, '')) >= 0
      })
    },
    filteredTrailerListArray () {
      return this.trailerList.filter((option) => {
        return option
          .toString()
          .replace(/\s+/g, '')
          .toLowerCase()
          .indexOf(this.form.trailer_model.toLowerCase().replace(/\s+/g, '')) >= 0
      })
    },
    filteredStreetStateArray () {
      return this.stateList.filter((option) => {
        return option
          .toString()
          .toLowerCase()
          .indexOf(this.form.street_state.toLowerCase()) >= 0
      })
    },
    filteredMailingStateArray () {
      return this.stateList.filter((option) => {
        return option
          .toString()
          .toLowerCase()
          .indexOf(this.form.mailing_state.toLowerCase()) >= 0
      })
    },
    filteredEngine1MakeListArray () {
      return this.engineMakeList.filter((option) => {
        return option
          .toString()
          .toLowerCase()
          .indexOf(this.form.engine_1_make.toLowerCase()) >= 0
      })
    },
    filteredEngine2MakeListArray () {
      return this.engineMakeList.filter((option) => {
        return option
          .toString()
          .toLowerCase()
          .indexOf(this.form.engine_2_make.toLowerCase()) >= 0
      })
    },
    filteredEngine3MakeListArray () {
      return this.engineMakeList.filter((option) => {
        return option
          .toString()
          .toLowerCase()
          .indexOf(this.form.engine_3_make.toLowerCase()) >= 0
      })
    }
  },
  methods: {
    submitForm: function () {
    },
    // DROPDOWN FILTERS
    hullChanged: function (value) {
      if (value !== null) {
        this.form.dealership = value.dealership
        this.form.model = value.model
        this.form.date_purchased = null
      } else {
        if (this.isNRB) { this.form.dealership = '' }
        this.form.model = ''
        this.form.date_purchased = null
      }
    },
    flipAddressBlock: function () {
      if (this.same) {
        this.form.mailing_address = this.form.street_address
        this.form.mailing_city = this.form.street_city
        this.form.mailing_state = this.form.street_state
        this.form.mailing_zip = this.form.street_zip
      } else {
        this.form.mailing_address = ''
        this.form.mailing_city = ''
        this.form.mailing_state = ''
        this.form.mailing_zip = ''
      }
    },
    // =========================================================================================================
    // Helper methods
    formatJson: function (field) {
      return JSON.stringify(field, undefined, 2)
    },
    blurAddress: function () {
      if (this.same) {
        this.form.mailing_address = this.form.street_address
      }
    },
    blurCity: function () {
      if (this.same) {
        this.form.mailing_city = this.form.street_city
      }
    },
    blurState: function () {
      if (this.same) {
        this.form.mailing_state = this.form.street_state
      }
    },
    blurZip: function () {
      if (this.same) {
        this.form.mailing_zip = this.form.street_zip
      }
    },
    blurToggle: function () {
      if (this.same) {
        this.form.mailing_address = this.form.street_address
        this.form.mailing_city = this.form.street_city
        this.form.mailing_state = this.form.street_state
        this.form.mailing_zip = this.form.street_zip
      }
    },
    handleNone: function (value) {
      if (value.toLowerCase() === 'none' ||
      value.toLowerCase().split(' ').join('') === 'na' ||
      value.toLowerCase().split(' ').join('') === 'n/a') {
        return 'None'
      }
      return value
    },
    swapEngines: function (from, target) {
      var make = String(this.form['engine_' + from + '_make'])
      var model = String(this.form['engine_' + from + '_model'])
      var serial = String(this.form['engine_' + from + '_serial_number'])

      this.form['engine_' + target + '_make'] = make
      this.form['engine_' + target + '_model'] = model
      this.form['engine_' + target + '_serial_number'] = serial

      this.form['engine_' + from + '_make'] = ''
      this.form['engine_' + from + '_model'] = ''
      this.form['engine_' + from + '_serial_number'] = ''
    },
    cleanupEngines: function () {
      if (this.form.engine_3_make === null) {
        this.form.engine_3_make = ''
      }
      if (this.form.engine_2_make === null) {
        this.form.engine_2_make = ''
      }
      if (this.form.engine_1_make === null) {
        this.form.engine_1_make = ''
      }

      if (this.form.engine_2_make.length > 0 && this.form.engine_1_make.length === 0) {
        this.swapEngines('2', '1')
      }
      if (this.form.engine_3_make.length > 0 && this.form.engine_1_make.length === 0) {
        this.swapEngines('3', '1')
      }
      if (this.form.engine_3_make.length > 0 && this.form.engine_2_make.length === 0) {
        this.swapEngines('3', '2')
      }
    }
  },
  created () {
    if (this.debug) { console.log('NAVIGATED TO: Original Purchaser Registration Form') }

    this.id = this.$route.params.id.toString()
    this.$store.dispatch('oprHullsRead')
    this.$store.dispatch('userInfoRead')
      .then(response => {
        if (this.isNRB) {
          this.dealership = 'All Dealerships'
          this.form.dealership = ''
        } else {
          this.dealership = this.userInfo
          this.form.dealership = this.userInfo
        }
        // this.$nextTick(() => { this.$v.$reset() })
      })
  }
}
</script>

<style>
</style>
