<template>
  <div>
    <div class="columns is-multiline">
      <div class="column is-full formgroup">
        <h3 class="title">
          Dealership Warranty Form
          <span class ="alignright" v-if="readOnly">
            <b-tooltip label="Delete OPR" position="is-bottom">
              <b-button @click="toDelete(id)" type="is-info" v-if="isNRB"><b-icon icon="delete" ></b-icon></b-button>
            </b-tooltip>
            &nbsp;
            <b-tooltip label="Download PDF" position="is-bottom">
              <b-button @click="toPDF(id)" type="is-info"><b-icon icon="file-pdf-box" ></b-icon></b-button>
            </b-tooltip>
          </span>
        </h3>
        <hr class="has-background-white">
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'Warrantyform',
  data () {
    return {
      id: '',
      dealership: '',
      readOnly: false, 
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
