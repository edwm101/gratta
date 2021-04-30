<template>
  <div></div>
</template>

<script>
export default {
  watch: {
    '$store.state.shop.info'(info) {},
  },
  mounted() {
    this.$router.push({ name: 'manager-draw' })
  },
}
</script>