<template>
  <div>
    <div class="columns">
      <div class="column is-full-mobile is-half-desktop">
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
            {{ props.row.dealership }}
          </b-table-column>
          <b-table-column field="date_purchased" label="Purchased" sortable>
            {{
              props.row.date_purchased.substring(5,7) + '/' +
              props.row.date_purchased.substring(8,10) + '/' +
              props.row.date_purchased.substring(0,4)
            }}
          </b-table-column>
          <b-table-column field="hull_serial_number" label="Serial Number" sortable>
            {{ props.row.hull_serial_number }}
          </b-table-column>
          <b-table-column field="first_name" label="First Name" sortable>
            {{ props.row.first_name }}
          </b-table-column>
          <b-table-column field="last_name" label="Last Name" sortable>
            {{ props.row.last_name }}
          </b-table-column>
          <b-table-column field="street_city" label="City" sortable>
            {{ props.row.street_city }}
          </b-table-column>
          <b-table-column field="street_state" label="State" sortable>
            {{ props.row.street_state }}
          </b-table-column>
          <b-table-column custom-key="actions">
            <button class="button is-small is-dark" @click="edit(props.row.id)">
              <b-icon icon="file-pdf-box" ></b-icon>
            </button>
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
    edit: function (data) {
      console.log(data)
    },
    textMatch: function (item) {
      var searchTerm = this.searchTerm.toLowerCase()
      var itemText = (
        item.hull_serial_number + '~' +
        item.date_purchased.substring(5, 7) + '/' +
        item.date_purchased.substring(8, 10) + '/' +
        item.date_purchased.substring(0, 4) + '~' +
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
