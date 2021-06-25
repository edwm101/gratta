<template>
  <div>
    <toolbar name="Tirage" back="manager-draw"></toolbar>
    <v-container grid-list-xs>
      <v-row>
        <v-col cols="12" md="4">
          <v-card class="elevation-0" :loading="loading" :disabled="loading">
            <v-form ref="form" class="overflow-auto" v-model="valid">
              <v-row no-gutters>
                <v-col cols="12" class="pa-2">
                  <v-card-actions>
                    <v-switch
                      hide-details
                      inset
                      label="Afficher"
                      class="ma-0 pa-1"
                      v-model="form.is_show"
                    ></v-switch>
                    <v-spacer></v-spacer>
                    <v-icon
                      :disabled="!(form.id && resume.amount == form.total_amount && resume.winner == form.nb_winner)"
                      x-large
                      class="white--text grey lighten-4 pa-1 rounded-circle bg-success"
                      @click="save('go')"
                      >mdi-play</v-icon
                    >
                  </v-card-actions>
                </v-col>
                <v-col cols="12" class="pa-2">
                  <InputNumberRange
                    filled
                    :min="1"
                    rounded
                    class="no-shadow input"
                    dense
                    hide-details
                    label="Numéro de tirage"
                    v-model="form.number"
                    :rules="[$method.rule_required]"
                  ></InputNumberRange
                ></v-col>
                <v-col cols="12" class="pa-2">
                  <InputNumberRange
                    filled
                    :min="1"
                    rounded
                    class="no-shadow input"
                    dense
                    hide-details
                    step="100"
                    label="Montant total
"
                    v-model="form.total_amount"
                    :rules="[$method.rule_required]"
                  ></InputNumberRange
                ></v-col>
                <v-col cols="12" class="pa-2">
                  <InputNumberRange
                    filled
                    :min="1"
                    rounded
                    class="no-shadow input"
                    dense
                    hide-details
                    step="1"
                    label="Nombre de gagnants
"
                    v-model="form.nb_winner"
                    :rules="[$method.rule_required]"
                  ></InputNumberRange
                ></v-col>
                <v-col cols="12" class="pa-2">
                  <v-text-field
                    filled
                    rounded
                    class="no-shadow input"
                    dense
                    hide-details
                    label="Nom"
                    v-model="form.name"
                    :rules="[$method.rule_required]"
                  ></v-text-field
                ></v-col>
                <v-col cols="12" class="pa-2">
                  <v-textarea
                    filled
                    rounded
                    class="no-shadow input"
                    rows="2"
                    dense
                    hide-details
                    label="Description"
                    v-model="form.description"
                  ></v-textarea>
                </v-col>
                <v-col cols="12" class="pa-2">
                  <v-card-actions class="pa-0">
                    <v-text-field
                      filled
                      rounded
                      dense
                      type="number"
                      class="no-shadow input"
                      rows="2"
                      hide-details
                      label="Début"
                      v-model="form.start_interval"
                    ></v-text-field>
                    <v-divider class="mx-1" vertical></v-divider>
                    <v-text-field
                      filled
                      rounded
                      dense
                      type="number"
                      class="no-shadow input"
                      rows="2"
                      hide-details
                      label="Fin"
                      v-model="form.end_interval"
                    ></v-text-field>
                  </v-card-actions>
                </v-col>
                <v-col cols="12" class="pa-2">
                  <dialog-date
                    filled
                    rounded
                    type="number"
                    class="no-shadow input"
                    rows="2"
                    :clearable="true"
                    dense
                    :no_time="false"
                    hide-details
                    label="Date de l'evenement"
                    v-model="form.event_date"
                  ></dialog-date>
                </v-col>
                <v-col cols="12" class="pa-2">
                  <v-text-field
                    filled
                    rounded
                    class="no-shadow input"
                    dense
                    hide-details
                    label="Vidéo Facebook"
                    v-model="form.live_link"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-form>
            <v-card-actions class="pa-2">
              <v-btn
                color="error darken-1"
                small
                text
                @click="remove()"
                v-if="form.id"
              >
                <v-icon> mdi-delete</v-icon></v-btn
              >
              <v-spacer></v-spacer>
              <v-btn
                color="primary"
                class="shadow"
                :disabled="!valid"
                @click="save()"
                large
                >Enregistrer <v-icon>mdi-save</v-icon></v-btn
              >
            </v-card-actions>
          </v-card>
        </v-col>
        <v-col cols="12" md="7" v-if="form.id">
          <v-row class="my-1" dense>
            <v-col>
              <v-card class="elevation-0 pa-2">
                <div class="text-center">
                  <div class="title font-weight-bold">
                    {{ $method.numberFormat(resume.total) }}
                  </div>
                  <div class="caption grey--text">Nombre de participants</div>
                </div>
              </v-card>
            </v-col>
            <v-col>
              <v-card class="elevation-0 pa-2 " dark  :class="
                  resume.winner == form.nb_winner
                    ? 'bg-success'
                    : 'warning darken-1'
                ">
                <div class="text-center">
                  <div class="title font-weight-bold">
                    {{ $method.numberFormat(resume.winner) }} /
                    {{ form.nb_winner }}
                  </div>
                  <div class="caption white--text">Nombre de gagnants</div>
                </div>
              </v-card>
            </v-col>
            <v-col>
              <v-card
                class="elevation-0 pa-2"
                :class="
                  resume.amount == form.total_amount
                    ? 'bg-success'
                    : 'warning darken-1'
                "
                dark
              >
                <div class="text-center">
                  <div class="title font-weight-bold">
                    {{ $method.moneyFormat(resume.amount, '') }}/
                    {{ $method.moneyFormat(form.total_amount, '') }}
                  </div>
                  <div class="caption white--text">Montant total (TND)</div>
                </div>
              </v-card>
            </v-col>
          </v-row>
          <v-simple-table fixed-header v-if="1 == 0"></v-simple-table>
          <div
            class="v-data-table v-data-table--fixed-header theme--light rounded-lg mt-4 overflow-hidden"
          >
            <div class="v-data-table__wrapper">
              <v-card-actions>
                <div class="title pa-2">Les codes</div>
                <v-spacer></v-spacer>
                <v-btn
                  color="success"
                  class="shadow"
                  @click="
                    dialog = true
                    form_code = {}
                  "
                  >Ajouter</v-btn
                >
              </v-card-actions>
              <v-divider></v-divider>
              <v-card-actions class="pa-0 px-1">
                <v-text-field
                  solo
                  flat
                  dense
                  name="name"
                  style="min-width: 160px"
                  height="48"
                  class="white border"
                  append-icon="mdi-magnify"
                  hide-details
                  @input="initCode()"
                  v-model="filter.q"
                  label="p.ex Code"
                ></v-text-field>
              </v-card-actions>
              <v-divider></v-divider>
              <table>
                <thead>
                  <tr>
                    <th class="text-left">#</th>
                    <th class="text-left">Code</th>
                    <th class="text-left">Montant</th>
                    <th class="text-left">Nom</th>
                    <th class="text-left">Téléphone</th>
                    <th class="text-left">Gouvernorat</th>
                  </tr>
                </thead>

                <draggable
                  v-model="items"
                  group="people"
                  @end="saveSort()"
                  handle=".grabbable"
                  tag="tbody"
                >
                  <tr
                    v-for="item in items"
                    :key="item.id"
                    class="cp"
                    @click="
                      dialog = true
                      form_code = { ...item }
                    "
                  >
                    <td
                      :style="
                        item.amount
                          ? 'border-left:4px solid #05a081!important'
                          : ''
                      "
                    >
                      <v-icon class="grabbable" v-if="!filter.q && item.amount"
                        >mdi-drag</v-icon
                      >
                    </td>
                    <td>
                      <b> {{ item.code }}</b>
                    </td>
                    <td>
                      <b> {{ $method.moneyFormat(item.amount) }}</b>
                    </td>
                    <td>
                      <b> {{ item.full_name }}</b>
                    </td>
                    <td>
                      <b> {{ item.phone }}</b>
                    </td>
                    <td>
                      <b> {{ item.governorate }}</b>
                    </td>
                  </tr></draggable
                >
              </table>
              <card-list-vide
                v-if="items.length < 1"
                class="mx-auto"
              ></card-list-vide>

              <div
                class="text-center mt-3"
                style="width: 100%"
                v-if="filter.cp < detail.pages"
              >
                <v-btn
                  color="white"
                  large
                  block
                  class="mx-auto"
                  @click="
                    filter.cp++
                    initCode(false)
                  "
                  >Afficher plus</v-btn
                >
              </div>
            </div>
          </div>
        </v-col>
      </v-row>
    </v-container>

    <v-dialog
      v-model="dialog"
      scrollable
      max-width="350px"
      transition="dialog-transition"
    >
      <v-card class="gre white lighten-4">
        <v-card-actions class="pa-2">
          <v-spacer></v-spacer>
          <v-icon class="close" @click="dialog = false">mdi-close</v-icon>
        </v-card-actions>
        <div>
          <v-form v-model="valid_code">
            <v-card-actions>
              <v-text-field
                filled
                :rules="[$method.rule_required]"
                rounded
                dense
                hide-details
                type="number"
                class="no-shadow input"
                rows="2"
                label="Code"
                v-model="form_code.code"
              ></v-text-field>
              <v-divider class="mx-1" vertical></v-divider>
              <v-text-field
                filled
                rounded
                dense
                hide-details
                type="number"
                class="no-shadow input"
                rows="2"
                label="Montant"
                v-model="form_code.amount"
              ></v-text-field>
            </v-card-actions>
            <div class="pa-2">
              <v-text-field
                filled
                rounded
                dense
                hide-details
                class="no-shadow input"
                rows="2"
                label="Nom"
                v-model="form_code.full_name"
              ></v-text-field>
            </div>
            <div class="pa-2">
              <v-text-field
                filled
                rounded
                dense
                hide-details
                class="no-shadow input"
                rows="2"
                type="number"
                label="Téléphone"
                v-model="form_code.phone"
              ></v-text-field>
            </div>
            <div class="pa-2">
              <v-select
                filled
                :items="governorates"
                rounded
                dense
                hide-details
                class="no-shadow input"
                rows="2"
                item-text="name"
                item-value="name"
                label="Gouvernorat"
                v-model="form_code.governorate"
              ></v-select>
            </div>
          </v-form>
        </div>
        <v-card-actions class="pa-2">
          <v-btn color="error" text @click="removeCode()" v-if="form_code.id"
            >Supprimer</v-btn
          >
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            :disabled="!valid_code"
            @click="saveCode(form_code)"
            class="shadow"
            >enregistrer</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>


<script>
import draggable from 'vuedraggable'

export default {
  components: {
    draggable,
  },
  data() {
    return {
      loading: false,
      dialog: false,
      form_code: {},
      valid_code: false,
      form: { number: 1 },
      valid: false,
      items: [],
      filter: {
        max: 1000,
      },
      detail: {},
      resume: {},
      governorates: [],
    }
  },
  watch: {
    '$route.query.id'(val) {
      if (!val) this.form = {}
      this.init()
    },
  },
  methods: {
    saveSort() {
      var items = this.items.map((item) => {
        return item.id
      })
      this.$api
        .post('person/admin/draw/sort', {
          ids: items,
        })
        .then((res) => {})
    },
    setResume() {
      this.$api('person/admin/draw/resume', {
        params: { id: this.form.id },
      }).then((res) => {
        this.resume = res.data.result
      })
    },
    init() {
      this.$api('person/admin/draw', {
        params: { id: this.$route.query.id },
      }).then((res) => {
        this.form = res.data.result
        if (this.form.id) {
          this.initCode()
          this.setResume()
        }
      })
    },
    async save(type = null) {
      this.loading = true
      await this.$api
        .post('person/admin/draw', {
          ...this.form,
        })
        .then((res) => {
          if (res) {
            if (!this.form.id)
              this.$router.push({
                name: 'manager-draw-edit',
                query: { id: res.data.result.id },
              })
            this.$set(this.form, 'id', res.data.result.id)
            if (type == 'go') {
              this.$router.push({
                name: 'play-id',
                params: { id: this.form.id },
              })
            }
          }
        })
        .catch((e) => {})
      this.loading = false
    },
    async saveCode() {
      this.loading = true
      await this.$api
        .post('person/admin/draw/code', {
          ...this.form_code,
          draw_id: this.form.id,
        })
        .then((res) => {
          if (this.form_code.id) {
            this.items = this.items.map((item) => {
              if (item.id == this.form_code.id) {
                item = { ...this.form_code }
              }
              return item
            })
          } else {
            this.initCode()
          }
          this.setResume()
        })
        .catch((e) => {})
      this.dialog = false
      this.loading = false
    },

    initCode(reset = true) {
      if (reset) this.filter.cp = 1
      this.filter.draw_id = this.form.id
      this.$api('person/admin/draw/code/all', {
        params: { ...this.filter },
      }).then((res) => {
        this.detail = res.data.detail
        if (reset) {
          this.items = res.data.result
        } else {
          this.items = [...this.items, ...res.data.result]
        }
      })
    },
    remove() {
      var r = confirm('Êtes-vous sûr de vouloir supprimer cet élément!')
      if (r) {
        this.$api
          .delete('person/admin/draw', {
            params: { id: this.form.id },
          })
          .then((res) => {
            this.items = this.items.filter((item) => {
              return item.id != this.form.id
            })
            this.$router.push({ name: 'manager-draw' })
            this.dialog = false
          })
      }
    },
    removeCode() {
      var r = confirm('Êtes-vous sûr de vouloir supprimer cet élément!')
      if (r) {
        this.$api
          .delete('person/admin/draw/code', {
            params: { id: this.form_code.id },
          })
          .then((res) => {
            this.items = this.items.filter((item) => {
              return item.id != this.form_code.id
            })
            this.dialog = false
          })
      }
    },
  },
  mounted() {
    this.$api('public/location/governorate').then((res) => {
      this.governorates = res.data.result
    })
    this.init()
  },
}
</script>