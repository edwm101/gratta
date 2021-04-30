<template>
  <v-card
    style="width: 100%"
    tile
    class="transparent search-form elevation-0"
    v-click-outside="onClickOutside"
  >
    <v-text-field
      filled
      rounded
      :autocomplete="false"
      v-model="value"
      :class="rounded ? 'rounded-xl' : ''"
      class="ronuded"
      @input="setRelated"
      hide-details
      @click="active = true"
      dense
      :height="height"
      :clearable="clearable"
      @keyup.enter.native="
        form = { q: value }
        setResult()
      "
      @keyup.38="setFocus('minus')"
      @keyup.40="setFocus('plus')"
      @click:clear="
        form = {}
        value = null
        active = false
        setResult()
      "
      @click:append="setResult()"
      :append-icon="icon"
      :label="label"
    ></v-text-field>

    <v-progress-linear
      indeterminate
      value="100"
      v-if="loading"
      color="primary"
      background-color="primary"
    ></v-progress-linear>
    <v-list
      dense
      light
      class="overflow-auto shadow mt-2 rounded"
      v-if="active && value && !loading && (items.length != 0 || is_show_vide)"
      ref="go_to_top"
      style="position: absolute; z-index: 5; width: 100%; max-height: 250px"
    >
      <card-list-vide v-if="items.length == 0 && is_show_vide"></card-list-vide>
      <v-list-item
        v-for="(item, index) in items"
        :key="index"
        class="cp pa-1 py-0 px-0"
        ref="li_search"
        @mouseenter="focus = index"
        :class="{
          focus:
            index === focus ||
            (typeof item.selected !== 'undefined' && item.selected),
        }"
        @click="
          value = is_replace ? item.value : value
          form = item
          setResult()
        "
      >
        <v-list-item-content>
          <div class="caption pa-2 pt-1 font-weight-medium">
            <v-icon small class="success--text" v-if="item.selected"
              >mdi-check</v-icon
            >
            {{ item.value }}
          </div>
        </v-list-item-content>
        <v-icon class="rounded mx-2 pa-1 grey lighten-3"
          >mdi-chevron-right</v-icon
        >
      </v-list-item>
    </v-list>
  </v-card>
</template>

<script>
export default {
  props: {
    height: {
      default: 40,
    },
    items: {
      type: Array,
      default: function () {
        return []
      },
    },
    label: { default: '' },
    rounded: { default: true },
    loading: { default: false },
    type: { default: 'location' },
    icon: { default: 'mdi-map-marker' },
    init: {
      default: null,
    },
    clearable: {
      default: true,
    },
    is_replace: {
      default: true,
    },
    is_show_vide: { default: true },
  },
  data() {
    return {
      focus: 0,
      value: null,
      form: {},
      active: false,
      timer: null,
      c_value: null,
    }
  },
  watch: {
    init(init) {
      this.value = init
    },
  },
  methods: {
    setFocus(type) {
      if (this.$refs.go_to_top && this.items.length >= 0 && this.active) {
        if (type == 'minus') {
          if (this.focus > 0) this.focus--
          if (this.$refs.li_search) {
            this.$refs.go_to_top.scrollTop =
              this.$refs.go_to_top.scrollTop -
              this.$refs.li_search[0].clientHeight
          }
        } else {
          if (this.focus < this.items.length - 1) this.focus++
          if (this.$refs.li_search) {
            this.$refs.go_to_top.scrollTop =
              this.$refs.go_to_top.scrollTop +
              this.$refs.li_search[0].clientHeight
          }
        }
        this.value = this.items[this.focus].value
      }
    },
    setRelated() {
      this.active = true
      if (this.value && this.value.length > 0) {
        if (!this.loading) {
          this.c_value = '' + this.value

          this.$emit('input', this.value)
        }
        clearTimeout(this.timer)
        this.timer = setTimeout(() => {
          if (this.c_value != this.value) this.$emit('input', this.value)
        }, 1000)
      } else {
        this.active = false
      }
    },
    onClickOutside() {
      this.active = false
    },
    setResult() {
      this.active = false
      this.$emit('change', this.form)
    },
  },
  mounted() {
    this.value = this.init
  },
}
</script>