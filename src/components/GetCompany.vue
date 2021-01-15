<template>
  <div class="modal-card" style="width: auto">
    <header class="modal-card-head">
      <p class="modal-card-title">Company or Agenecy Information</p>
      <button type="button" class="delete" @click="$emit('close')" v-if="false"/>
    </header>
    <section class="modal-card-body">
      <b>If this regisration is for a Company or Government Agency:</b>
      <p>Please enter the Organization name below and use the First Name / Last Name fields for your contact at the Organization.</p>
      <br />
      <b-field label="Company / Agency">
        <b-input type="company" v-model="company" placeholder="Company or Agency Name" @input="bob"></b-input>
      </b-field>
      <b-field label="">
       <b-checkbox v-model="checkbox" :disabled="!checkable">This sale is to an individual</b-checkbox> 
      </b-field>
    </section>
    <footer class="modal-card-foot" style="justify-content: flex-end;">
        <button class="button is-primary" @click="submit" :disabled="(!checkbox && company === '') || (checkbox  && company !=='')">Ok</button>
    </footer>
  </div>
</template>

<script>
export default {
  name: "HelloWorld",
  data() {
    return {
      company: "",
      checkbox: false,
      checkable: true,
    }
  },
  watch: {
    '$route' () {
      this.$emit('close')
    }
  },
  methods: {
    bob: function (event) {
      if (event) {
        this.checkbox = false
        this.checkable = false 
      } else {
        this.checkable = true
      }
    },
    submit: function () {
      this.$emit('pushCompany', this.company)
      this.$emit('close')
    },
  },
}
</script>
