<template>
  <div class="modal-card" style="width: auto">
    <header class="modal-card-head">
      <p class="modal-card-title">Confirm Deletion</p>
      <button type="button" class="delete" @click="$emit('close')" v-if="false"/>
    </header>
    <section class="modal-card-body">
      <p>{{ hull }}</p>
      <br />
      <b-field label="">
       <b-checkbox v-model="checkbox">Yes I want to delete this record.</b-checkbox> 
      </b-field>
    </section>
    <footer class="modal-card-foot" style="justify-content: flex-end;">
        <button class="button" @click="$emit('close')">No</button>
        <button class="button is-primary" @click="submit" :disabled="!checkbox">Yes</button>
    </footer>
  </div>
</template>

<script>
export default {
  name: "ConfirmDelete",
  data() {
    return {
      checkbox: false,
    }
  },
  props: [
    'hull',
    'id',
  ],
  watch: {
    '$route' () {
      this.$emit('close', -1)
    }
  },
  methods: {
    submit: function () {
      this.$emit('doDelete', this.id)
      this.$emit('close')
    },
  },
}
</script>
