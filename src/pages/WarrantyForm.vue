<template>
  <div>
    Something Goes Here {{ id }}
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'Warrantyform',
  data () {
    return {
      id: ''
    }
  },
  computed: {
    ...mapGetters([
      'debug',
      'warrantyList',
      'fileName',
      'isNRB',
      'userInfo'
    ])
  },
  methods: {
    titleCase: function (str) {
      str = str || ''
      return str.toLowerCase().split(' ').map(function (word) {
        return (word.charAt(0).toUpperCase() + word.slice(1))
      }).join(' ')
    }
  },
  created () {
    if (this.debug) { console.log('NAVIGATED TO: Warranty List / History') }

    this.id = this.$route.params.id.toString()
  }
}
</script>

<style>
</style>
