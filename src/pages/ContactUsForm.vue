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
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'


export default {
  name: 'ContactUsForm',
  data () {
    return {
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
        iemail: '',
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
      'userInfo'
    ]),
    readOnly () {
      return !!this.id
    },
    notReadOnly () {
      return !this.id
    },
  },
  methods: {
  },
  created () {
    if (this.debug) { console.log('NAVIGATED TO: Contact Us Form') }
    this.id = this.$route.params.id.toString()
    this.$store.dispatch('contactListRead')
      .then(() => {
        var index = this._.findIndex(this.contactList, { id: this.id })
        this.form = this.contactList[index]
        this.api_count = 0
      })
  }
}
</script>

<style>
</style>
