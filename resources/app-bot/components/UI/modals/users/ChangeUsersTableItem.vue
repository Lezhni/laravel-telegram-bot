<template>
  <div>
    <b-modal id="changeUsersTableItem" title="Редактирование" size="xs" hide-footer>
      <users-form :getUsersTableData="getUsersTableData"/>
    </b-modal>
  </div>
</template>

<script>
import UsersForm from "../../forms/users/UsersForm";

export default {
  props: {
    getUsersTableData: {
      type: Function,
      required: true
    }
  },
  components: {UsersForm},
  computed: {
    categorySelected() {
      return this.$store.getters['getSelectedCategory']
    },
  },
  methods: {
    showModal() {
      this.$refs['changeSalesTableItem'].show()
    },
    hideModal() {
      this.$refs['changeSalesTableItem'].hide()
    },
    toggleModal() {
      this.$refs['changeSalesTableItem'].toggle('#toggle-btn')
    }
  }
}
</script>

<style scoped>

</style>