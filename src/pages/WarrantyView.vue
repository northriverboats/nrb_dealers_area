<template>
  <div>
    <div class="columns">
      <div class="column">
        <h1 class="title">Warranties</h1>
      </div>
      <div class="column">
        <section>
            <b-field>
                <b-radio-button v-model="status"
                  native-value="Open"
                  type="is-success">
                  <b-icon icon="check"></b-icon>
                  <span>Opened</span>
                </b-radio-button>

                <b-radio-button v-model="status"
                  native-value="Closed"
                  type="is-danger">
                  <b-icon icon="close"></b-icon>
                  <span>Closed</span>
                </b-radio-button>

                <b-radio-button v-model="status"
                  native-value="all">
                  All
                </b-radio-button>
            </b-field>
        </section>
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
          <b-table-column field="date_created" label="Created" sortable>
            {{ props.row.date_created }}
          </b-table-column>
          <b-table-column field="hull_serial_number" label="Serial Number" sortable>
            {{ props.row.hull_serial_number }}
          </b-table-column>
          <b-table-column field="customer" label="Customer" sortable>
            {{ titleCase(props.row.customer) }}
          </b-table-column>
          <b-table-column field="issue" label="Issue" sortable>
            {{ props.row.issue }}
          </b-table-column>
          <b-table-column field="status" label="Status" sortable>
            {{ props.row.status }}
          </b-table-column>
          <b-table-column custom-key="pdf">
            <a href="#" @click="toPDF(props.row.uid)">
              <b-icon icon="file-pdf-box" ></b-icon>
            </a>
          </b-table-column>
          <b-table-column custom-key="edit">
            <router-link :to="{path: `Warrantyform/${props.row.id}`}" >
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
  name: 'Warrantyview',
  data () {
    return {
      status: 'all',
      searchTerm: ''
    }
  },
  computed: {
    ...mapGetters([
      'debug',
      'warrantyList',
      'fileName',
      'isNRB',
      'userInfo'
    ]),
    filter: function () {
      return this.warrantyList.filter(this.textMatch)
    }
  },
  methods: {
    toPDF: function (id) {
      /*
      this.$store.dispatch('readOPRPDF', id)
        .then(() => {
          window.open(this.fileName, '_blank')
        })
      */
    },
    toEdit: function (id) {
      this.$router.push({ path: `/warrantyform/${id}/` })
      // this.$router.push({ name: 'Warrantyform', params: { id: id } })
    },
    titleCase: function (str) {
      str = str || ''
      return str.toLowerCase().split(' ').map(function (word) {
        return (word.charAt(0).toUpperCase() + word.slice(1))
      }).join(' ')
    },
    textMatch: function (item) {
      if (this.status === 'Open' && item.status === 'Closed') return false
      if (this.status === 'Closed' && item.status === 'Opened') return false
      var searchTerm = this.searchTerm.toLowerCase()
      var dealership = (this.isNRB ? item.dealership : '')
      var itemText = (
        dealership + '~' +
        item.hull_serial_number + '~' +
        item.date_created + '~' +
        item.customer
      ).toLowerCase()
      return itemText.indexOf(searchTerm) !== -1
    }
  },
  created () {
    if (this.debug) { console.log('NAVIGATED TO: Warranty List / History') }
    this.$store.dispatch('warrantyListRead')
  }
}
</script>

<style>
</style>
