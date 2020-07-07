<template>
  <div>
    <div class="columns is-multiline">
      <div class="column is-full formgroup">
        <h3 class="title">
          Contact Us Form {{ id }}
          <span class ="alignright" v-if="readOnly">
            <b-button @click="toPDF(id)" type="is-info"><b-icon icon="file-pdf-box" ></b-icon></b-button>
          </span>
        </h3>
        <hr class="has-background-white">
      </div>
      <div class="column is-12-tablet is-6-desktop">
        <b-field label="Name">
          <b-input v-model="form.name" readonly></b-input>
        </b-field>
      </div>
      <div class="column is-12-tablet is-6-desktop">
        <b-field label="Company">
          <b-input v-model="form.company" readonly></b-input>
        </b-field>
      </div>

      <div class="column is-12-tablet is-12-desktop">
        <b-field label="Address">
          <b-input v-model="form.address" readonly></b-input>
        </b-field>
      </div>

      <div class="column is-12-tablet is-6-desktop">
        <b-field label="City">
          <b-input v-model="form.city" readonly></b-input>
        </b-field>
      </div>
      <div class="column is-12-tablet is-3-desktop">
        <b-field label="State">
          <b-input v-model="form.state" readonly></b-input>
        </b-field>
      </div>
      <div class="column is-12-tablet is-3-desktop">
        <b-field label="Zip">
          <b-input v-model="form.zip" readonly></b-input>
        </b-field>
      </div>

      <div class="column is-6-tablet is-5-desktop">
        <b-field label="Phone">
          <b-input v-model="form.phone" readonly></b-input>
        </b-field>
      </div>
      <div class="column is-6-tablet is-7-desktop">
        <b-field label="E-Mail">
          <b-input v-model="form.email" readonly></b-input>
        </b-field>
      </div>

      <div class="column is-6-tablet is-4-desktop">
        <b-field label="Boat Model">
          <b-input v-model="form.boat_model" readonly></b-input>
        </b-field>
      </div>
      <div class="column is-6-tablet is-2-desktop">
        <b-field label="Boat Length">
          <b-input v-model="form.boat_length" readonly></b-input>
        </b-field>
      </div>
      <div class="column is-6-tablet is-3-desktop">
        <b-field label="Boat Use">
          <b-input v-model="form.boat_use" readonly></b-input>
        </b-field>
      </div>
      <div class="column is-6-tablet is-3-desktop">
        <b-field label="Hull Identification Number">
          <b-input v-model="form.hull_identification_number" readonly></b-input>
        </b-field>
      </div>

      <div class="column is-6-tablet is-3-desktop">
        <b-field label="Subject">
          <b-select v-model="form.subject">
            <option
              v-for="reason in recReasonList"
              :value="reason"
              :key="reason"
            >{{ reason }}
            </option>
          </b-select>
        </b-field>
      </div>
      <div class="column is-6-tablet is-9-desktop">
      </div>

      <div class="column is-12-tablet is-12-desktop">
        <b-field label="Comments">
          <b-input type="textarea" v-model="form.comments" readonly></b-input>
        </b-field>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'


export default {
  name: 'ContactUsForm',
  data () {
    return {
      originalSubject: '',
      originalDealership: '',
      api_count: 1,
      form: {
        id: 0,
        name: "John Doe",
        company: '',
        address: '',
        city: '',
        state: '',
        zip: '',
        phone: '',
        email: '',
        subject: '',
        boat_model: '',
        boat_length: '',
        boat_use: '',
        hull_serial_number: '',
        comments: '',
        dealership: '',
        sent: '',
        contact: '',
      },
    }
  },
  computed: {
    ...mapGetters([
      'contactList',
      'debug',
      'fileName',
      'isNRB',
      'recReasonList',
      'userInfo'
    ]),
    readOnly () {
      return !!this.id
    },
    notReadOnly () {
      return !this.id
    },
    changed () {
      if (!this.isNRB) {
        return true
      }
      if (this.submitted) {
        return false
      }
      return (this.originalSubject !== this.form.subject || this.originalDealership !== this.form.dealership)
    },
    sendMsg () {
      if (!this.isNRB) {
        return 'Ok'
      } else if (this.form.sent == 'Yes') {
        return 'Send Again'
      } else {
        return 'Send'
      }
    },
  },
  methods: {
    toPDF: function (id) {
      this.$store.dispatch('readContactUsPDF', id)
        .then(() => {
          window.open(this.fileName, '_blank')
        })
    },
  },
  created () {
    if (this.debug) { console.log('NAVIGATED TO: Contact Us Form') }
    this.id = this.$route.params.id.toString()
    this.$store.dispatch('contactListRead')
      .then(() => {
        var index = this._.findIndex(this.contactList, { id: this.id })
        this.form = this.contactList[index]
        this.originalSubject = this.form.subject
        this.originalDealership = this.form.dealership
        this.api_count = 0
      })
  },
  mounted () {
  }
}
</script>

<style>
</style>
