<template>
  <div>
    <Header/>
    <b-overlay :show="isLoading" rounded="sm" spinner-variant="info" variant="dark" opacity="0.20" spinner-type="grow">
      <section class="wrapper pt-5">
        <router-view></router-view>

        <notifications group="server-alerts" position="bottom right"/>
      </section>
    </b-overlay>
  </div>
</template>

<script>
import Header from './components/Header.vue'
import Spinner from './components/UI/Spinner.vue'

export default {
  components: {Header, Spinner },
  computed: {
    isLoading() {
      return this.$store.getters['getIsLoading']
    }
  },
  methods: {

  },
  created() {

  }
}
</script>

<style lang="scss" scoped>
.wrapper {
  min-height: 100vh;
  //overflow-y: scroll;
}
</style>