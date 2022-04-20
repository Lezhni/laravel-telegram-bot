<template>
  <div>
    <b-form @submit.prevent="submitUser($event)" @reset="" id="formUser">
      <b-form-row>
        <b-col
            cols="12"
        >
          <b-form-group
              id="`input-group-users-1`"
              label="Имя пользователя"
              label-for="input-users-name"
              label-class="forms-label"
          >
            <b-form-input
                id="input-users-name"
                name="name"
                v-model="user.name"
                type="text"
                placeholder="Имя пользователя"
                :required="true"
            ></b-form-input>
          </b-form-group>
        </b-col>

        <b-col
            cols="12"
        >
          <b-form-group
              id="`input-group-users-2`"
              label="Email"
              label-for="input-users-email"
              label-class="forms-label"
          >
            <b-form-input
                id="input-users-email"
                name="email"
                v-model="user.email"
                type="email"
                placeholder="Email"
                :required="true"
            ></b-form-input>
          </b-form-group>
        </b-col>

        <b-col
            cols="12"
        >
          <b-form-group
              id="`input-group-users-3`"
              label="Пароль"
              label-for="input-users-password"
              label-class="forms-label"
          >
            <b-form-input
                id="input-users-password"
                name="password"
                type="password"
                placeholder="Пароль"
                autocomplete="foo"
            ></b-form-input>
          </b-form-group>
        </b-col>

        <b-col
            cols="12"
        >
          <b-form-group
              id="`input-group-users-4`"
              label="Подтвердите пароль"
              label-for="input-users-password_confirmation"
              label-class="forms-label"
          >
            <b-form-input
                id="input-users-password_confirmation"
                name="password_confirmation"
                type="password"
                placeholder="Подтвердите пароль"
                autocomplete="foo"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-form-row>

      <b-form-row>
        <b-col>
          <b-form-invalid-feedback
              :force-show="passwordError.length > 0"
              v-for="(mess, idxMess) in passwordError"
              :key="idxMess"
          >
            {{ mess }}
          </b-form-invalid-feedback>
        </b-col>
      </b-form-row>

      <input type="hidden" name="_method" value="PUT">

      <b-button
          type="submit"
          variant="info"
          size="sm"
          class="float-right"
      >
        Сохранить
      </b-button>
    </b-form>

  </div>
</template>

<script>
export default {
  data() {
    return {
      passwordError: []
    }
  },
  props: {
    getUsersTableData: {
      type: Function,
      required: true
    }
  },
  methods: {
    async submitUser(e) {
      const id = this.$store.getters['getUsersModalUser'].id

      await this.$store.dispatch('setIsLoading', true)
      await this.$store.dispatch('setUsersUpdateUser', {id: id, form: e.target})
          .then(res => {
            if (res.status === 204) {
              this.$root.$emit('bv::hide::modal', 'changeUsersTableItem', '#btnShow')
              this.getUsersTableData()

              this.$notify({
                group: 'server-alerts',
                title: 'Успешный запрос!',
                type: 'success',
                text: 'Пользователь успешно сохранен'
              });
            }
          })
          .catch(err => {
            if (typeof err.response.data.message === 'string') {
              this.passwordError.length = 0
              this.passwordError.push(err.response.data.message)
            } else if (typeof err.response.data.message === 'object') {
              this.passwordError = err.response.data.message.password ? err.response.data.message.password : []
            }

            this.$store.dispatch('setIsLoading', false)
          })
    }
  },
  computed: {
    user() {
      return this.$store.getters['getUsersModalUser']
    }
  }
}
</script>

<style scoped>

</style>