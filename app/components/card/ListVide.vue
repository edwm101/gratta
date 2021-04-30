<template>
  <div>
    <v-card
      class="elevation-0 pa-3 text-center mx-auto overflow-hidden"
      style="background: transparent"
      max-width="400"
      v-if="show"
    >
      <div class="pa-2">
        <v-icon class="rounded-circle pa-1 mb-1" x-large>{{ icon }}</v-icon>

        <div class="title grey--text font-weight-medium mb-2">
          {{title}}
        </div>
        <div class="body-2" v-html="description"></div>
      </div>
      <slot></slot>
    </v-card>

    <v-progress-linear
      v-else
      color="deep-purple accent-4"
      indeterminate
      rounded
      height="6"
    ></v-progress-linear>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      default: 'Votre liste est vide !',
    },
    description: {
      default: '',
    },
    icon: {
      default: 'mdi-information-outline',
    },
  },
  data() {
    return { show: false }
  },
  watch: {},
  mounted() {
    this.show = true
  },
}
</script>