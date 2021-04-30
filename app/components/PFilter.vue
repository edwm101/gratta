<template>
  <div style="height: 48px; width: 100%" class="overflow-auto">
    <v-card-actions class="pa-0 white overflow-auto">
      <v-btn
        class="white black--text elevation-0"
        small
        tile
        height="48"
        @click="setData()"
        v-if="reload"
      >
        <v-icon>mdi-reload</v-icon></v-btn
      >
      <v-divider
        style="margin: 0 0.5px"
        v-if="range && reload"
        vertical
      ></v-divider>

      <v-card class="px-2 pa-1 elevation-0" v-if="range">
        <DialogRangeDate v-model="filter" @input="setData()"></DialogRangeDate>
      </v-card>
      <v-divider v-if="search" style="margin: 0 0.5px" vertical></v-divider>

      <v-text-field
        solo
        flat
        dense
        v-if="search"
        name="name"
        style="min-width: 160px"
        height="48"
        class="white"
        append-icon="mdi-magnify"
        hide-details
        @input="setData()"
        v-model="filter.q"
        :label="label_search"
        id="id"
      ></v-text-field>
      <v-divider v-if="status" style="margin: 0 0.5px" vertical></v-divider>

      <v-select
        solo
        flat
        v-if="status"
        dense
        :items="c_status_items"
        hide-details
        name="status"
        label="État "
        style="max-width: 120px; min-width: 120px"
        height="48"
        class="white mr-1"
        item-text="name"
        @change="setData()"
        item-value="status"
        v-model="filter.status"
      ></v-select>
      <v-divider v-if="sort" style="margin: 0 0.5px" vertical></v-divider>

      <v-select
        solo
        flat
        v-if="sort"
        dense
        style="max-width: 120px; min-width: 120px"
        height="48"
        class="white mr-1"
        hide-details
        :items="sort_items"
        @change="setData()"
        v-model="sort_value"
        label="Trier par"
        item-text="text"
        return-object
      ></v-select>
      <v-divider style="margin: 0 0.5px" v-if="car" vertical></v-divider>

      <v-select
        solo
        flat
        v-if="car"
        dense
        style="max-width: 120px; min-width: 120px"
        height="48"
        class="white mr-1"
        hide-details
        :items="cars"
        @change="setData()"
        v-model="filter.car_id"
        label="Véhicule"
        item-text="name"
        item-value="id"
      ></v-select>
    </v-card-actions>
  </div>
</template>


<script>
export default {
  props: {
    value: {
      default: {},
    },
    label: {
      default: 'Date',
    },
    label_search: {
      default: 'Recherche',
    },
    car: { default: false },
    status: { default: false },
    is_dialog: { default: true },
    sort: { default: false },
    reload: { default: true },
    search: { default: true },
    range: { default: true },
    sort_items: {
      type: Array,
      default() {
        return [
          {
            sortby: 'p.creation_date',
            sort_type: 'desc',
            text: 'Date décroissant',
          },
          {
            sortby: 'p.creation_date',
            sort_type: 'asc',
            text: 'Date croissant',
          },
        ]
      },
    },
    status_items: {
      default:
        'succeeded,draft,on_hold,on_processing,on_expedition,on_delivery,failed,canceled',
    },
  },
  data() {
    return {
      dialog: false,
      filter: {},
      sort_value: {},
      dialog: false,
      items: [],
      cars: [],
      c_status_items: [],
    }
  },
  methods: {
    setData() {
      if ('q' in this.filter) this.$set(this.value, 'q', this.filter.q)
      if ('status' in this.filter)
        this.$set(this.value, 'status', this.filter.status)

      if ('start' in this.filter)
        this.$set(this.value, 'start', this.filter.start)

      if ('end' in this.filter) this.$set(this.value, 'end', this.filter.end)

      if ('car_id' in this.filter)
        this.$set(this.value, 'car_id', this.filter.car_id)

      if ('sort_type' in this.sort_value)
        this.$set(this.value, 'sort_type', this.sort_value.sort_type)

      if ('sort_type' in this.sort_value)
        this.$set(this.value, 'sortby', this.sort_value.sortby)

      this.$emit('input', this.value)
    },
  },
  mounted() {
    this.filter = { ...this.value }

    if (this.car) {
      this.$api('user/car/all-simple').then((res) => {
        this.cars = res.data.result
        this.cars.unshift({ id: null, name: 'Tous' })
      })
    }

    if (this.status_items) {
      this.c_status_items = this.status_items.split(',').map((status) => {
        var obj = {}
        obj.name = this.$c[status]
        obj.status = status
        return obj
      })
      this.c_status_items.unshift({ name: 'Tous', status: null })
    }
  },
}
</script>