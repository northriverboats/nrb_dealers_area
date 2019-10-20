<template>
  <div>
    <div class="columns">
      <div class="column">
        <h3 class="title">Original Purchaser Registration for {{ dealership }}</h3>
        <hr class="has-background-white">
      </div>
    </div>

    <div class="columns is-multiline">
      <div class="column is-full">
        <h3 class="title">Street Address</h3>
        <hr class="has-background-white">
      </div>
      <div class="column is-full">
        <b-field label="Address">
          <b-input></b-input>
        </b-field>
      </div>
      <div class="column is-one-half-tablet is-one-half-desktop">
        <b-field label="City">
          <b-input></b-input>
        </b-field>
      </div>
      <div class="column is-one-half-tablet is-one-quarter-desktop">
        <b-field label="State">
          <b-autocomplete
            v-model="form.street_state"
            :data="filteredStateArray"
            placeholder="e.g. Oregon"
            :keep-first="true"
            @select="option => selected = option"
            >
            <template slot="empty">No results found</template>
          </b-autocomplete>
        </b-field>
      </div>
      <div class="column is-one-half-tablet is-one-quarter-desktop">
        <b-field label="Zip">
          <b-input></b-input>
        </b-field>
      </div>
    </div>

    <div class="columns">
      <div class="column">
        <h3 class="title">Mailing Address</h3>
        <hr class="has-background-white">
      </div>
    </div>

    <div class="columns">
      <div class="column">
        <h3 class="title">Boat Information</h3>
        <hr class="has-background-white">
      </div>
    </div>

    <div class="columns">
      <div class="column">
        <h3 class="title">Trailer Information</h3>
        <hr class="has-background-white">
      </div>
    </div>

    <div class="columns">
      <div class="column">
        <h3 class="title">Moter Information</h3>
        <hr class="has-background-white">
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'DRIform',
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
        date_purchased: '',
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
    filteredStateArray () {
      return this.stateList.filter((option) => {
        return option
          .toString()
          .toLowerCase()
          .indexOf(this.form.street_state.toLowerCase()) >= 0
      })
    }
  },
  methods: {
  },
  created () {
    if (this.debug) { console.log('NAVIGATED TO: Original Purchaser Registration Form') }

    this.id = this.$route.params.id.toString()
    this.$store.dispatch('oprHullsRead')
    this.$store.dispatch('userInfoRead')
      .then(response => {
        if (this.isNRB) {
          this.dealership = 'All Dealerships'
        } else {
          this.dealership = this.userInfo
          // this.form.dealership = this.userInfo
        }
        // this.$nextTick(() => { this.$v.$reset() })
      })
  }
}
</script>

<style>
</style>
