<template>
  <div>
    <toolbar name="Tirage" back="manager-draw"></toolbar>
    <v-container grid-list-xs>
      <v-row>
        <v-col cols="12" md="4">
          <v-card class="elevation-0">
            <v-form
              ref="form"
              class="overflow-auto"
              style="max-height: 400px"
              v-model="valid"
            >
              <v-row no-gutters>
                <v-col cols="12" class="pa-2">
                  <v-text-field
                    filled
                    rounded
                    class="no-shadow input"
                    dense
                    hide-details
                    label="Nom"
                    v-model="form.name"
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
                  <dialog-date
                    filled
                    rounded
                    type="number"
                    class="no-shadow input"
                    rows="2"
                    dense
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
          <v-card :loading="loading" :disabled="loading">
            <v-card-actions>
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
              <v-divider class="mx-1" vertical></v-divider>

              <v-btn
                color="success"
                class="shadow"
                height="50"
                @click="generateCode()"
                >Généré</v-btn
              >
            </v-card-actions>
          </v-card>
          <v-card
            dark
            v-if="form.file"
            class="mt-4 mb-2 header"
            :href="form.file"
            target="_blank"
            rel="noopener noreferrer"
          >
            <v-card-actions>
              <v-icon>mdi-file</v-icon>
              <div class="mx-2">{{ form.file }}</div>
              <v-btn color="success" icon>
                <v-icon large>mdi-download</v-icon></v-btn
              >
            </v-card-actions>
          </v-card>

          <v-row class="my-1" dense>
            <v-col>
              <v-card class="elevation-0 pa-2">
                <div class="text-center">
                  <div class="title font-weight-bold">
                    {{ $method.numberFormat(resume.total) }}
                  </div>
                  <div class="caption grey--text">Nombre de codes</div>
                </div>
              </v-card>
            </v-col>
            <v-col>
              <v-card class="elevation-0 pa-2">
                <div class="text-center">
                  <div class="title font-weight-bold">
                    {{ $method.numberFormat(resume.winner) }}
                  </div>
                  <div class="caption grey--text">Nombre de gagnants</div>
                </div>
              </v-card>
            </v-col>
            <v-col>
              <v-card class="elevation-0 pa-2">
                <div class="text-center">
                  <div class="title font-weight-bold">
                    {{ $method.moneyFormat(resume.amount) }}
                  </div>
                  <div class="caption grey--text">Montant total</div>
                </div>
              </v-card>
            </v-col>
            <v-col v-if="resume.winner">
              <v-card class="elevation-0 pa-2">
                <div class="text-center">
                  <div class="title font-weight-bold">
                    {{ $method.moneyFormat(resume.amount / resume.winner) }}
                  </div>
                  <div class="caption grey--text">Montant moyen</div>
                </div>
              </v-card>
            </v-col>
          </v-row>
          <v-simple-table fixed-header v-if="1 == 0"></v-simple-table>
          <div
            class="v-data-table v-data-table--fixed-header theme--light rounded-lg mt-4 overflow-hidden"
          >
            <div class="v-data-table__wrapper">
              <div class="title pa-2">Les codes</div>
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
                <v-checkbox
                  label="Les gagnants"
                  v-model="filter.is_winner"
                  class="ma-0 pt-4 pa-0 pr-2"
                  @change="initCode()"
                  value="value"
                ></v-checkbox>
              </v-card-actions>
              <v-divider></v-divider>
              <table>
                <thead>
                  <tr>
                    <th class="text-left">Code</th>
                    <th class="text-left">Montante</th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="item in items" :key="item.id" class="cp">
                    <td>
                      <b> {{ item.code }}</b>
                    </td>
                    <td>
                      <v-text-field
                        solo
                        flat
                        style="width: 100px"
                        hide-details
                        placeholder="p.ex 50"
                        v-model="item.amount"
                        @input="saveCode(item)"
                        dense
                        class="border"
                      ></v-text-field>
                    </td>
                  </tr>
                </tbody>
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
  </div>
</template>


<script>
export default {
  data() {
    return {
      loading: false,
      dialog: false,
      form: {},
      valid: false,
      items: [],
      filter: {
        max: 10,
      },
      detail: {},
      resume: {},
    }
  },
  watch: {
    '$route.query.id'(val) {
      if (!val) this.form = {}
      this.init()
    },
  },
  methods: {
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
    async save() {
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
          }
        })
        .catch((e) => {})
      this.loading = false
    },
    saveCode(item) {
      this.$api
        .post('person/admin/draw/code', {
          ...item,
        })
        .then((res) => {
          this.setResume()
        })
        .catch((e) => {})
    },
    async generateCode() {
      this.loading = true
      console.log(this.form)
      await this.$api
        .post('person/admin/draw/generate-code', {
          ...this.form,
          draw_id: this.form.id,
        })
        .then((res) => {
          this.$set(this.form, 'draw_file', res.data.result.draw_file)
          this.initCode()
          this.setResume()
        })
        .catch((e) => {})
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
            this.dialog = false
          })
      }
    },
  },
  mounted() {
    this.init()
  },
}
</script>