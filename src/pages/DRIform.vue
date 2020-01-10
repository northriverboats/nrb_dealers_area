<template>
  <div>
    <div class="columns is-multiline">
      <div class="column is-full formgroup">
        <h3 class="title">
          Dealer Receipt Inspection for {{ dealership }}
          <span class ="alignright" v-if="readOnly">
            <b-button @click="toPDF(id)" type="is-info"><b-icon icon="file-pdf-box" ></b-icon></b-button>
          </span>
        </h3>
        <hr class="has-background-white">
      </div>
      <div class="column is-6-tablet is-3-desktop" v-if="isNRB">
        <b-field label="Dealership">
          <b-input v-model="form.dealership" readonly></b-input>
        </b-field>
      </div>
      <div class="column is-6-tablet is-3-desktop required">
        <b-field label="Hull Serial Number"
          :type="{'is-danger': $v.form.hull_serial_number.$error}"
          :message="{'Hull Serial is required': $v.form.hull_serial_number.$error}"
          v-bind:class="{ 'is-danger': $v.form.hull_serial_number.$error }"
        >
          <span v-if="readOnly">
            <b-input v-model="form.hull_serial_number" :readonly="readOnly"></b-input>
          </span>
          <span v-else>
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
          </span>
        </b-field>
      </div>
      <div class="column is-6-tablet is-3-desktop">
        <b-field label="Model">
          <b-input v-model="form.model" readonly></b-input>
        </b-field>
      </div>
      <div class="column is-6-tablet is-3-desktop required">
        <b-field label="Date Received"
          :type="{'is-danger': $v.other.date_received.$error}"
          :message="{'Received date is required': $v.other.date_received.$error}"
          v-bind:class="{ 'is-danger': $v.other.date_received.$error }"
        >
          <span v-if="readOnly">
            <b-input v-model="other.date_received" readonly></b-input>
          </span>
          <span v-else>
            <b-datepicker
              is-info
              v-model="other.date_received"
              placeholder="Type or select a date..."
              icon="calendar-today"
              editable
              :date-formatter="dateFormatter"
              :min-date="date_received_start"
              :max-date="date_received_end"
              @input="changeReceivedDate"
              :readonly="readOnly"
              >
            </b-datepicker>
          </span>
        </b-field>
      </div>
      <div class="column is-12-tablet is-12-desktop required">
        <b-field label="Defects Found"
          :type="{'is-danger': $v.form.defects_found.$error}"
          :message="{'Enter N/A or None if no defects': $v.form.defects_found.$error}"
          v-bind:class="{ 'is-danger': $v.form.defects_found.$error }"
        >
          <b-input
            maxlength="800"
            type="textarea"
            v-model="form.defects_found"
            v-on:blur="defectBlur"
            :readonly="readOnly"
          >
          </b-input>
        </b-field>
      </div>
      <div class="column is-12-tablet is-12-desktop required">
        <b-field label="Missing Items"
          :type="{'is-danger': $v.form.missing_items.$error}"
          :message="{'Enter N/A or None if no defects': $v.form.missing_items.$error}"
          v-bind:class="{ 'is-danger': $v.form.missing_items.$error }"
        >
          <b-input
            maxlength="800"
            type="textarea"
            v-model="form.missing_items"
            v-on:blur="missingBlur"
            :readonly="readOnly"
          >
          </b-input>
        </b-field>
      </div>
      <div class="column is-12-tablet is-6-desktop required">
        <b-field label="Checked By"
          :type="{'is-danger': $v.form.checked_by.$error}"
          :message="{'Enter inspectors name': $v.form.checked_by.$error}"
          v-bind:class="{ 'is-danger': $v.form.checked_by.$error }"
        >
          <b-input v-model="form.checked_by" :readonly="readOnly"></b-input>
        </b-field>
      </div>
      <div class="column is-12-tablet is-6-desktop required">
        <b-field label="Email"
          :type="{'is-danger': $v.form.email_address.$error}"
          :message="{'Enter a valid emaill address': $v.form.email_address.$error}"
          v-bind:class="{ 'is-danger': $v.form.email_address.$error }"
        >
          <b-input v-model="form.email_address" :readonly="readOnly"></b-input>
        </b-field>
      </div>
      <div class="column is-12-tablet is-12-desktop">
        <b-field label="Files" v-if="readOnly">
            <a v-if="form.file_1" :href="form.file_1" target="_blank"><span class="tag is-primary">{{ fileFormatter(form.file_1) }}</span></a>&nbsp;
            <a v-if="form.file_2" :href="form.file_2" target="_blank"><span class="tag is-primary">{{ fileFormatter(form.file_2) }}</span></a>&nbsp;
            <a v-if="form.file_3" :href="form.file_3" target="_blank"><span class="tag is-primary">{{ fileFormatter(form.file_3) }}</span></a>&nbsp;
            <a v-if="form.file_4" :href="form.file_4" target="_blank"><span class="tag is-primary">{{ fileFormatter(form.file_4) }}</span></a>&nbsp;
            <a v-if="form.file_5" :href="form.file_5" target="_blank"><span class="tag is-primary">{{ fileFormatter(form.file_5) }}</span></a>&nbsp;
        </b-field>
        <div v-else>
          <b-field label="Files">
            <div class="file">
              <b-upload
                v-model="file_1"
                accept="image/*,application/pdf,application/vnd.ms-excel,application/msword"
                :disabled="!!file_1"
                ref="file_1"
              >
                <a class="button is-primary">
                    <b-icon icon="upload"></b-icon>
                    <span>Click to upload</span>
                </a>
              </b-upload>
              <span class="file-name" style="color: black; background-color: white;" v-if="file_1">
                {{ file_1.name }}
              </span>
            </div>
          </b-field>
          <b-field v-if="file_1">
            <div class="file">
              <b-upload
                v-model="file_2"
                accept="image/*,application/pdf,application/vnd.ms-excel,application/msword"
                :disabled="!!file_2"
              >
                <a class="button is-primary">
                    <b-icon icon="upload"></b-icon>
                    <span>Click to upload</span>
                </a>
              </b-upload>
              <span class="file-name" style="color: black; background-color: white;" v-if="file_2">
                {{ file_2.name }}
              </span>
            </div>
          </b-field>
          <b-field v-if="file_2">
            <div class="file">
              <b-upload
                v-model="file_3"
                accept="image/*,application/pdf,application/vnd.ms-excel,application/msword"
                :disabled="!!file_3"
              >
                <a class="button is-primary">
                    <b-icon icon="upload"></b-icon>
                    <span>Click to upload</span>
                </a>
              </b-upload>
              <span class="file-name" style="color: black; background-color: white;" v-if="file_3">
                {{ file_3.name }}
              </span>
            </div>
          </b-field>
          <b-field v-if="file_3">
            <div class="file">
              <b-upload
                v-model="file_4"
                accept="image/*,application/pdf,application/vnd.ms-excel,application/msword"
                :disabled="!!file_4"
                ref="file1"
              >
                <a class="button is-primary">
                    <b-icon icon="upload"></b-icon>
                    <span>Click to upload</span>
                </a>
              </b-upload>
              <span class="file-name" style="color: black; background-color: white;" v-if="file_4">
                {{ file_4.name }}
              </span>
            </div>
          </b-field>
          <b-field v-if="file_4">
            <div class="file">
              <b-upload
                v-model="file_5"
                accept="image/*,application/pdf,application/vnd.ms-excel,application/msword"
                :disabled="!!file_5"
                ref="file1"
              >
                <a class="button is-primary">
                    <b-icon icon="upload"></b-icon>
                    <span>Click to upload</span>
                </a>
              </b-upload>
              <span class="file-name" style="color: black; background-color: white;" v-if="file_5">
                {{ file_5.name }}
              </span>
            </div>
          </b-field>
          <b-field v-if="file_1">
            <b-button
              icon-left="delete"
              type="is-primary"
              @click="delUpload"
            >
              Clear Upload
            </b-button>
          </b-field>
        </div>
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
// import { required, minLength, email, requiredIf, requiredUnless, or } from 'vuelidate/lib/validators'
import { required, email } from 'vuelidate/lib/validators'

export default {
  name: 'DRIform',
  data () {
    return {
      id: '',
      closeTimeout: null,
      dealership: '',
      date_received_start: null,
      date_received_end: null,
      file_1: null,
      file_2: null,
      file_3: null,
      file_4: null,
      file_5: null,
      form: {
        dealership: '',
        defects_found: '',
        hull_serial_number: '',
        missing_items: '',
        model: '',
        checked_by: '',
        email_address: '',
        date_received: ''
      },
      other: {
        date_received: null
      },
      searchTerm: '',
      submit_locked: false
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
    readOnly () {
      return !!this.id
    },
    notReadOnly () {
      return !this.id
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
      },
      defects_found: {
        validDescription (value) {
          if (value.trim().toLowerCase() === 'none') {
            return true
          }
          if (value.trim().toLowerCase() === 'n/a') {
            return true
          }
          return value.toString().length > 8
        }
      },
      missing_items: {
        validDescription (value) {
          if (value.trim().toLowerCase() === 'none') {
            return true
          }
          if (value.trim().toLowerCase() === 'n/a') {
            return true
          }
          return value.toString().length > 8
        }
      },
      checked_by: {
        required
      },
      email_address: {
        required,
        email: email
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
    delUpload () {
      if (this.file_5) {
        this.file_5 = null
      } else if (this.file_4) {
        this.file_4 = null
      } else if (this.file_3) {
        this.file_3 = null
      } else if (this.file_2) {
        this.file_2 = null
      } else {
        this.file_1 = null
      }
    },
    logMe (value) {
      console.log(value)
    },
    toPDF: function (id) {
      this.$store.dispatch('readDRIPDF', id)
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
    fileFormatter (file) {
      if (file) {
        var parts = file.split('/')
        return parts[parts.length - 1]
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

      this.date_received_start = this.$moment(buildDate).subtract(12, 'months').toDate()
      this.date_received_end = this.$moment(new Date()).toDate()
    },
    changeReceivedDate () {
      if (this.other.date_received) {
        this.form.date_received = this.$moment(this.other.date_received).format('MM/DD/YYYY')
      } else {
        this.form.date_received = ''
      }
    },
    submitForm () {
      this.submit_locked = true
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.formErrors()
        return
      }

      let formData = new FormData()
      for (var key in this.form) {
        formData.append(key, this.form[key])
      }
      for (var i = 0; i < 5; i++) {
        if (this['file_' + i]) {
          formData.append('files[' + i + ']', this['file_' + i])
        }
      }

      this.$store.dispatch('drisCreate', formData)
        .then(response => {
          // show notification that will last 2 seconds
          this.$buefy.notification.open({
            message: 'Form has been saved',
            type: 'is-success'
          })
          // set timer to close window after 2 seconds
          this.closeTimeout = setTimeout(() => {
            this.$router.go(-1)
          }, 2000)
        })
    },
    formErrors () {
      this.$buefy.notification.open({
        duration: 2500,
        message: `There are errors on this form`,
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
        this.form.date_received = ''
        this.other.date_received = null
      } else {
        if (this.isNRB) { this.form.dealership = '' }
        this.form.model = ''
        this.form.date_received = ''
        this.other.date_received = null
      }
      if (value !== null) {
        this.setDateRanges(value.id)
      }
    },
    defectBlur () {
      if (this.form.defects_found.trim().toLowerCase() === 'none') {
        this.form.defects_found = 'None'
      }
      if (this.form.defects_found.trim().toLowerCase() === 'n/a') {
        this.form.defects_found = 'N/A'
      }
    },
    missingBlur () {
      if (this.form.missing_items.trim().toLowerCase() === 'none') {
        this.form.missing_items = 'None'
      }
      if (this.form.missing_items.trim().toLowerCase() === 'n/a') {
        this.form.missing_items = 'N/A'
      }
    }
  },
  created () {
    if (this.debug) { console.log('NAVIGATED TO: Dealer Receipt Inspection Form') }
    if (typeof (this.$route.params.id) !== 'undefined' && this.$route.params.id.toString() !== '0') {
      this.id = this.$route.params.id.toString()
      this.$store.dispatch('drisRead', this.id)
        .then(response => {
          this.form = this.dris.find(hull => hull.id === this.id)
          this.other.date_received = this.$moment(this.form.date_received).format('MM/DD/YYYY')
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
  },
  beforeDestroy () {
    if (this.closeTimeout) {
      clearTimeout(this.closeTimeout)
    }
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
