<template>
  <div>
    <v-dialog v-model="dialog" scrollable max-width="300px">
      <template v-slot:activator="{ on, attrs }">
        <div v-bind="attrs" v-on="on">
          <slot>
            <v-text-field
              :label="label"
              append-icon="mdi-calendar-month"
              readonly
              dense
              :value="label_date"
              hide-details
              class="white-input white"
              filled rounded
            ></v-text-field>
          </slot></div
      ></template>
      <v-card>
        <v-date-picker
          v-model="c_date"
          locale="fr"
          no-title
          @input="setDate()"
          scrollable
        >
        </v-date-picker>
        <v-card-actions class="pa-1" v-if="c_date && !no_time">
          <v-select
            dense
            outlined
            :items="times"
            v-model="time"
            label="Time"
            hide-details
            @change="setDate()"
          ></v-select>
        </v-card-actions>
        <v-card-actions class="pa-1">
          <v-btn color="error" @click="dialog = false" text>Fermer</v-btn>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="dialog = false">OK</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      default: null,
    },
    label: {
      default: 'Date',
    },
    no_time: {
      default: true,
    },
  },
  data() {
    return {
      c_date: null,
      dialog: false,
      times: [],
      time: null,
      label_date: null,
    }
  },
  watch: {
    dialog() {},
    value() {
      if (this.value) {
        this.c_date = this.$moment.unix(this.value).format('YYYY-MM-DD')
        this.time = this.$moment.unix(this.value).format('HH:mm')
      } else {
        this.c_date = null
      }
      this.setDate()
    },
  },
  methods: {
    setDate() {
      if (this.c_date && this.time) {
        this.label_date = this.c_date + ' ' + this.time
      }
      if (this.c_date) {
        this.label_date = this.c_date
      } else {
        this.label_date = null
      }
      this.$emit('input', new Date(this.label_date).getTime() / 1000)
    },
  },
  mounted() {
    if (this.value) {
      this.c_date = this.$moment.unix(this.value).format('YYYY-MM-DD')
      this.time = this.$moment.unix(this.value).format('HH:mm')
      this.setDate()
    } else {
      this.c_date = null
    }
    for (var hour = 0; hour < 24; hour++) {
      for (let minute = 0; minute < 60; minute += 15) {
        this.times.push(('0' + hour).slice(-2) + ':' + ('0' + minute).slice(-2))
      }
    }
  },
}
</script>