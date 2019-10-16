<template>
  <div class="columns is-multiline">
    <!-- RESOURCES -->
    <div class="column is-full-mobile is-full-desktop" style="margin-bottom: 2rem">
      <h1 class="title">Resources</h1><hr class="has-background-white">
      <div class="columns">
        <div class="column"><a href="/drc" class="button is-primary is-inverted is-outlined">Dealer Resource Center</a></div>
      </div>
    </div>
    <!-- DRI -->
    <div class="column is-full-tablet is-half-desktop" style="margin-bottom: 2rem">
        <h1 class="title">Dealer Inspection</h1>
        <hr class="has-background-white">
      <div class="columns">
        <div class="column"><a href="/drc" class="button is-primary is-inverted is-outlined">Create New DRI</a></div>
        <div class="column"><a href="/drc" class="button is-primary is-inverted is-outlined">View All DRIs</a></div>
      </div>
    </div>
    <!-- OPR -->
    <div class="column is-full-tablet is-half-desktop" style="margin-bottom: 2rem">
        <h1 class="title">Original Purchaser Registration</h1>
        <hr class="has-background-white">
      <div class="columns">
        <div class="column"><a href="/drc" class="button is-primary is-inverted is-outlined">Create New OPR</a></div>
        <div class="column"><a href="/drc" class="button is-primary is-inverted is-outlined">View All OPRs</a></div>
      </div>
    </div>
    <!-- MAINTEANCE  -->
    <div class="column is-full-tablet is-half-desktop" style="margin-bottom: 2rem">
        <h1 class="title">Contact Us</h1>
        <hr class="has-background-white">
      <div class="columns">
        <div class="column"><a href="/drc" class="button is-primary is-inverted is-outlined">View All Contacts</a></div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'Main',
  data () {
    return {
    }
  },
  computed: {
    ...mapGetters([
      'userInfo',
      'isNRB',
      'debug'
    ])
  },
  created () {
    if (this.debug) {
      console.log('NAVIGATED TO: Main')
    }
  }
}
</script>

<style>
</style>
