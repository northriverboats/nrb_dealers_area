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
      searchTerm: ''
    }
  },
  computed: {
    ...mapGetters([
      'dealers',
      'debug',
      'driHulls',
      'fileName',
      'isNRB',
      'userInfo'
    ])
  },
  methods: {
  },
  created () {
    if (this.debug) { console.log('NAVIGATED TO: Dealer Receipt Inspection Form') }

    this.$store.dispatch('userInfoRead')
      .then(() => {
        if (this.isNRB) {
          this.dealership = 'All Dealerships'
        } else {
          this.dealership = this.userInfo
          this.form.dealership = this.userInfo
        }
      })
    this.$store.dispatch('dealersRead')
    this.$store.dispatch('driHullsRead')
  }
}
</script>

<style>
</style>
