<template>
  <v-app>
    <v-app-bar
      dark
      class="shadow header"
      :clipped-left="$vuetify.breakpoint.lgAndUp"
      app
    >
      <v-toolbar-title style="width: 140px" class="px-0 mx-1">
        <nuxt-link :to="{ name: 'user' }">
          <img
            :src="require('~/assets/image/logo.png')"
            alt="" height="45"
            class="mr-1 mt-1"
        /></nuxt-link>
      </v-toolbar-title>

      <v-spacer></v-spacer>

      <card-account-menu></card-account-menu>
    </v-app-bar>
    <v-main class="dash-content">
      <v-slide-y-transition mode="out-in">
        <nuxt />
      </v-slide-y-transition>
      <page-footer></page-footer>
    </v-main>
  </v-app>
</template>

<script>
export default {
  data() {
    return { form: {} }
  },
  watch: {
    '$store.state.user.info.phone'() {
      // if (this.$store.state.user.info.phone == '123456') {
      //   this.$store.commit('user/logout')
      // }
    },
  },
  methods: {},
  mounted() {
    this.$store.dispatch('user/validation')
    // if (this.$store.state.user.info.phone == '123456') {
    //   this.$store.commit('user/logout')
    // }
  },
}
</script>