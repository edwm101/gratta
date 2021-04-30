<template>
  <v-card class="input" light outlined>
    <v-card-actions class="pa-0 field-range">
      <slot>
        <v-text-field
          :autofocus="autofocus"
          tile
          filled
          rounded
          v-model="content"
          flat
          type="number"
          height="50"
          dense
          style="font-size: 20px !important; font-weight: 700; border-radius: 0"
          :style="'max-width:' + width + 'px'"
          @input="field"
          class="title g elevation-0"
          hide-details
          :min="min"
          @keyup.enter.native="enter()"
          :label="label"
          :suffix="suffix"
        ></v-text-field>
      </slot>
      <v-divider v-if="!no_inc" style="margin: 0 0.5px" vertical></v-divider>

      <v-btn
        v-if="!no_inc"
        tile
        small
        class="elevation-0"
        height="50"
        @click="minus($event)"
      >
        <v-icon>mdi-minus</v-icon>
      </v-btn>
      <v-divider style="margin: 0 0.5px" v-if="!no_inc" vertical></v-divider>

      <v-btn
        v-if="!no_inc"
        tile
        small
        class="elevation-0"
        height="50"
        @click="plus($event)"
      >
        <v-icon>mdi-plus</v-icon>
      </v-btn>
    </v-card-actions>
  </v-card>
</template> 
  
  <script>
export default {
  props: {
    autofocus: {
      default: false,
    },
    width: { default: 700 },
    label: { default: '0.00' },
    suffix: {
      default: null,
    },
    step: {
      type: Number,
      default: 1,
    },
    init: {
      type: Number,
      default: 0,
    },
    min: {
      type: Number,
      default: 0,
    },
    value: {
      default: 0,
    },
    no_inc: {
      default: false,
    },
  },
  data() {
    return {
      content: this.content,
    }
  },
  watch: {
    value(val) {
      if (!val) val = this.min
      this.content = parseFloat(val)
    },
  },
  methods: {
    enter() {
      this.$emit('enter', this.content)
    },
    field() {
      if (this.content < this.min) {
        this.content = this.min
      }
      this.$emit('input', this.content)
    },
    plus() {
      if (!this.content) this.content = 0
      this.content = parseFloat(this.content) + parseFloat(this.step)
      this.$emit('input', this.content)
    },
    minus() {
      this.content > this.min ? (this.content -= parseFloat(this.step)) : ''
      if (this.content < 0) this.content = 0
      this.$emit('input', this.content)
    },
  },

  mounted() {
    this.content = this.value
  },
}
</script>