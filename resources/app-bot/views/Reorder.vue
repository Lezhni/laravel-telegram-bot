<template>
  <b-container>
    <b-row class="mt-4">
      <b-col>
        <h2>Вложенность меню</h2>
        <p>Перетаскивайте с помощью мыши пункты меню, чтобы выстроить нужную иерархию и навигацию по чат-боту</p>
      </b-col>
    </b-row>

    <b-row class="mb-2 mt-4">
      <b-col>
        <b-jumbotron>
          <VueNestable v-model="reorderItems" class-prop="class">
            <VueNestableHandle
                slot-scope="{ item }"
                :item="item"
            >
              <b-icon icon="card-text" variant="info"></b-icon>
              {{ item.text }}
            </VueNestableHandle>
          </VueNestable>
        </b-jumbotron>
      </b-col>
    </b-row>

    <b-row>
      <b-col
          cols="12" sm="6"
          class="d-flex justify-content-start"
      >
        <b-button
            size="sm"
            variant="success"
            v-b-tooltip.hover
            @click="saveReorderData"
            title="Сохранить вложенность"
        >
          Сохранить
        </b-button>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
export default {
  data() {
    return {
      nestableItems: []
    }
  },
  computed: {
    reorderItems: {
      set(val) {
        this.$store.dispatch('setReorderUpdateItems', val)
      },
      get() {
        return this.$store.getters['getReorderItems']
      }
    },
  },
  methods: {
    async getReorderData() {
      await this.$store.dispatch('setIsLoading', true)
      await this.$store.dispatch('setReorderData')
          .then(res => {
            this.$store.dispatch('setReorderItems', res.data.data.items)
            this.$store.dispatch('setIsLoading', false)
          })
    },
    async saveReorderData() {
      await this.$store.dispatch('setIsLoading', true)
      await this.$store.dispatch('setReorderSaveData', this.reorderItems)
          .then(res => {
            this.$store.dispatch('setIsLoading', false)

            this.$notify({
              group: 'server-alerts',
              title: 'Успешный запрос!',
              type: 'success',
              text: 'Вложенность успешно сохранена'
            });
          }).catch(err => {
            this.$notify({
              group: 'server-alerts',
              title: 'Ошибка!',
              type: 'success',
              text: 'Вложенность не сохранена, произошла ошибка'
            });

            this.$store.dispatch('setIsLoading', false)
          })
    },
  },
  created() {
    this.getReorderData()
  }
}
</script>

<style lang="scss">
.marker-color-info svg {
  color: #17a2b8 !important
}

.marker-color-danger svg {
  color: red !important
}

.marker-color-success svg {
  color: green !important
}
</style>
