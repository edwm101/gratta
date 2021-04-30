<template>
  <v-card-actions class="caption pa-0">
    <span
      class="rounded-circle mr-1 overflow-hidden"
      style="width: 10px; height: 10px"
      :style="{
        background: color,
        animation: is_on ? 'radar-animation 1s ease-out infinite' : '',
      }"
    ></span>
    {{ text }}
  </v-card-actions>
</template>

<script>
export default {
  props: { init: { default: 'green' }, date: { default: null } },
  data() {
    return {
      color: 'green',
      is_on: false,
      text: '',
    }
  },
  watch: {
    date() {
      this.start()
    },
  },
  methods: {
    start() {
      if (!this.date) {
        this.color = ''
        this.is_on = false
      } else {
        if (this.date + 32 > Math.floor(Date.now() / 1000)) {
          this.color = 'green'
          this.text = 'En ligne'
          this.is_on = true
        } else {
          this.color = 'grey'
          this.text = 'Hors ligne'
          this.is_on = false
        }
      }
    },
  },
  mounted() {
    this.start()
  },
}
</script>