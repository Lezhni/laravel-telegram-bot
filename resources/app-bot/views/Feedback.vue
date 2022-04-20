<template>
  <b-container>
    <b-row class="mt-4">
      <b-col>
        <h2>Отзывы</h2>
      </b-col>
    </b-row>

    <b-row class="mb-2 mt-4">
      <b-col
          cols="12"
          v-for="feedback in feedbackItems"
          :key="feedback.id"
          class="mb-3"
      >
        <b-card
            footer-tag="footer"
            :title="feedback.author"
        >
          <b-card-text>
            {{feedback.text}}
          </b-card-text>
          <template #footer>
            <em>{{feedback['created_at'] | filterDate}}</em>
          </template>

          <b-button
              class="delete-feedback-btn"
              variant="danger"
              size="sm"
              @click="deleteFeedbackItemById(feedback.id)"
              v-b-tooltip.hover
              title="Изменить пользователя"
          >
            <b-icon icon="trash"></b-icon>
          </b-button>
        </b-card>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
export default {
  computed: {
    feedbackItems() {
      return this.$store.getters['getFeedbackItems']
    }
  },
  methods: {
    async getFeedbackData() {
      await this.$store.dispatch('setIsLoading', true)
      await this.$store.dispatch('setFeedbackData')
          .then(res => {
            this.$store.dispatch('setFeedbackItems', res.data.data['testimonials'])
            this.$store.dispatch('setIsLoading', false)
          })
    },
    async deleteFeedbackItemById(id) {
      this.$bvModal.msgBoxConfirm(`Вы точно хотите удалить отзыв?`, {
        title: 'Удаление отзыва',
        size: 'sm',
        buttonSize: 'sm',
        okVariant: 'danger',
        okTitle: 'Да',
        cancelTitle: 'Нет',
        footerClass: 'footer-buttons p-2',
        hideHeaderClose: false,
        centered: true
      })
          .then( async value => {
            if (value) {
              await this.$store.dispatch('setDeleteFeedbackById', id)
                  .then(res => {
                    if (res.status === 202) {
                      this.getFeedbackData()

                      this.$store.dispatch('setIsLoading', false)

                      this.$notify({
                        group: 'server-alerts',
                        title: 'Успешный запрос!',
                        type: 'success',
                        text: 'Отзыв успешно удален'
                      });
                    }
                  }).catch(e => {
                    this.$notify({
                      group: 'server-alerts',
                      title: 'Ошибка!',
                      type: 'danger',
                      text: 'Отзыв не удален'
                    });
                  })
            }
          })
          .catch(err => {
            this.$notify({
              group: 'server-alerts',
              title: 'Ошибка!',
              type: 'danger',
              text: 'Отзыв не удален'
            });
          })

    }
  },
  filters: {
    filterDate(date) {
      return new Date(date).toLocaleString("ru", {
        year: 'numeric',
        month: 'numeric',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        timezone: 'UTC'
      })
    }
  },
  created() {
    this.getFeedbackData()
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

.delete-feedback-btn {
  position: absolute;
  bottom: 8px;
  right: 10px;
}
</style>
