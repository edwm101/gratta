<template>
  <div>
    <toolbar name="Contact" class="">
      <v-btn
        color="primary"
        class="shadow"
        @click="
          dialog = true
          form = { password: $method.randomString(6) }
        "
        >Ajouter
      </v-btn>
    </toolbar>

    <v-container>
      <div style="max-width: 500px">
        <div class="rounded-lg overflow-hidden fb-shadow white">
          <PFilter
            class="rounded white"
            v-model="filter"
            @input="init()"
          ></PFilter>
          <v-divider></v-divider>
          <div
            class="pa-2 px-3 sub-title font-weight-bold"
            v-if="detail.total > 1"
          >
            {{ $method.numberFormat(detail.total) }} clients
          </div>
        </div>
        <CardListVide class="mt-3" v-if="items.length == 0"> </CardListVide>
        <div v-for="(data, k) in items_date" :key="k">
          <v-card-actions class="pa-0 my-4">
            <v-divider></v-divider>
            <div
              class="body-2 rounded-lg pa-1 px-2 mx-2 white font-weight-medium"
            >
              {{ data.key }}
            </div>
            <v-divider></v-divider>
          </v-card-actions>
          <v-row>
            <v-col
              cols="12"
              sm="6"
              v-for="(item, key) in data.items"
              :key="item.id"
            >
              <v-card
                class="mx-auto mb-6 elevation-0 rounded-lg"
                :style="{
                  borderLeft: '5px solid ' + $c['color_' + item.status],
                }"
                @click="
                  form = { ...item }
                  dialog = true
                "
              >
                <v-list-item class="pa-0">
                  <v-list-item-content class="px-2 py-2">
                    <v-list-item-title class="body-1 font-weight-bold">
                      <span
                        :style="{ background: $c['color_' + item.status] }"
                        outlined
                        small
                        class="white--text rounded caption px-2 pa-1 font-weight-regular body-1"
                      >
                        {{ $c[item.status] }}
                      </span>
                      <span class="mx-1"></span>
                      {{ item.full_name }}
                    </v-list-item-title>
                    <v-list-item-subtitle class="body-2" v-if="item.phone">{{
                      item.phone
                    }}</v-list-item-subtitle>
                    <v-list-item-subtitle class="body-2" v-if="item.address">{{
                      item.address
                    }}</v-list-item-subtitle>

                    <v-list-item-subtitle class="body-2">{{
                      $moment.unix(item.creation_date).format('lll')
                    }}</v-list-item-subtitle>
                    <div class="caption" v-if="item.message">
                      <b>Sujet :</b>
                      {{ item.subject ? '“' + item.subject + '”' : '' }}
                    </div>
                    <div class="caption" v-if="item.message">
                      <b>Message :</b>
                      {{ item.message ? '“' + item.message + '”' : '' }}
                    </div>
                    <div class="caption" v-if="item.note">
                      <b>Note :</b>
                      {{ item.note ? '“' + item.note + '”' : '' }}
                    </div>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
            </v-col>
          </v-row>
        </div>
        <div class="text-center">
          <v-btn
            color="success"
            class="mx-auto"
            v-if="filter.cp < detail.pages"
            @click="
              filter.cp++
              init(false)
            "
            >Afficher plus</v-btn
          >
        </div>
      </div>
    </v-container>

    <v-dialog
      v-model="dialog"
      scrollable
      max-width="350px"
      transition="dialog-transition"
    >
      <v-card :loading="loading" :disabled="loading">
        <v-card-actions class="pa-2">
          <div class="title">{{ form.id ? '#' + form.id : '' }}</div>
          <v-spacer></v-spacer>
          <v-icon @click="dialog = false" class="colse">mdi-close</v-icon>
        </v-card-actions>

        <v-form
          ref="form"
          class="overflow-auto"
          style="max-height: 400px"
          v-model="valid"
        >
          <v-row no-gutters>
            <v-col cols="12" class="pa-2">
              <status
                filled
                rounded
                class="no-shadow input"
                dense
                items="succeeded,draft,on_hold,on_processing,failed,canceled"
                hide-details
                label="Nom"
                v-model="form.status"
              ></status
            ></v-col>
            <v-col cols="12" class="pa-2 pt-0">
              <v-textarea
                filled
                rounded
                class="no-shadow input"
                rows="2"
                dense
                hide-details
                label="Note"
                v-model="form.note"
              ></v-textarea>
            </v-col>
            <v-col cols="12" class="pa-2">
              <v-text-field
                filled
                rounded
                class="no-shadow input"
                dense
                hide-details
                label="Nom"
                v-model="form.full_name"
              ></v-text-field
            ></v-col>

            <v-col cols="12" sm="12" md="12" class="pa-2">
              <v-card-actions class="pa-0">
                <v-text-field
                  filled
                  rounded
                  class="no-shadow input"
                  dense
                  hide-details
                  name="phone"
                  type="number"
                  prepend-inner-icon="mdi-phone"
                  label="Téléphone"
                  v-model="form.phone"
                  :rules="[$method.rule_required]"
                ></v-text-field>
                <v-spacer v-if="form.phone"></v-spacer>
                <v-btn
                  v-if="form.phone"
                  color="white"
                  class="fb-shadow"
                  height="50"
                  :href="'tel:' + form.phone"
                  ><v-icon class="green--text">mdi-phone</v-icon></v-btn
                >
              </v-card-actions>
            </v-col>
            <v-col cols="12" class="pa-2">
              <v-text-field
                filled
                rounded
                class="no-shadow input"
                dense
                hide-details
                label="Email"
                append-icon="mdi-email"
                v-model="form.email"
                :rules="[$method.rule_required, $method.rule_email]"
              ></v-text-field>
            </v-col>

            <v-col cols="12" class="pa-2">
              <v-text-field
                filled
                rounded
                class="no-shadow input"
                dense
                hide-details
                label="Adresse"
                v-model="form.address"
              ></v-text-field>
            </v-col>

            <v-col cols="12" class="pa-2">
              <v-text-field
                filled
                rounded
                class="no-shadow input"
                dense
                hide-details
                label="Sujet"
                v-model="form.subject"
              ></v-text-field>
            </v-col>
          </v-row>
        </v-form>
        <v-card-actions class="pa-1">
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
    </v-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      dialog: false,
      form: {
        quantity: 1,
        status: 'on_processing',
      },
      filter: { max: 24 },
      valid: false,
      items: [],
      items_date: [],
      detail: {},
    }
  },
  watch: {
    items() {
      this.items_date = this.$method.groupBy(this.items, 'c_date')
    },
  },
  methods: {
    init(reset = true) {
      if (reset) this.filter.cp = 1

      this.$api('person/admin/contact/all', {
        params: { ...this.filter },
      }).then((res) => {
        this.detail = res.data.detail

        if (reset) {
          this.items = res.data.result
        } else {
          this.items = [...this.items, ...res.data.result]
        }
        this.items = this.items.map((item) => {
          item.c_date = this.$moment.unix(item.creation_date).format('LL')
          return item
        })
      })
    },
    async save(item) {
      if (item) {
        this.form = { ...item }
      }
      this.loading = true
      await this.$api
        .post('person/admin/contact', {
          ...this.form,
        })
        .then((res) => {
          if (res) {
            if (this.form.id) {
              this.items = this.items.map((item) => {
                if (item.id == this.form.id) {
                  item = { ...this.form }
                }
                return item
              })
              this.items = this.items.map((item) => {
                item.c_date = this.$moment.unix(item.creation_date).format('LL')
                return item
              })
            } else {
              this.init()
            }
            this.dialog = false
          }
        })
        .catch((e) => {})
      this.loading = false
    },
    remove() {
      var r = confirm('Êtes-vous sûr de vouloir supprimer cet élément!')
      if (r) {
        this.$api
          .delete('person/admin/contact', {
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