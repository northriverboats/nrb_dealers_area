<template>
  <div>
    <div class="columns">
      <div class="column">
        <b-field label="Search">
           <b-input v-model="searchTerm" />
        </b-field>
      </div>
    </div>
    <div class="columns">
      <div class="column">
        <b-table
          :data="filter"
          :columns="columns"
          :paginated="true"
          :per-page="16"
          :striped="true"
          :narrowed="true"
          :hoverable="true"
          :pagination-simple="false"
          aria-next-label="Next page"
          aria-previous-label="Previous page"
          aria-page-label="Page"
          aria-current-label="Current page"
        >
        </b-table>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'OPRview',
  data () {
    return {
      searchTerm: '',
      columns: [
        {
          label: 'Dealership',
          field: 'dealership',
          sortable: true
        },
        {
          label: 'Purchased',
          field: 'date_purchased',
          sortable: true
        },
        {
          label: 'Serial Number',
          field: 'hull_serial_number',
          sortable: true
        },
        {
          label: 'First Name',
          field: 'first_name',
          sortable: true
        },
        {
          label: 'Last Name',
          field: 'last_name',
          sortable: true
        },
        {
          label: 'City',
          field: 'street_city',
          sortable: true
        },
        {
          label: 'State',
          field: 'street_state',
          sortable: true
        }
      ]
    }
  },
  computed: {
    ...mapGetters([
      'debug',
      'oprList',
      'fileName',
      'isNRB',
      'userInfo'
    ]),
    filter: function () {
      var reg = new RegExp(this.searchTerm, 'i')
      var data = []
      for (var i in this.oprList) {
        if ((this.oprList[i].hull_serial_number || '').match(reg) ||
            (this.oprList[i].first_name || '').match(reg) ||
            (this.oprList[i].last_name || '').match(reg) ||
            (this.oprList[i].street_city || '').match(reg) ||
            (this.oprList[i].street_state || '').match(reg)) {
          data.push(this.oprList[i])
        }
      }
      return data
    }
  },
  methods: {
  },
  created () {
    if (this.debug) { console.log('NAVIGATED TO: Original Purchaser Registration History') }

    this.$store.dispatch('oprRead')
  }
}
</script>

<style>
</style>
