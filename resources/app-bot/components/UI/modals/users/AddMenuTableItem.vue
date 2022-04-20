<template>
  <div>
    <b-modal id="AddMenuTableItem" title="Создание пункта меню" size="lg" hide-footer>
      <add-menu-form :getMenuTableData="getMenuTableData" />
    </b-modal>
  </div>
</template>

<script>
import AddMenuForm from "../../forms/menu/AddMenuForm";

export default {
  props: {
    getMenuTableData: {
      type: Function,
      required: true
    }
  },
  components: {AddMenuForm},
  computed: {
    categorySelected() {
      return this.$store.getters['getSelectedCategory']
    },
  },
  methods: {
    showModal() {
      this.$refs['AddMenuTableItem'].show()
    },
    hideModal() {
      this.$refs['AddMenuTableItem'].hide()
    },
    toggleModal() {
      this.$refs['AddMenuTableItem'].toggle('#toggle-btn')
    }
  }
}
</script>

<style lang="scss">
.ql-tooltip {
  &.ql-editing {
    left: 5px !important;
  }
}
</style>