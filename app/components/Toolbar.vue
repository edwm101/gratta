<template>
  <v-card-actions class="head-toolbar headline font-weight-bold">
    <v-icon @click="$router.push({ name: back })" v-if="back" class="mr-2"
      >mdi-arrow-left</v-icon
    >

    {{name}} <v-spacer></v-spacer>
    <slot></slot>
  </v-card-actions>
</template>

<script>
export default {
  props: {
    back: {
      default: null,
    },
    name: {
      default: '',
    },
  },
}
</script>