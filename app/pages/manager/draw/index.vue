<template>
  <div>
    <toolbar name="Les tirages" class="">
      <v-btn color="primary" class="shadow" :to="{ name: 'manager-draw-edit' }"
        >Ajouter
      </v-btn>
    </toolbar>

    <v-container>
      <div style="max-width: 600px">
        <div class="overflow-hidden ">
          <PFilter
            class="mb-0 rounded"
            v-model="filter"
            @input="init()"
          ></PFilter>
          <div class="pa-0 py-2 px-3 sub-title font-weight-bold">
            {{ $method.numberFormat(detail.total) }} tirages
          </div>
          <CardListVide class="mt-3" v-if="items.length == 0"> </CardListVide>
        </div>
        <div v-for="(data, k) in items_date" :key="k" class="pa-1 py-3">
          <v-card-actions class="pa-0 mb-4 my-2">
            <v-divider></v-divider>
            <div
              class="body-2 rounded-lg pa-1 px-2 mx-2 white font-weight-medium"
            >
              {{ data.key }}
            </div>
            <v-divider></v-divider>
          </v-card-actions>
          <v-card
            v-for="(item, key) in data.items"
            :key="item.id"
            class="mx-auto mb-6 overflow-hidden grey lighten-4  elevation-0"
          >
            <v-row no-gutters>
              <v-col class="pa-0 px-0" cols="12">
                <v-card class="elevation-0" tile>
                  <v-list-item class="pa-1">
                    <div
                      class="font-weight-medium text-center pa-1"
                      style="min-width: 50px"
                    >
                      <v-icon
                        x-large
                        class="black--text grey lighten-4 pa-1 rounded-circle"
                        @click="
                          $router.push({
                            name: 'play',
                            query: { id: item.id },
                          })
                        "
                        >mdi-play</v-icon
                      >
                      <br />
                    </div>

                    <v-list-item-content
                      class="px-2 py-2 cp"
                      v-ripple
                      @click="
                        $router.push({
                          name: 'manager-draw-edit',
                          query: { id: item.id },
                        })
                      "
                    >
                      <v-list-item-title class="title font-weight-bold">
                        ID {{ item.id }} {{ item.name }}

                        <span class="caption">{{ item.description }}</span>
                      </v-list-item-title>
                      <v-list-item-subtitle
                        class="body-2 grey--text"
                        v-if="item.event_date"
                        ><b>{{ item.time_down }}</b>
                      </v-list-item-subtitle>
                    </v-list-item-content>
                    <v-switch
                      hide-details
                      inset
                      @change="save(item)"
                      class="ma-0 pa-1"
                      v-model="item.is_show"
                    ></v-switch>
                  </v-list-item>
                </v-card>
                <v-row class="my-0" no-gutters>
                  <v-col class="pa-2">
                    <div>
                      <div class="title font-weight-bold">
                        {{ $method.numberFormat(item.winner) }}
                      </div>
                      <div class="caption">Nombre de gagnants</div>
                    </div>
                  </v-col>
                  <v-col class="pa-2">
                    <div>
                      <div class="title font-weight-bold">
                        {{ $method.moneyFormat(item.amount, '') }}/
                        {{ $method.moneyFormat(item.total_amount, '') }} TND
                      </div>
                      <div class="caption">Montant total</div>
                    </div>
                  </v-col>
                </v-row>
              </v-col>
            </v-row>
            <div class="pb-1 px-2 caption" v-if="item.shop_note">
              <b>Note :</b>
              {{ item.shop_note ? '“' + item.shop_note + '”' : '' }}
            </div>
            <div class="pb-1 px-2 caption" v-if="item.customer_note">
              <b>Client :</b>
              {{ item.customer_note ? '“' + item.customer_note + '”' : '' }}
            </div>
          </v-card>
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
  </div>
</template>

<script>
export default {
  data() {
    return {
      filter: { max: 24 },
      items: [],
      items_date: [],
      detail: {},
    }
  },
  computed: {},
  watch: {
    items() {
      this.items_date = this.$method.groupBy(this.items, 'c_date')
    },
  },
  methods: {
    async save(item) {
      this.loading = true
      await this.$api
        .post('person/admin/draw', {
          ...item,
        })
        .then((res) => {})
        .catch((e) => {})
    },
    init(reset = true) {
      if (reset) this.filter.cp = 1

      this.$api('person/admin/draw/all', {
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
          this.timeDown(item)
          return item
        })
      })
    },
    async timeDown(form) {
      var time = this.$method.convSec(
        form.event_date - this.$moment().format('x') / 1000
      )
      this.$set(
        form,
        'time_down',
        time.d + 'd ' + time.h + 'h ' + time.m + 'm ' + time.s
      )
      await this.$method.sleep(1000)
      this.timeDown(form)
    },
  },
  mounted() {
    this.init()
  },
}
</script>