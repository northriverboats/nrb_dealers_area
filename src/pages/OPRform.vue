<template>
  <div>
    <div class="columns is-multiline">
      <div class="column is-full formgroup">
        <h3 class="title">
          Original Purchaser Registration for {{ dealership }}
          <span class ="alignright" v-if="readOnly">
            <b-button @click="toPDF(id)" type="is-info"><b-icon icon="file-pdf-box" ></b-icon></b-button>
          </span>
        </h3>
        <hr class="has-background-white">
      </div>
      <div class="column is-6-tablet is-7-desktop" v-if="isNRB">
        <b-field label="Agency">
          <b-input v-model="form.agency" :readonly="readOnly"></b-input>
        </b-field>
      </div>
      <div class="column is-6-tablet is-5-desktop" v-if="isNRB">
        <b-field label="Contract">
          <b-input v-model="form.contract" :readonly="readOnly"></b-input>
        </b-field>
      </div>
      <div class="column is-6-tablet is-5-desktop required">
        <b-field label="First Name"
          :type="{'is-danger': $v.form.first_name.$error}"
          :message="{'First Name is Required': $v.form.first_name.$error}"
          v-bind:class="{ 'is-danger': $v.form.first_name.$error }"
        >
          <b-input v-model="form.first_name" :readonly="readOnly"></b-input>
        </b-field>
      </div>
      <div class="column is-6-tablet is-7-desktop required">
        <b-field label="Last Name"
          :type="{'is-danger': $v.form.last_name.$error}"
          :message="{'Last Name is Required': $v.form.last_name.$error}"
          v-bind:class="{ 'is-danger': $v.form.last_name.$error }"
        >
          <b-input v-model="form.last_name" :readonly="readOnly"></b-input>
        </b-field>
      </div>
      <div class="column is-6-tablet is-3-desktop">
        <b-field label="Home Phone Number"
          :type="{'is-danger': $v.form.phone_home.$error}"
          :message="{
            'Required if no home phone or email address': !$v.form.phone_home.$required
               && $v.form.phone_home.minLength
               && $v.form.phone_home.$error,
            'Not a valid phone number': !$v.form.phone_home.minLength}"
            v-bind:class="{ 'is-danger': $v.form.phone_home.$error }"
        >
          <b-input v-model="form.phone_home" v-cleave="masks.phoneNumber" :readonly="readOnly"></b-input>
        </b-field>
      </div>
      <div class="column is-6-tablet is-3-desktop">
        <b-field label="Work Phone Number"
          :type="{'is-danger': $v.form.phone_work.$error}"
          :message="{
            'Required if no work phone or email address': !$v.form.phone_work.$required
               && $v.form.phone_work.minLength
               && $v.form.phone_work.$error,
            'Not a valid phone number': !$v.form.phone_work.minLength}"
            v-bind:class="{ 'is-danger': $v.form.phone_work.$error }"
        >
          <b-input v-model="form.phone_work" v-cleave="masks.phoneNumber" :readonly="readOnly"></b-input>
        </b-field>
      </div>
      <div class="column is-6-tablet is-6-desktop">
        <b-field label="Email"
          :type="{'is-danger': $v.form.email.$error}"
          :message="{
            'Required if no home or work phone': !$v.form.email.$required
               && $v.form.email.email
               && $v.form.email.$error,
            'Not a valid email address': !$v.form.email.email}"
            v-bind:class="{ 'is-danger': $v.form.email.$error }"
        >
          <b-input v-model="form.email" :readonly="readOnly"></b-input>
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
        <b-field label="Address"
          :type="{'is-danger': $v.form.street_address.$error}"
          :message="{'Street Address is required': $v.form.street_address.$error}"
          v-bind:class="{ 'is-danger': $v.form.street_address.$error }"
        >
          <b-input v-model="form.street_address" @blur="blurAddress" :readonly="readOnly"></b-input>
        </b-field>
      </div>
      <div class="column is-5-tablet is-6-desktop">
        <b-field label="City"
          :type="{'is-danger': $v.form.street_city.$error}"
          :message="{'City is required': $v.form.street_city.$error}"
          v-bind:class="{ 'is-danger': $v.form.street_city.$error }"
        >
          <b-input v-model="form.street_city" @blur="blurCity" :readonly="readOnly"></b-input>
        </b-field>
      </div>
      <div class="column is-5-tablet is-3-desktop">
        <b-field label="State"
          :type="{'is-danger': $v.form.street_state.$error}"
          :message="{'State is required': $v.form.street_state.$error}"
          v-bind:class="{ 'is-danger': $v.form.street_state.$error }"
        >
          <b-autocomplete
            v-model="form.street_state"
            :data="filteredStreetStateArray"
            :readonly="readOnly"
            placeholder="e.g. Oregon"
            :keep-first="true"
            :open-on-focus="true"
            @input="blurState"
            >
            <template slot="empty">No results found</template>
          </b-autocomplete>
        </b-field>
      </div>
      <div class="column is-2-tablet is-3-desktop">
        <b-field label="Zip"
          :type="{'is-danger': $v.form.street_zip.$error}"
          :message="{'Postal Code is required': !$v.form.street_zip.zip,
            'Postal Code should be blank': !$v.form.street_zip.notApplicable
          }"
          v-bind:class="{ 'is-danger': $v.form.street_zip.$error }"
        >
          <b-input v-model="form.street_zip" @blur="blurZip" :readonly="readOnly"></b-input>
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
        <b-switch v-model="same" type="is-success" @input="flipAddressBlock" :disabled="readOnly">
          {{ isSame }}
        </b-switch>
      </div>
      <div class="column is-full" v-if="!same">
        <b-field label="Address"
          :type="{'is-danger': $v.form.mailing_address.$error}"
          :message="{'Street Address is required': $v.form.mailing_address.$error}"
          v-bind:class="{ 'is-danger': $v.form.mailing_address.$error }"
        >
          <b-input v-model="form.mailing_address" :readonly="readOnly"></b-input>
        </b-field>
      </div>
      <div class="column is-5-tablet is-6-desktop" v-if="!same">
        <b-field label="City"
          :type="{'is-danger': $v.form.mailing_city.$error}"
          :message="{'Street Address is required': $v.form.mailing_city.$error}"
          v-bind:class="{ 'is-danger': $v.form.mailing_city.$error }"
        >
          <b-input v-model="form.mailing_city" :readonly="readOnly"></b-input>
        </b-field>
      </div>
      <div class="column is-5-tablet is-3-desktop" v-if="!same">
        <b-field label="State"
          :type="{'is-danger': $v.form.mailing_state.$error}"
          :message="{'Street Address is required': $v.form.mailing_state.$error}"
          v-bind:class="{ 'is-danger': $v.form.mailing_state.$error }"
        >
          <b-autocomplete
            v-model="form.mailing_state"
            :data="filteredMailingStateArray"
            :readonly="readOnly"
            placeholder="e.g. Oregon"
            :keep-first="true"
            :open-on-focus="true"
            >
            <template slot="empty">No results found</template>
          </b-autocomplete>
        </b-field>
      </div>
      <div class="column is-2-tablet is-3-desktop" v-if="!same">
        <b-field label="Zip"
          :type="{'is-danger': $v.form.mailing_zip.$error}"
          :message="{'Postal Code is required': !$v.form.mailing_zip.zip,
            'Postal Code should be blank': !$v.form.mailing_zip.notApplicable
          }"
          v-bind:class="{ 'is-danger': $v.form.mailing_zip.$error }"
        >
          <b-input v-model="form.mailing_zip" :readonly="readOnly"></b-input>
        </b-field>
      </div>
    </div>

    <!-- BOAT INFO -->
    <div class="columns is-multiline">
      <div class="column is-full formgroup">
        <h3 class="title">Boat Information</h3>
        <hr class="has-background-white">
      </div>
      <div class="column is-6-tablet is-3-desktop">
        <b-field label="Hull Serial Number"
          :type="{'is-danger': $v.form.hull_serial_number.$error}"
          :message="{'Street Address is required': $v.form.hull_serial_number.$error}"
          v-bind:class="{ 'is-danger': $v.form.hull_serial_number.$error }"
        >
          <b-autocomplete
            v-model="form.hull_serial_number"
            :data="filteredHullsArray"
            :readonly="readOnly"
            field="id"
            placeholder="e.g. NRB 21045 B818"
            :keep-first="true"
            :open-on-focus="true"
            @select="hullChanged"
            >
            <template slot="empty">No results found</template>
          </b-autocomplete>
        </b-field>
      </div>
      <div class="column is-6-tablet is-3-desktop">
        <b-field label="Model">
          <b-input v-model="form.model" readonly></b-input>
        </b-field>
      </div>
      <div class="column is-6-tablet is-3-desktop">
        <b-field label="Date of Purchase Deposit"
          :type="{'is-danger': $v.other.date_deposit.$error}"
          :message="{'Purchase date is required': $v.other.date_deposit.$error}"
          v-bind:class="{ 'is-danger': $v.other.date_deposit.$error }"
        >
          <span v-if="readOnly">
            <b-input v-model="other.date_deposit" readonly></b-input>
          </span>
          <span v-else>
            <b-datepicker
              is-info
              v-model="other.date_deposit"
              :date-formatter="dateFormatter"
              :min-date="date_deposit_start"
              :max-date="date_deposit_end"
              placeholder="Type or select a date..."
              icon="calendar-today"
              @input="changeDepositDate"
              >
            </b-datepicker>
          </span>
        </b-field>
      </div>
      <div class="column is-6-tablet is-3-desktop">
        <b-field label="Date of Final Delivery"
          :type="{'is-danger': $v.other.date_delivered.$error}"
          :message="{'Purchase date is required': $v.other.date_delivered.$error}"
          v-bind:class="{ 'is-danger': $v.other.date_delivered.$error }"
        >
          <span v-if="readOnly">
            <b-input v-model="other.date_delivered" readonly></b-input>
          </span>
          <span v-else>
            <b-datepicker
              is-info
              v-model="other.date_delivered"
              :date-formatter="dateFormatter"
              :min-date="date_delivered_start"
              :max-date="date_delivered_end"
              placeholder="Type or select a date..."
              icon="calendar-today"
              @input="changeDeliveredDate"
              >
            </b-datepicker>
          </span>
        </b-field>
      </div>
      <div class="column is-6-tablet is-5-desktop">
        <b-field label="Dealership">
          <b-input v-model="form.dealership" readonly></b-input>
        </b-field>
      </div>
      <div class="column is-6-tablet is-7-desktop">
        <b-field
          label="Salesperson"
          :type="{'is-danger': $v.form.salesperson.$error}"
          :message="{'Salesperson is required': $v.form.salesperson.$error}"
          v-bind:class="{ 'is-danger': $v.form.salesperson.$error }"
        >
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
        <b-field label="Trailer Model"
          :type="{'is-danger': $v.form.trailer_model.$error}"
          :message="{'Trailer Model is required': $v.form.trailer_model.$error}"
          v-bind:class="{ 'is-danger': $v.form.trailer_model.$error }"
        >
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
        <b-field label="Trailer Serial"
          :type="{'is-danger': $v.form.trailer_serial_number.$error}"
          :message="{'Trailer Serial Number is required': $v.form.trailer_serial_number.$error}"
          v-bind:class="{ 'is-danger': $v.form.trailer_serial_number.$error }"
        >
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
        <b-field label="Engine 1 Make"
          :type="{'is-danger': $v.form.engine_1_make.$error}"
          :message="{'Make is required': $v.form.engine_1_make.$error}"
          v-bind:class="{ 'is-danger': $v.form.engine_1_make.$error }"
        >
          <b-autocomplete
            v-model="form.engine_1_make"
            :data="filteredEngine1MakeListArray"
            placeholder="e.g. Yamaha"
            :keep-first="true"
            :open-on-focus="true"
            >
            <template slot="empty">No results found</template>
          </b-autocomplete>
        </b-field>
      </div>
      <div class="column is-4-tablet is-4-desktop">
        <b-field label="Engine 1 Model"
          :type="{'is-danger': $v.form.engine_1_model.$error}"
          :message="{'Engine Model is required': $v.form.engine_1_model.$error}"
          v-bind:class="{ 'is-danger': $v.form.engine_1_model.$error }"
        >
          <b-input v-model="form.engine_1_model"></b-input>
        </b-field>
      </div>
      <div class="column is-4-tablet is-4-desktop">
        <b-field label="Engine 1 Serial Number"
          :type="{'is-danger': $v.form.engine_1_serial_number.$error}"
          :message="{'Engine Serial Number is required': $v.form.engine_1_serial_number.$error}"
          v-bind:class="{ 'is-danger': $v.form.engine_1_serial_number.$error }"
        >
          <b-input v-model="form.engine_1_serial_number"></b-input>
        </b-field>
      </div>

      <div class="column is-4-tablet is-4-desktop">
        <b-field label="Engine 2 Make"
          :type="{'is-danger': $v.form.engine_2_make.$error}"
          :message="{'Make is required': $v.form.engine_2_make.$error}"
          v-bind:class="{ 'is-danger': $v.form.engine_2_make.$error }"
        >
          <b-autocomplete
            v-model="form.engine_2_make"
            :data="filteredEngine2MakeListArray"
            placeholder="e.g. Yamaha"
            :keep-first="true"
            :open-on-focus="true"
            >
            <template slot="empty">No results found</template>
          </b-autocomplete>
        </b-field>
      </div>
      <div class="column is-4-tablet is-4-desktop">
        <b-field label="Engine 2 Model"
          :type="{'is-danger': $v.form.engine_2_model.$error}"
          :message="{'Engine Model is required': $v.form.engine_2_model.$error}"
          v-bind:class="{ 'is-danger': $v.form.engine_2_model.$error }"
        >
          <b-input v-model="form.engine_2_model"></b-input>
        </b-field>
      </div>
      <div class="column is-4-tablet is-4-desktop">
        <b-field label="Engine 2 Serial Number"
          :type="{'is-danger': $v.form.engine_2_serial_number.$error}"
          :message="{'Engine Serial Number is required': $v.form.engine_2_serial_number.$error}"
          v-bind:class="{ 'is-danger': $v.form.engine_2_serial_number.$error }"
        >
          <b-input v-model="form.engine_2_serial_number"></b-input>
        </b-field>
      </div>

      <div class="column is-4-tablet is-4-desktop">
        <b-field label="Engine 3 Make"
          :type="{'is-danger': $v.form.engine_3_make.$error}"
          :message="{'Make is required': $v.form.engine_3_make.$error}"
          v-bind:class="{ 'is-danger': $v.form.engine_3_make.$error }"
        >
          <b-autocomplete
            v-model="form.engine_3_make"
            :data="filteredEngine3MakeListArray"
            placeholder="e.g. Yamaha"
            :keep-first="true"
            :open-on-focus="true"
            >
            <template slot="empty">No results found</template>
          </b-autocomplete>
        </b-field>
      </div>
      <div class="column is-4-tablet is-4-desktop">
        <b-field label="Engine 3 Model"
          :type="{'is-danger': $v.form.engine_3_model.$error}"
          :message="{'Engine Model is required': $v.form.engine_3_model.$error}"
          v-bind:class="{ 'is-danger': $v.form.engine_3_model.$error }"
        >
          <b-input v-model="form.engine_3_model"></b-input>
        </b-field>
      </div>
      <div class="column is-4-tablet is-4-desktop">
        <b-field label="Engine 3 Serial Number"
          :type="{'is-danger': $v.form.engine_3_serial_number.$error}"
          :message="{'Engine Serial Number is required': $v.form.engine_3_serial_number.$error}"
          v-bind:class="{ 'is-danger': $v.form.engine_3_serial_number.$error }"
        >
          <b-input v-model="form.engine_3_serial_number"></b-input>
        </b-field>
      </div>
    </div>

    <!-- BUTTONS -->
    <div class="columns is-multiline">
      <div class="column is-full">
      </div>
      <div class="column is-full has-text-right">
        <span v-if="readOnly">
          <b-button @click="$router.go(-1)" type="is-info">Back</b-button>
        </span>
        <span v-else>
          <b-button @click="$router.go(-1)" outlined>Cancel</b-button>
          <b-button @click="submitForm" type="is-dark" inverted :disabled="submit_locked">Submit</b-button>
        </span>
      </div>
      <div class="column is-full">
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { required, minLength, email, requiredIf, requiredUnless, or } from 'vuelidate/lib/validators'
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

// Validators
const atLeastNone = (value) => value.toLowerCase() === 'none' ||
  value.toLowerCase().split(' ').join('') === 'na' ||
  value.toLowerCase().split(' ').join('') === 'n/a' ||
  value.length > 8

export default {
  name: 'OPRform',
  directives: { cleave },
  data () {
    return {
      selected: null,
      name: '',
      id: '',
      dealership: 0,
      date_delivered_start: null,
      date_delivered_end: null,
      date_deposit_start: null,
      date_deposit_end: null,
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
        date_delivered: '',
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
      other: {
        date_delivered: null,
        date_deposit: null
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
        },
        phoneNumber: {
          // phone: true,
          // phoneRegionCode: 'US'
          numericOnly: true,
          blocks: [0, 3, 3, 4],
          delimiters: ['(', ') ', '-']
        }
      }
    }
  },
  // ===========================================================================================================
  // ===========================================================================================================
  // VALIDATION SECTION
  validations: {
    form: {
      first_name: {
        required: required
      },
      last_name: {
        required: required
      },
      phone_home: {
        required: or(requiredUnless('phone_work'), requiredUnless('email')),
        minLength: minLength(14)
      },
      phone_work: {
        required: or(requiredUnless('phone_home'), requiredUnless('email')),
        minLength: minLength(14)
      },
      email: {
        required: or(requiredUnless('phone_home'), requiredUnless('phone_work')),
        email: email
      },
      street_address: {
        required
      },
      street_city: {
        required
      },
      street_state: {
        required
      },
      street_zip: {
        notApplicable (value) {
          if (this.form.street_state !== 'Not Applicable') {
            return true
          }
          return value.toString().length === 0
        },
        zip (value) {
          if (!this.form.street_state) {
            return false
          }
          let abvr = this.form.street_state.toString().toLowerCase().split(' ').join('').substr(0, 4)
          if (abvr === 'nota') {
            return true
          }
          if (abvr.match('albe|brit|mani|newb|newf|nova|nuna|nort|onta|prin|queb|sask|yuko')) {
            let ca = /^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/
            return ca.test(value)
          }
          let us = /(^\d{5}$)|(^\d{5}-\d{4}$)/
          return us.test(value)
        }
      },
      mailing_address: {
        required: requiredIf(function () {
          return !this.same
        })
      },
      mailing_city: {
        required: requiredIf(function () {
          return !this.same
        })
      },
      mailing_state: {
        required: requiredIf(function () {
          return !this.same
        })
      },
      mailing_zip: {
        notApplicable (value) {
          if (this.same) {
            return true
          }
          if (this.form.mailing_state !== 'Not Applicable') {
            return true
          }
          return value.toString().length === 0
        },
        zip (value) {
          if (this.same) {
            return true
          }
          if (!this.form.mailing_state) {
            return false
          }
          let abvr = this.form.mailing_state.toLowerCase().split(' ').join('').substr(0, 4)
          if (abvr === 'nota') {
            return true
          }
          if (abvr.match('albe|brit|mani|newb|newf|nova|nuna|nort|onta|prin|queb|sask|yuko')) {
            let ca = /^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/
            return ca.test(value)
          }
          let us = /(^\d{5}$)|(^\d{5}-\d{4}$)/
          return us.test(value)
        }
      },
      hull_serial_number: {
        required
      },
      salesperson: {
        required
      },
      trailer_model: {
        required
      },
      trailer_serial_number: {
        atLeastNone
      },
      engine_1_make: {
        required (v) {
          if (v) {
            return true
          }
          return !(this.form.engine_1_model || this.form.engine_1_serial_number)
        }
      },
      engine_1_model: {
        required: requiredIf('engine_1_make')
      },
      engine_1_serial_number: {
        required: requiredIf('engine_1_make')
      },
      engine_2_make: {
        required (v) {
          if (v) {
            return true
          }
          return !(this.form.engine_2_model || this.form.engine_2_serial_number)
        }
      },
      engine_2_model: {
        required: requiredIf('engine_2_make')
      },
      engine_2_serial_number: {
        required: requiredIf('engine_2_make')
      },
      engine_3_make: {
        required (v) {
          if (v) {
            return true
          }
          return !(this.form.engine_3_model || this.form.engine_3_serial_number)
        }
      },
      engine_3_model: {
        required: requiredIf('engine_3_make')
      },
      engine_3_serial_number: {
        required: requiredIf('engine_3_make')
      }
    },
    other: {
      date_delivered: {
        required
      },
      date_deposit: {
        required
      }
    }
  },
  // ===========================================================================================================
  // ===========================================================================================================
  // COMPUTED SECTION
  computed: {
    ...mapGetters([
      'debug',
      'engineMakeList',
      'fileName',
      'isNRB',
      'oprHulls',
      'oprs',
      'stateList',
      'trailerList',
      'userInfo'
    ]),
    readOnly () {
      return !!this.id
    },
    notReadOnly () {
      return !this.id
    },
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
  // ===========================================================================================================
  // ===========================================================================================================
  // METHODS SECTION
  methods: {
    toPDF: function (id) {
      this.$store.dispatch('readOPRPDF', id)
        .then(() => {
          window.open(this.fileName, '_blank')
        })
    },
    dateFormatter (date) {
      if (date) {
        return this.$moment(date).format('MM/DD/YYYY')
      }
      return ''
    },
    setDateRanges (hull) {
      // get build_year as "20" + model_year_decade and handle corner case for I920 = 2019 not 2029
      // this.$moment() expects yyyy-m-d or yyyy-mm-dd for input
      var buildYear = ''
      if (hull.charAt(11) === '9' & hull.charAt(13) === '0') {
        buildYear = '20' + (hull.charAt(12) - 1) + hull.charAt(11)
      } else {
        buildYear = '20' + hull.charAt(12) + hull.charAt(11)
      }
      var buildMonth = ('0' + (hull.charCodeAt(10) - 64)).slice(-2)
      var buildDay = '01'
      var buildDate = buildYear + '-' + buildMonth + '-' + buildDay

      this.date_deposit_start = this.$moment(buildDate).subtract(12, 'months').toDate()
      this.date_deposit_end = this.$moment(new Date()).toDate()
      this.date_delivered_start = this.$moment(buildDate).subtract(1, 'months').toDate()
      this.date_delivered_end = this.$moment(new Date()).toDate()
    },
    changeDepositDate () {
      if (this.other.date_deposit) {
        this.form.date_deposit = this.$moment(this.other.date_deposit).format('YYYY-MM-DD')
      } else {
        this.form.date_deposit = ''
      }
    },
    changeDeliveredDate () {
      if (this.other.date_delivered) {
        this.form.date_delivered = this.$moment(this.other.date_delivered).format('YYYY-MM-DD')
      } else {
        this.form.date_delivered = ''
      }
    },
    submitForm () {
      this.submit_locked = true
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.formErrors()
        return
      }
      this.submit_locked = false
      
      let formData = new FormData()
      for (var key in this.form) {
        formData.append(key, this.form[key])
      }

      this.$store.dispatch('oprCreate', this.form)
        // eslint-disable-next-line
        .then(response => {
          // show notification that will last 2 seconds
          const notif = this.$buefy.notification.open({
            duration: 2000,
            message: 'Form has been saved',
            type: 'is-success'
          })
          // set timer to close window after 2 seconds
          notif.$on('close', () => {
            // this.$router.go(-1)
            console.log()
          })
        })
    },
    formErrors () {
      this.$buefy.notification.open({
        duration: 2500,
        message: "There are errors on this form\nThey will be marked in red.",
        position: 'is-bottom',
        type: 'is-danger',
        hasIcon: true,
        closable: false
      })
      setTimeout(() => {
        this.submit_locked = false
      }, 2400)
    },
    // DROPDOWN FILTERS
    hullChanged (value) {
      if (value !== null) {
        this.form.dealership = value.dealership
        this.form.model = value.model
        this.form.date_delivered = ''
        this.other.date_delivered = null
      } else {
        if (this.isNRB) { this.form.dealership = '' }
        this.form.model = ''
        this.form.date_delivered = ''
        this.other.date_delivered = null
      }
      if (value !== null) {
        this.setDateRanges(value.id)
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

    if (typeof (this.$route.params.id) !== 'undefined' && this.$route.params.id.toString() !== '0') {
      this.id = this.$route.params.id.toString()
      this.$store.dispatch('oprsRead', this.id)
        // eslint-disable-next-line
        .then(response => { 
          this.form = this.oprs.find(hull => hull.id === this.id)
          this.other.date_delivered = this.$moment(this.form.date_delivered).format('MM/DD/YYYY')
          this.other.date_deposit = this.$moment(this.form.date_deposit).format('MM/DD/YYYY')
        })
    }
    this.$store.dispatch('oprHullsRead')
    this.$store.dispatch('userInfoRead')
      // eslint-disable-next-line
      .then(response => {
        if (this.isNRB) {
          this.dealership = 'All Dealerships'
          this.form.dealership = ''
        } else {
          this.dealership = this.userInfo
          this.form.dealership = this.userInfo
        }
      })
  }
}
</script>

<style>
div.formgroup h3 {
  margin-bottom: 1rem !important;
}
.alignleft {
  float: left;
}
.alignright {
  float: right;
}
.required label::after {
  content: " *";
  color: red;
}
.is-danger label {
  color: red;
}
</style>
