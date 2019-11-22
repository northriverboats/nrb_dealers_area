<template>
  <div>
    <div class="columns">
      <div class="column">
        <h1 class="title">Dealer Receipt Inspection Form</h1>
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
      id: '',
      dealership: '',
      form: {
        dealership: ''
      },
      searchTerm: ''
    }
  },
  computed: {
    ...mapGetters([
      'dealers',
      'debug',
      'driHulls',
      'dris',
      'fileName',
      'isNRB',
      'userInfo'
    ])
  },
  methods: {
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
</style>
