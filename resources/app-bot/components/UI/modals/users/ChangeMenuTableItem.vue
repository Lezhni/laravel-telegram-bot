<template>
  <div>
    <b-modal id="changeMenuTableItem" title="Редактирование" size="xs" hide-footer>
      <menu-form :getMenuTableData="getMenuTableData"/>
    </b-modal>
  </div>
</template>

<script>
import MenuForm from "../../forms/menu/MenuForm";

export default {
  props: {
    getMenuTableData: {
      type: Function,
      required: true
    }
  },
  components: {MenuForm},
  methods: {
    showModal() {
      this.$refs['changeMenuTableItem'].show()
    },
    hideModal() {
      this.$refs['changeMenuTableItem'].hide()
    },
    toggleModal() {
      this.$refs['changeMenuTableItem'].toggle('#toggle-btn')
    }
  }
}
</script>

<style scoped>

</style>