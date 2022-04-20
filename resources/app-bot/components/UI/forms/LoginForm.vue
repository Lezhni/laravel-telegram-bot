<template>
  <div class="w-100 p-4">
    <b-form @submit="onSubmit" @reset="onReset">
      <b-form-group
          id="input-group-1"
          label="Email"
          label-for="input-1"
      >
        <b-form-input
            id="input-1"
            :value="userEmail"
            @input="updateUserEmail"
            type="email"
            placeholder="Enter email"
            required
        ></b-form-input>
      </b-form-group>

      <b-form-group id="input-group-2" label="Пароль" label-for="input-2">
        <b-form-input
            id="input-2"
            :value="userPassword"
            @input="updateUserPassword"
            type="password"
            placeholder="Пароль"
            required
            autocomplete="on"
        ></b-form-input>
        <b-form-invalid-feedback
            :force-show="passwordError.length > 0"
            v-for="(mess, idxMess) in passwordError"
            :key="idxMess"
        >
         {{mess}}
        </b-form-invalid-feedback>
      </b-form-group>

      <b-button type="submit" variant="info" class="w-100">Войти</b-button>
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
  computed: {
    userEmail() {
      return this.$store.getters['getUserEmail']
    },
    userPassword() {
      return this.$store.getters['getUserPassword']
    }
  },
  methods: {
    updateUserEmail(value) {
      this.$store.dispatch('setUserEmail', value)
    },
    updateUserPassword(value) {
      this.$store.dispatch('setUserPassword', value)
    },
    async onSubmit(event) {
      event.preventDefault()

      await this.$store.dispatch('setIsLoading', true)
      await this.$store.dispatch('loginUser', {
        email: this.userEmail,
        password: this.userPassword
      })
          .then(res => {
            auth.login(res.data.access_token, res.data.data.user);
            this.$router.push('/users')
            this.$store.dispatch('setIsLoading', false)
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

    },
    onReset(event) {
      event.preventDefault()
      this.$store.dispatch('setResetLoginForm')
    }
  }
}
</script>