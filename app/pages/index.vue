<template>
  <div>
    <section
      id="home"
      style="
        background-position: right bottom;
        background-repeat-x: no-repeat;
        background-size: cover;
        background-color: #2c0f70;
      "
      :style="{
        'background-image':
          'url('+require('@/assets/image/gratta-e-vinci.png')+')!important',
      }"
    >
      <v-card
        tile
        dark
        min-height="350"
        class="align-end elevation-0 pt-0 pb-12"
        style="min-height: calc(100vh); background: rgb(2, 9, 16, 0.6)"
      >
        <v-container class="pb-12">
          <v-row no-gutters class="pb-12 pt-12">
            <v-col cols="12" sm="6" md="6" class="pa-12 ">
              <img
               :src="require('~/assets/image/logo.png')"
                alt=""
                height="100px"
                class="mr-1 mt-3"
              />
              <h1 class="display-1 pa-0 py-4 font-weight-bold">
                Grattez vinci
              </h1>
              <h3 class="body-1 py-2">
                Un nouveau jeu Grattez et Gagnez a été lancé. Êtes-vous prêt ?
              </h3>

              <v-divider class="my-5" v-if="draws.length > 0"></v-divider>
            </v-col>

            <v-col cols="12" md="6">
              <h3 class="pa-5 " v-if="draws.length > 0">
                <v-icon x-large class="success--text">mdi-sale</v-icon> Les
                tirages
              </h3>
              <v-carousel
                v-if="draws.length > 0"
                v-model="carousel"
                hide-delimiter-background
                show-arrows-on-hover
                hide-delimiters
                :show-arrows="draws.length > 1"
                class="responsive-carousel my-3 rounded-lg"
              >
                <v-carousel-item
                  v-for="(draw, i) in draws"
                  :key="i"
                  :to="{ name: 'play-id', params: { id: draw.id } }"
                >
                  <v-img
                    class="align-end"
                    gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                  >
                    <v-card
                      light
                      class="elevation-0 grey lighten-4"
                    >
                      <div class="display-1 pa-2 font-weight-bold">
                        {{ draw.name }}
                      </div>

                      <div class="body-2 pa-2 grey--text">
                        {{ draw.description }}
                      </div>
                      <v-divider></v-divider>

                      <v-row class="my-0">
                        <v-col>
                          <div class="px-3" style="min-width:130px">
                            <div class="headline font-weight-bold">
                              {{ $method.numberFormat(draw.winner) }}
                            </div>
                            <div class="caption">Nombre de gagnants</div>
                          </div>
                        </v-col>
                        <v-col>
                          <div class="px-3" style="min-width:130px">
                            <div class="headline font-weight-bold">
                              {{ $method.moneyFormat(draw.amount) }}
                            </div>
                            <div class="caption">Montant total</div>
                          </div>
                        </v-col>
                        <v-col v-if="draw.winner">
                          <div class="px-3" style="min-width:130px">
                            <div class="headline font-weight-bold">
                              {{
                                $method.moneyFormat(draw.amount / draw.winner)
                              }}
                            </div>
                            <div class="caption">Montant moyen</div>
                          </div>
                        </v-col>
                      </v-row>
                      <v-card-actions class="pa-2" style="width: 100%">
                        <span
                          class="headline pa-2 mt-2 font-weight-bold rounded-lg"
                          v-if="draw.time_down"
                        >
                          {{ draw.time_down }}
                        </span>
                        <v-spacer></v-spacer>
                        <v-btn color="success" class="shadow" x-large
                          >Entre</v-btn
                        >
                      </v-card-actions>
                    </v-card>
                  </v-img>
                </v-carousel-item>
              </v-carousel>
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </section>
  </div>
</template>

<script>
export default {
  layout: 'empty',
  data: () => ({
    status: '',
    lightbox: 0,
    draws: [],
  }),
  head() {
    return {
      title: 'Gratta',
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: 'Gratta.',
        },
        {
          hid: 'og:title',
          property: 'og:title',
          content: 'Gratta',
        },
        {
          hid: 'og:description',
          property: 'og:description',
          content: 'Gratta.',
        },
        {
          hid: 'og:image',
          property: 'og:image',
          content: require('@/assets/image/icon.png'),
        },
      ],
    }
  },
  methods: {
    async timeDown(offer) {
      var time = this.$method.convSec(
        offer.event_date - this.$moment().format('x') / 1000
      )
      this.$set(
        offer,
        'time_down',
        time.d + 'd ' + time.h + 'h ' + time.m + 'm ' + time.s
      )
      await this.$method.sleep(1000)
      this.timeDown(offer)
    },
  },
  mounted() {
    this.draws = this.$api('public/draw/all').then((res) => {
      this.draws = res.data.result

      this.draws.map((e) => {
        if (e.event_date) {
          this.timeDown(e)
        }
      })
    })
  },
}
</script>


