<template>
  <div>
    <div class="columns">
      <div class="column">
        <h1 class="title">Original Purchaser Registration Form</h1>
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
      'debug',
      'fileName',
      'isNRB',
      'userInfo'
    ])
  },
  methods: {
  },
  created () {
    if (this.debug) { console.log('NAVIGATED TO: Original Purchaser Registration Form') }
  }
}
</script>

<style>
</style>
