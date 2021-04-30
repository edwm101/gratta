<template>
  <div>
    <v-footer class="header" dark>
      <v-container>
        <v-card-actions class="pa-1 mt-3 caption grey--text">
          <nuxt-link to="/" class="mr-2 text-decoration-none white--text"
            >Politique de confidentialité</nuxt-link
          >
          <nuxt-link to="/" class="text-decoration-none white--text"
            >Conditions d'utilisation</nuxt-link
          >
        </v-card-actions>
        <span class="pa-1 caption grey--text">
          2021 © Tous droits réservés -
          <b class="white--text">MonShop</b>
        </span>
      </v-container>
    </v-footer>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {},
      loading: false,
      is_send: false,
    }
  },
  methods: {
    async sendMessage() {
      this.loading = true
      await this.$api
        .post('public/contact', {
          ...this.form,
        })
        .then((res) => {
          this.is_send = true
        })
      this.loading = false
    },
  },
}
</script>