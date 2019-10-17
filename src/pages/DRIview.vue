<template>
  <div>
    <div class="columns">
      <div class="column">
        <h1 class="title">Dealer Receipt Inspection</h1>
      </div>
      <div class="column">
        <b-field>
           <b-input
             v-model="searchTerm"
             type="search"
             icon="magnify"
             placeholder="Search..."
           />
        </b-field>
      </div>
    </div>
    <div class="columns">
      <div class="column">
        <hr class="has-background-white" style="margin-top: 0rem">
        <b-table
          :data="filter"
          :paginated="true"
          :per-page="10"
          :striped="true"
          :narrowed="true"
          :hoverable="true"
          :pagination-simple="false"
          :mobile-cards="true"
          aria-next-label="Next page"
          aria-previous-label="Previous page"
          aria-page-label="Page"
          aria-current-label="Current page"
        >
        <template slot-scope="props">
          <b-table-column field="dealership" label="Dealership" :visible="isNRB" sortable>
            {{ titleCase(props.row.dealership) }}
          </b-table-column>
          <b-table-column field="date_received" label="Received" sortable>
            {{
              props.row.date_received.substring(5,7) + '/' +
              props.row.date_received.substring(8,10) + '/' +
              props.row.date_received.substring(0,4)
            }}
          </b-table-column>
          <b-table-column field="hull_serial_number" label="Serial Number" sortable>
            {{ props.row.hull_serial_number }}
          </b-table-column>
          <b-table-column field="model" label="Model" sortable>
            {{ props.row.model }}
          </b-table-column>
          <b-table-column field="checked_by" label="Checked By" sortable>
            {{ titleCase(props.row.checked_by) }}
          </b-table-column>
          <b-table-column field="email_address" label="Email" sortable>
            {{ titleCase(props.row.email_address) }}
          </b-table-column>
          <b-table-column custom-key="pdf">
            <a href="#" @click="toPDF(props.row.id)">
              <b-icon icon="file-pdf-box" ></b-icon>
            </a>
          </b-table-column>
          <b-table-column custom-key="edit">
            <a href="#" @click="toEdit(props.row.hull_serial_number)">
              <b-icon icon="pencil" ></b-icon>
            </a>
          </b-table-column>
        </template>
        </b-table>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'DRIview',
  data () {
    return {
      searchTerm: ''
    }
  },
  computed: {
    ...mapGetters([
      'debug',
      'driList',
      'fileName',
      'isNRB',
      'userInfo'
    ]),
    filter: function () {
      return this.driList.filter(this.textMatch)
    }
  },
  methods: {
    toPDF: function (id) {
      this.$store.dispatch('readDRIPDF', id)
        .then(() => {
          window.open(this.fileName, '_blank')
        })
    },
    toEdit: function (id) {
      alert('Not yet ' + id + ' is not ready.')
    },
    titleCase: function (str) {
      str = str || ''
      return str.toLowerCase().split(' ').map(function (word) {
        return (word.charAt(0).toUpperCase() + word.slice(1))
      }).join(' ')
    },
    textMatch: function (item) {
      var searchTerm = this.searchTerm.toLowerCase()
      var dealership = (this.isNRB ? item.dealership : '')
      var itemText = (
        dealership + '~' +
        item.hull_serial_number + '~' +
        item.date_received.substring(5, 7) + '/' +
        item.date_received.substring(8, 10) + '/' +
        item.date_received.substring(0, 4) + '~' +
        item.model + ' ' +
        item.checked_by + '~' +
        item.email_address
      ).toLowerCase()
      return itemText.indexOf(searchTerm) !== -1
    }
  },
  created () {
    if (this.debug) { console.log('NAVIGATED TO: Dealer Receipt Inspetcion History') }

    this.$store.dispatch('driRead')
  }
}
</script>

<style>
</style>
