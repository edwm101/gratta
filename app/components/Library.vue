<template>
  <div>
    <v-dialog
      v-model="dialog"
      scrollable
      :overlay="false"
      max-width="1100px"
      transition="dialog-transition"
    >
      <template v-slot:activator="{ on, attrs }">
        <div v-bind="attrs" v-on="on" @click="value = {}">
          <slot></slot>
        </div>
      </template>
      <v-card class="back">
        <v-card-actions class="pa-2 pb-0 mb-3 x">
          <v-btn @click="is_new_image = !is_new_image" class="white wave"
            >Importer <v-icon>mdi-plus</v-icon></v-btn
          >
          <v-text-field
            dense
            outlined
            hide-details
            class="mx-2 input"
            append-icon="mdi-magnify"
            v-model="filter.q"
            label="Rechercher le média"
            @input="init()"
          >
          </v-text-field>
          <v-switch
            hide-details
            class="ma-0 pa-0"
            label="Shop"
            @change="init()"
            v-model="filter.is_shop"
          ></v-switch>
          <v-icon class="close" @click="dialog = false">mdi-close</v-icon>
        </v-card-actions>
        <v-divider></v-divider>
        <div class="overflow-auto">
          <v-row no-gutters>
            <v-col cols="12" sm="5" md="4" class="pa-2" v-if="is_new_image">
              <v-card class="elevation-0">
                <UploadNew @input="setImage" />
              </v-card>
            </v-col>
            <v-col
              cols="12"
              :sm="is_new_image ? 7 : 12"
              :md="is_new_image ? 8 : 12"
              class="pa-2"
            >
              <card-list-vide v-if="items.length == 0">
                <v-btn
                  @click="is_new_image = !is_new_image"
                  class="white elevation-0"
                  >Importer <v-icon>mdi-plus</v-icon></v-btn
                ></card-list-vide
              >
              <div class="pa-2 pt-1" v-else>
                <v-progress-circular
                  size="50"
                  width="2"
                  class="pa-2 mr-2 shadow white rounded-circle"
                  color="primary"
                  :value="(value.length * 100) / max"
                  >{{ value.length }}/{{ max }}</v-progress-circular
                >
                Sélectionner l’image <b class="ml-2">(Max {{ max }})</b>
              </div>
              <v-row no-gutters>
                <v-col
                  cols="6"
                  :md="is_new_image ? 3 : 2"
                  class="pa-1"
                  v-for="item in items"
                  :key="item.id"
                >
                  <v-card
                    class="elevation-0 transparent"
                    :disabled="!item.is_selected && value.length >= max"
                    :style="
                      item.is_selected
                        ? 'border:3px solid blue!important'
                        : 'border:3px solid transparent!important'
                    "
                    @click="
                      $method.selectText('.item' + item.id)
                      $set(item, 'is_selected', !item.is_selected)
                    "
                  >
                    <wmedia
                      :is_pause="dialog"
                      :path="item.path"
                      height="100px"
                      :controls="true"
                    ></wmedia>
                    <div class="pa-1">
                      <v-card-actions class="caption pa-0">
                        <span
                          >{{ $method.numberFormat(item.size / 1000) }}Ko</span
                        >
                        <v-spacer></v-spacer>
                        <span v-if="item.width"
                          >{{ item.width }}x{{ item.height }}</span
                        >
                      </v-card-actions>
                      <v-list-item-title class="headline caption pa-0">{{
                        item.name
                      }}</v-list-item-title>

                      <v-card-actions class="pa-0">
                        <v-list-item-title
                          class="headline caption"
                          :class="'item' + item.id"
                          >{{ item.path }}</v-list-item-title
                        >

                        <v-spacer></v-spacer>
                        <v-icon @click="remove(item.id)">mdi-delete</v-icon>
                      </v-card-actions>
                    </div>
                  </v-card>
                </v-col>
              </v-row>
              <div class="text-center mt-3" v-if="filter.cp < detail.pages">
                <v-btn
                  color="white"
                  large
                  :loading="loading"
                  class="mx-auto shadow mb-2"
                  @click="
                    filter.cp_prod++
                    init(true)
                  "
                  >Afficher plus <v-icon>mdi-plus</v-icon></v-btn
                >
              </div>
            </v-col>
          </v-row>
        </div>
        <v-card-actions class="pa-2">
          <v-spacer></v-spacer>
          <v-btn
            color="success"
            class="shadow px-3"
            large
            :disabled="value.length < 1"
            @click="next()"
            :loading="loading"
            >Ajouter</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>


<script>
export default {
  props: {
    max: {
      default: 1,
    },
  },
  data() {
    return {
      if_first_time: true,
      is_new_image: false,
      dialog: false,
      filter: { cp: 1, is_shop: true },
      detail: {},
      image_url: null,
      loading: false,
      files: [],
      images: [],
      items: [],
      value: [],
    }
  },
  watch: {
    items: {
      handler: function () {
        this.value = this.items.filter((item) => item.is_selected)
      },
      deep: true,
    },
    dialog(val) {
      if (this.if_first_time) {
        this.init()
        this.if_first_time = false
      }
      if (!val) {
        this.items = this.items.map((item) => {
          this.$set(item, 'is_selected', false)
          return item
        })
      }
    },
  },
  methods: {
    next() {
      if (this.value.length > 0) {
        if (this.max == 1) {
          this.$emit('input', this.value[0])
        } else {
          this.$emit('input', this.value)
        }
      }
      this.dialog = false
    },

    async setImage($e) {
      if ($e.path) {
        this.loading = true
        await this.$api
          .post('user/library', { path: $e.path, name: $e.name })
          .then(async (res) => {
            this.init()
          })
          .catch((e) => {
            console.log(e)
          })
        this.loading = false
      }
    },
    init(inner = false) {
      this.$api('user/library/all', { params: { ...this.filter } }).then(
        (res) => {
          this.detail = res.data.detail
          if (inner) {
            this.items = [...res.data.result, ...this.items]
          } else {
            this.items = res.data.result
          }
        }
      )
    },
    async saveUrl() {
      if (this.image_url) {
        this.loading = true
        await this.$api
          .post('user/library', { path: this.image_url })
          .then(async (res) => {
            this.init()
            this.image_url = null
          })
          .catch((e) => {
            console.log(e)
          })
        this.loading = false
      }
    },
    async save() {
      this.loading = true

      this.images.forEach(async (element) => {
        await this.$api
          .post('user/library', {
            ...element,
          })
          .then(async (res) => {
            this.images = []
            this.init()
          })
        this.loading = false
      })
      this.loading = true
    },
    async remove(id) {
      await this.$api
        .delete('user/library', {
          params: { id },
        })
        .then(async (res) => {
          this.init()
        })
    },
  },
  mounted() {},
}
</script>