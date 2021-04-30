<template>
  <div @click="toggleFullscreen" v-if="isSupported">
    <slot>
      <v-btn text :large="large" :height="height" :size="size" icon>
        <v-icon :large="large" :size="size">{{
          isFullscreen ? 'mdi-fullscreen-exit' : 'mdi-fullscreen'
        }}</v-icon>
      </v-btn>
    </slot>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      default: false,
    },
    large: {
      default: false,
    },
    height: {
      default: 40,
    },
    size: {
      default: 20,
    },
    elem: {
      default: 'html',
    },
  },
  data: () => ({
    isFullscreen: false,
    isSupported: true,
  }),
  watch: {
    isFullscreen() {
      this.$emit('input', this.isFullscreen)
    },
    value() {
      this.isFullscreen = this.value
    },
  },
  methods: {
    toggleFullscreen(event) {
      var elem = document.querySelector(this.elem)
      if (
        document.fullscreenEnabled ||
        document.webkitFullscreenEnabled ||
        document.mozFullScreenEnabled ||
        document.msFullscreenEnabled
      ) {
        if (!this.isFullscreen) {
          this.isFullscreen = true

          if (elem.requestFullscreen) {
            elem.requestFullscreen()
            return
          } else if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen()
            return
          } else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen()
            return
          } else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen()
            return
          }
        } else {
          this.isFullscreen = false

          if (document.exitFullscreen) {
            document.exitFullscreen()
            return
          } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen()
            return
          } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen()
            return
          } else if (document.msExitFullscreen) {
            document.msExitFullscreen()
            return
          }
        }
      } else {
        this.isSupported = false
      }
    },
  },
  mounted() {
    this.isFullscreen = this.value
  },
}
</script>