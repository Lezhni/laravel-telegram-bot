<template>
  <div>
    <b-navbar variant="info" toggleable="lg" class="creacept-header" type="dark" fixed="top">
      <router-link class="navbar-brand" to="/">Creacept</router-link>

      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav>

          <b-nav-item-dropdown text="Меню" left>
            <router-link to="/menu" exact custom v-slot="{ navigate, isActive }">
              <b-dropdown-item :active="isActive" @click="navigate" @keypress.enter="navigate">Пункты меню</b-dropdown-item>
            </router-link>
            <router-link to="/menu/reorder" exact custom v-slot="{ navigate, isActive }">
              <b-dropdown-item :active="isActive" @click="navigate" @keypress.enter="navigate">Вложенность меню</b-dropdown-item>
            </router-link>
          </b-nav-item-dropdown>

          <router-link to="/feedback" custom v-slot="{ navigate, isActive }">
            <b-nav-item :active="isActive" @click="navigate" @keypress.enter="navigate">Отзывы</b-nav-item>
          </router-link>

          <router-link to="/users" custom v-slot="{ navigate, isActive }">
            <b-nav-item :active="isActive" @click="navigate" @keypress.enter="navigate">Пользователи</b-nav-item>
          </router-link>
        </b-navbar-nav>

        <b-navbar-nav class="ml-auto">

          <b-spinner v-if="!isAuth && $route.name !== 'login'" variant="light" type="grow" label="Spinning"></b-spinner>

          <b-nav-item-dropdown v-if="isAuth" right>

            <template #button-content>
              <em>{{ userStatus }}</em>
            </template>
            <b-dropdown-item @click="onLogout">Выйти</b-dropdown-item>
          </b-nav-item-dropdown>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
  </div>
</template>

<script>
export default {
  methods: {
    async onLogout() {
      await this.$store.dispatch('setIsLoading', true)
      await this.$store.dispatch('logoutUser')
          .then((res) => {
            auth.logout()
            this.$store.dispatch('clearUser')
            this.$store.dispatch('setIsLoading', false)
            this.$router.push('/login')
          })
    }
  },
  computed: {
    userStatus() {
      return this.$store.getters['getUserStatus']
    },
    isAuth() {
      return this.$store.getters['getAuth']
    },
    isLoading() {
      return this.$store.getters['getIsLoading']
    }
  },
  mounted() {
    console.log(this)
  }
}
</script>

<style scoped>

</style>