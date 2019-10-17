<template>
  <div>
    <div class="columns">
      <div class="column">
        <h1 class="title">Contact Us Form {{ id }}</h1>
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
      id: 0
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
    if (this.debug) { console.log('NAVIGATED TO: Contact Us Form') }
    this.id = this.$route.params.id.toString()
  }
}
</script>

<style>
</style>
