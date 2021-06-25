<template>
  <v-app class="is_rtl">
  
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