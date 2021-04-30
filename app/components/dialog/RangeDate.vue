<template>
  <div>
    <slot>
      <div class="elevation-0 pa-0 transparent cp" @click="dialog = true">
        <div class="body-2 font-weight-medium">Période</div>
        <v-list-item-subtitle
          class="caption pa-0 font-weight-medium"
          v-if="value.start"
        >
          {{ $moment(value.start).format('ll') }}
          {{ value.end ? ' - ' + $moment(value.end).format('ll') : '' }}
        </v-list-item-subtitle>

        <v-list-item-subtitle
          class="caption grey--text pa-0 font-weight-medium"
          v-else
        >
          {{ label }}
        </v-list-item-subtitle>
      </div>
    </slot>
    <v-dialog v-model="dialog" scrollable max-width="420px">
      <v-card class="white">
        <v-card-actions class="pa-1">
          <v-spacer></v-spacer>
          <v-icon class="close " @click="dialog = false">mdi-close</v-icon>
        </v-card-actions>
        <DatePicker
          v-model="c_date"
          label="Sélectionner une date"
          range
          inline
          color="#0d5dac"
          :custom-shortcuts="$c['date_shortcuts']"
          locale="fr"
          noHeader
          right
          noKeyboard
          format="YYYY-MM-DD HH:mm"
          formatted="ll"
        ></DatePicker>
        <v-card-actions class="pa-1">
          <v-btn
            color="error"
            text
            @click="
              c_date = {}
              setDate()
              dialog = false
            "
            ><v-icon class="mr-1">mdi-reload</v-icon> Réinitialiser
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            color="primary" class="shadow"
            @click="
              setDate()
              dialog = false
            "
            >Appliquer</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      default: {},
    },
    label: {
      default: 'Sélectionner une date',
    },
  },
  data() {
    return {
      c_date: {},
      dialog: false,
    }
  },
  methods: {
    setDate() {
      this.$set(this.value, 'start', this.c_date.start)
      this.$set(this.value, 'end', this.c_date.end)
      this.$emit('input', this.value)
    },
  },
  mounted() {},
}
</script>