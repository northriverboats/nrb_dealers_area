<template>
  <div>
    <div class="columns is-multiline">
      <div class="column is-full formgroup">
        <h3 class="title">
          Dealer Receipt Inspection for {{ dealership }}
          <span class ="alignright" v-if="isEdit">
            <b-button @click="toPDF(id)" type="is-info"><b-icon icon="file-pdf-box" ></b-icon></b-button>
          </span>
        </h3>
        <hr class="has-background-white">
      </div>
      <div class="column is-6-tablet is-3-desktop" v-if="isNRB">
        <b-field label="Dealership">
          <b-input v-model="form.dealership"></b-input>
        </b-field>
      </div>
      <div class="column is-6-tablet is-3-desktop">
        <b-field label="Hull Serial Number"
          :type="{'is-danger': $v.form.hull_serial_number.$error}"
          :message="{'Street Address is required': $v.form.hull_serial_number.$error}"
        >
          <b-autocomplete
            v-model="form.hull_serial_number"
            :data="filteredHullsArray"
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
        <b-field label="Date Received"
          :type="{'is-danger': $v.other.date_received.$error}"
          :message="{'Purchase date is required': $v.other.date_received.$error}"
        >
          <b-datepicker
            is-info
            v-model="other.date_received"
            placeholder="Type or select a date..."
            icon="calendar-today"
            editable
            @input="changeReceivedDate"
            >
          </b-datepicker>
        </b-field>
      </div>

    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
// import { required, minLength, email, requiredIf, requiredUnless, or } from 'vuelidate/lib/validators'
import { required } from 'vuelidate/lib/validators'

export default {
  name: 'DRIform',
  data () {
    return {
      id: '',
      dealership: '',
      form: {
        dealership: '',
        hull_serial_number: '',
        model: '',
        date_received: ''
      },
      other: {
        date_purchased: null
      },
      searchTerm: ''
    }
  },
  // ===========================================================================================================
  // ===========================================================================================================
  // COMPUTED SECTION
  computed: {
    ...mapGetters([
      'dealers',
      'debug',
      'driHulls',
      'dris',
      'fileName',
      'isNRB',
      'userInfo'
    ]),
    isEdit () {
      return this.id
    },
    filteredHullsArray () {
      return this.driHulls.filter((option) => {
        return option.id
          .toString()
          .replace(/\s+/g, '')
          .toLowerCase()
          .indexOf(this.form.hull_serial_number.toLowerCase().replace(/\s+/g, '')) >= 0
      })
    }
  },
  // ===========================================================================================================
  // ===========================================================================================================
  // VALIDATION SECTION
  validations: {
    form: {
      hull_serial_number: {
        required
      }
    },
    other: {
      date_received: {
        required
      }
    }
  },
  // ===========================================================================================================
  // ===========================================================================================================
  // METHODS SECTION
  methods: {
    logMe (value) {
      console.log(value)
    },
    toPDF: function (id) {
      this.$store.dispatch('readDRIPDF', id)
        .then(() => {
          window.open(this.fileName, '_blank')
        })
    },
    changeReceivedDate () {
      if (this.other.date_received) {
        this.form.date_received = this.$moment(this.other.date_received).format('MM/DD/YYYY')
      } else {
        this.form.date_received = ''
      }
    },
    // DROPDOWN FILTERS
    hullChanged (value) {
      if (value !== null) {
        this.form.dealership = value.dealership
        this.form.model = value.model
        this.form.date_purchased = ''
        this.other.date_purchased = null
      } else {
        if (this.isNRB) { this.form.dealership = '' }
        this.form.model = ''
        this.form.date_purchased = ''
        this.other.date_purchased = null
      }
    }
  },
  created () {
    if (this.debug) { console.log('NAVIGATED TO: Dealer Receipt Inspection Form') }
    if (typeof (this.$route.params.id) !== 'undefined') {
      this.id = this.$route.params.id.toString()
      this.$store.dispatch('drisRead', this.id)
        .then(response => {
          this.form = this.dris.find(hull => hull.id === this.id)
        })
    }
    this.$store.dispatch('dealersRead')
    this.$store.dispatch('driHullsRead')

    this.$store.dispatch('userInfoRead')
      .then(() => {
        if (this.isNRB) {
          this.dealership = 'All Dealerships'
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
</style>
