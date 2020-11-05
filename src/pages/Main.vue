<template>
  <div class="columns is-multiline">
    <!-- RESOURCES -->
    <div class="column is-full-mobile is-full-desktop" style="margin-bottom: 2rem">
      <h1 class="title">Resources</h1>
      <hr class="has-background-white">
      <div class="columns">
        <div class="column"><a href="/drc" class="button is-primary is-inverted is-outlined">Dealer Resource Center</a></div>
      </div>
    </div>
    <!-- DRI -->
    <div class="column is-full-tablet is-half-desktop" style="margin-top: auto;">
        <h1 class="title">Dealer Inspection</h1>
        <hr class="has-background-white">
      <div class="columns">
        <div class="column">
          <router-link :to="{name: 'DRIform', params: {id: 0}}" class="button is-primary is-inverted is-outlined">Create New DRI</router-link>
        </div>
        <div class="column">
          <router-link :to="{name: 'DRIview'}" class="button is-primary is-inverted is-outlined">View All DRIs</router-link>
        </div>
      </div>
    </div>
    <!-- OPR -->
    <div class="column is-full-tablet is-half-desktop" style="margin-top: auto;">
        <h1 class="title">Original Purchaser Registration</h1>
        <hr class="has-background-white">
      <div class="columns">
        <div class="column">
          <router-link :to="{name: 'OPRform', params: {id: 0}}" class="button is-primary is-inverted is-outlined">Create New OPR</router-link>
        </div>
        <div class="column">
          <router-link :to="{name: 'OPRview'}" class="button is-primary is-inverted is-outlined">View All OPRs</router-link>
        </div>
      </div>
    </div>
    <!-- MAINTEANCE  -->
    <div class="column is-full-tablet is-half-desktop" style="margin-top: auto;">
        <h1 class="title">Warranty</h1>
        <hr class="has-background-white">
      <div class="columns">
        <div class="column">
          <router-link :to="{name: 'Warrantyview'}" class="button is-primary is-inverted is-outlined">View All Warranties</router-link>
        </div>
      </div>
    </div>
    <div class="column is-full-tablet is-half-desktop" style="margin-top: auto;">
        <h1 class="title">Contact Us</h1>
        <hr class="has-background-white">
      <div class="columns">
        <div class="column">
          <router-link :to="{name: 'ContactUsView'}" class="button is-primary is-inverted is-outlined">View All Contact Us</router-link>
        </div>
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
