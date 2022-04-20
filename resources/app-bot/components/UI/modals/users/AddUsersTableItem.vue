<template>
  <div>
    <b-modal id="AddUsersTableItem" title="Создание пользователя" size="xs" hide-footer>
      <add-users-form :getUsersTableData="getUsersTableData" />
    </b-modal>
  </div>
</template>

<script>
import AddUsersForm from "../../forms/users/AddUsersForm";

export default {
  props: {
    getUsersTableData: {
      type: Function,
      required: true
    }
  },
  components: {AddUsersForm},
  computed: {
    categorySelected() {
      return this.$store.getters['getSelectedCategory']
    },
  },
  methods: {
    showModal() {
      this.$refs['AddUsersTableItem'].show()
    },
    hideModal() {
      this.$refs['AddUsersTableItem'].hide()
    },
    toggleModal() {
      this.$refs['AddUsersTableItem'].toggle('#toggle-btn')
    }
  }
}
</script>

<style scoped>

</style>