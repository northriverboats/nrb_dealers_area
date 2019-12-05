<template>
  <div>
    <div class="columns">
      <div class="column">
        <h1 class="title">Original Purchaser Registrations</h1>
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
          <b-table-column field="date_delivered" label="Purchased" sortable>
            {{
              props.row.date_delivered.substring(5,7) + '/' +
              props.row.date_delivered.substring(8,10) + '/' +
              props.row.date_delivered.substring(0,4)
            }}
          </b-table-column>
          <b-table-column field="hull_serial_number" label="Serial Number" sortable>
            {{ props.row.hull_serial_number }}
          </b-table-column>
          <b-table-column field="first_name" label="First Name" sortable>
            {{ titleCase(props.row.first_name) }}
          </b-table-column>
          <b-table-column field="last_name" label="Last Name" sortable>
            {{ titleCase(props.row.last_name) }}
          </b-table-column>
          <b-table-column field="street_city" label="City" sortable>
            {{ titleCase(props.row.street_city) }}
          </b-table-column>
          <b-table-column field="street_state" label="State" sortable>
            {{ titleCase(props.row.street_state) }}
          </b-table-column>
          <b-table-column custom-key="pdf">
            <a href="#" @click="toPDF(props.row.id)">
              <b-icon icon="file-pdf-box" ></b-icon>
            </a>
          </b-table-column>
          <b-table-column custom-key="edit">
            <router-link :to="{path: `OPRform/${props.row.id}`}" >
              <b-icon icon="pencil" ></b-icon>
            </router-link>
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
  name: 'OPRview',
  data () {
    return {
      searchTerm: ''
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
      return this.oprList.filter(this.textMatch)
    }
  },
  methods: {
    toPDF: function (id) {
      this.$store.dispatch('readOPRPDF', id)
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
        item.date_delivered.substring(5, 7) + '/' +
        item.date_delivered.substring(8, 10) + '/' +
        item.date_delivered.substring(0, 4) + '~' +
        item.first_name + ' ' +
        item.last_name + '~' +
        item.street_city + '~' +
        item.street_state
      ).toLowerCase()
      return itemText.indexOf(searchTerm) !== -1
    }
  },
  created () {
    if (this.debug) { console.log('NAVIGATED TO: Original Purchaser Registration History') }

    this.$store.dispatch('oprRead')
  }
}
</script>

<style>
</style>
