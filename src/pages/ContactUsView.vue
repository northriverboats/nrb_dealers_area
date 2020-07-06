<template>
  <div>
    <div class="columns">
      <div class="column">
        <h1 class="title">Contact Us</h1>
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
          <b-table-column field="submitted" label="Submitted" sortable>
            {{
              props.row.submitted.substring(5,7) + '/' +
              props.row.submitted.substring(8,10) + '/' +
              props.row.submitted.substring(0,4)
            }}
          </b-table-column>
          <b-table-column field="name" label="Name" sortable>
            {{ titleCase(props.row.name) }}
          </b-table-column>
          <b-table-column field="email" label="Email" sortable>
            {{ props.row.email }}
          </b-table-column>
          <b-table-column field="phone" label="Phone" sortable>
            {{ props.row.phone }}
          </b-table-column>
          <b-table-column field="subject" label="Subject" sortable>
            {{ titleCase(props.row.subject) }}
          </b-table-column>
          <b-table-column custom-key="pdf">
            <a href="#" @click="toPDF(props.row.id)">
              <b-icon icon="file-pdf-box" ></b-icon>
            </a>
          </b-table-column>
          <b-table-column custom-key="edit">
            <router-link :to="{path: `ContactUsForm/${props.row.id}`}" >
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
  name: 'ContactUsView',
  data () {
    return {
      searchTerm: ''
    }
  },
  computed: {
    ...mapGetters([
      'debug',
      'contactList',
      'fileName',
      'isNRB',
      'userInfo'
    ]),
    filter: function () {
      return this.contactList.filter(this.textMatch)
    }
  },
  methods: {
    toPDF: function (id) {
      this.$store.dispatch('readContactUsPDF', id)
        .then(() => {
          window.open(this.fileName, '_blank')
        })
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
        item.submitted.substring(5, 7) + '/' +
        item.submitted.substring(8, 10) + '/' +
        item.submitted.substring(0, 4) + '~' +
        item.name + ' ' +
        item.email + '~' +
        item.phone + '~' +
        item.subject
      ).toLowerCase()
      return itemText.indexOf(searchTerm) !== -1
    }
  },
  created () {
    if (this.debug) { console.log('NAVIGATED TO: Contact Us History') }

    this.$store.dispatch('contactListRead')
  }
}
</script>

<style>
</style>
