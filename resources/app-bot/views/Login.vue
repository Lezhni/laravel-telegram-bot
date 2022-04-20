<template>
  <b-container>
    <b-row class="pt-5 justify-content-center">
      <b-col cols="12" sm="8" md="6" lg="5" xl="4" class="rounded creacept-form-wrap">
        <LoginForm/>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import LoginForm from "../components/UI/forms/LoginForm";

export default {
  components: {LoginForm},
  mounted() {
    this.$store.dispatch('setAuth', auth.check())
    eventBus.$on('userLoggedIn', (user) => {
      this.$store.dispatch('setAuth', true)
    });
  },
}
</script>

<style lang="scss" scoped>

</style>