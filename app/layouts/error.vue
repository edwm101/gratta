<template>
  <v-container>
    <v-card class="mx-auto text-center pa-6 elevation-0 my-4" max-width="500">
      <img
        width="200"
        :src="require('@/assets/image/404.jpg')"
        :alt="pageNotFound"
      />
      <h1 v-if="error.statusCode === 404">{{ pageNotFound }}</h1>
      <h1 v-else>{{ otherError }}</h1>
      <v-btn class="primary shadow my-2" to="/">
        <v-icon class="mx-2">mdi-home</v-icon> Accueil</v-btn
      >
    </v-card>
  </v-container>
</template>

<script>
export default {
  props: {
    error: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      pageNotFound: '404 introuvable',
      otherError: 'Une erreur est survenue',
    }
  },
  head() {
    const title =
      this.error.statusCode === 404 ? this.pageNotFound : this.otherError
    return {
      title,
    }
  },
}
</script>

<style scoped>
h1 {
  font-size: 20px;
}
</style>
