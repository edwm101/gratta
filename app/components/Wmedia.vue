<template>
  <v-card
    class="line-height-0"
    :tile="tile"
    :height="height"
    :width="width"
    :class="_class"
  >
    <img class="center-in" :src="path" :alt="alt" v-if="isImage()" />

    <video
      @mouseenter="
        is_mouse ? ($refs.video.muted = false) : ''
      "
      @mouseleave="
        is_mouse ? ($refs.video.muted = true) : ''
      "
      ref="video"
      class="center-in"
      :controls="controls"
      :autoplay="autoplay"
      :loop="loop"
      :muted="muted"
      v-else-if="isVideo()"
    >
      <source :src="path" type="video/mp4" />
      <source :src="path" type="video/ogg" />
      Your browser does not support the video tag.
    </video>
    <!-- <v-icon v-if="isVideo()" class="white rounded ma-1 black--text float-left">
      {{ muted ? 'mdi-volume-off' : 'mdi-volume-on' }}
    </v-icon> -->
  </v-card>
</template>

<script>
export default {
  props: {
    _class: {
      default: 'elevation-0 grey lighten-3',
    },
    alt: {
      default: null,
    },
    tile: {
      default: true,
    },
    is_mouse: {
      default: true,
    },
    autoplay: {
      default: false,
    },
    controls: {
      default: false,
    },
    loop: {
      default: true,
    },
    muted: {
      default: true,
    },
    path: {
      default: null,
    },
    height: {
      default: '120px',
    },
    width: {
      default: '100%',
    },
    is_pause: {
      default: null,
    },
  },
  watch: {
    is_pause() {
      if (this.$refs.video) {
        this.$refs.video.pause()
      }
    },
  },
  methods: {
     getExtension() {
      var parts = this.path ? this.path.split('.') : []
      if (parts && parts.length > 0) return parts[parts.length - 1]
      else return []
    },
    isImage() {
      var ext = this.getExtension()
      if (ext && ext != '') {
        switch (ext.toLowerCase()) {
          case 'svg':
          case 'jfif':
          case 'jpeg':
          case 'jpg':
          case 'gif':
          case 'bmp':
          case 'png':
            //etc
            return true
        }
      }
      return false
    },
    isVideo() {
      var ext = this.getExtension()
      if (ext && ext != '') {
        switch (ext.toLowerCase()) {
          case 'm4v':
          case 'avi':
          case 'mpg':
          case 'mp4':
          case 'mkv':
            // etc
            return true
        }
      }
      return false
    },
  },
  mounted() {},
  beforeDestroy: function () {
    if (this.$refs.video) {
      this.$refs.video.pause()
    }
  },
}
</script>