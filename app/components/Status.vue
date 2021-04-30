<template>
  <v-select
    :filled="filled"
    :rounded="rounded"
    :outlined="outlined"
    :solo="solo"
    :flat="flat"
    :value="value"
    :items="c_items"
    hide-details
    name="status"
    label="État "
    :style="'max-width: ' + width + '; min-width:' + width"
    :height="height"
    :width="width"
    class="cp" dense
    item-text="name"
    item-value="status"
    @input="($e) => $emit('input', $e)"
  ></v-select>
</template>

<script>
export default {
  props: { dense: {
      default: true,
    },filled: {
      default: false,
    }, rounded: {
      default: false,
    }, outlined: {
      default: false,
    }, solo: {
      default: false,
    },flat: {
      default: false,
    },
    any: {
      default: false,
    },
    width: {
      default: 'initial',
    },
    height: {
      default: 50,
    },
    items: {
      default:
        'succeeded,draft,on_hold,on_processing,on_expedition,on_delivery,failed,canceled',
    },
    value: {
      default: null,
    },
  },
  data() {
    return { c_items: [] }
  },
  mounted() {
    if (this.items) {
      this.c_items = this.items.split(',').map((status) => {
        var obj = {}
        obj.name = this.$c[status]
        obj.status = status
        return obj
      })
      if (this.any) {
        this.c_items.unshift({ name: 'Tous', status: null })
      }
    }
  },
}
</script>