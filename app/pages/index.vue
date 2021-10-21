<template>
  <v-app class="is_rtl">
    <v-app-bar
      app
      hide-on-scroll
      :class="is_down ? 'head-shadow' : ' head-shadow'"
      style="transition: all 0.2s"
      :style="
        is_down
          ? ''
          : 'background-image: linear-gradient(rgb(255, 255, 255, 0.9), rgb(255,255,255 ,1));background-color: transparent;'
      "
    >
      <v-toolbar-title
        style="max-width: 250px"
        class="px-0 font-weight-bold headline black--text"
      >
        <nuxt-link :to="{ name: 'index' }">
          <h1 class="display-1 font-weight-bold black--text">
            ڤرڤش
          </h1></nuxt-link
        >
      </v-toolbar-title>

      <v-spacer></v-spacer>

      <div class="mx-1"></div>

      <template v-slot:extension>
        <v-tabs
          center-active
          show-arrows
          large
          align-with-title
          background-color="transparent"
        >
          <v-tab @click="$vuetify.goTo('#draw')" class="title font-weight-bold"
            >السحب</v-tab
          >
          <v-tab
            @click="$vuetify.goTo('#find_us')"
            class="title font-weight-bold"
            >نقاط البيع</v-tab
          >

          <v-tab
            @click="$vuetify.goTo('#contact')"
            class="title font-weight-bold"
            >اتصل بنا</v-tab
          >
        </v-tabs>
      </template>

      <!-- -->
    </v-app-bar>

    <div
      class="overflow-hidden grey darken-4 head-shadow"
      style="
        background-image: url(https://source.unsplash.com/1600x900/?game);
        background-size: cover;
      "
    >
      <div
        class="pb-12 overflow-hidden"
        style="
          min-height: 100vh;
          padding-top: 80px;
          position: relative;
          z-index: 1;
        "
      >
        <!-- <video
          autoplay=""
          loop=""
          muted=""
          preload="auto"
          style="width: 100%; position: absolute; top: 0"
        >
          <source src="tri.mp4" type="video/mp4" />
        </video> -->
        <div
          style="
            width: 100%;
            position: absolute;
            top: 0;
            background: url(http://www.torsos.com/wp-content/themes/blacklabel/images/grid-patterns/grid2.png);

            height: 100%;
            background-size: 250px;
            background-color: rgb(251, 251, 251, 0.9);
          "
        ></div>
        <v-container style="position: relative">
          <v-row no-gutters class="pt-12">
            <v-col cols="12" sm="6" md="6" class="pa-4 py-12 pt-4">
              <img
                :src="require('~/assets/image/logo.png')"
                alt=""
                style="width: 250px"
                class="mx-auto"
              />
              <v-divider class="my-5"></v-divider>
              <h2 class="headline font-weight-black neonText black--text">
                ڤرڤش العب و اربح بالوقت
              </h2>
              <h3 class="body-1 py-2">
                قرقش اقوى كارطة في تونس* بالوقت تنجم تربح اكثر من عشرة ملاين* و
                تنجم تدخل في القرعة و تربح برشا فلوس و كرهبة كيا بيكانتو* مع
                قرقش، بدينار تعمل احسن افار* إشري كارطة من اقرب نقطة بيع، قرقش،
                و شوف زهرك اش مخبيلك عندنا كارطة جوكار كيف تلقا ثلاثة سبعات تربح
                تربح عشرة ملاين كااااش اذا تلقى ثلاثة ناقوس تربح ميات مرة سوم
                الكارطة* و كي تلقى ثلاثة مربعات تربح سوم الكارطة عشرة مرات*  و
                إيذا لقيت ثلاثة سُريز تربح سوم الكارطة خمسة مرات *يعني الربح
                مضمون *و الكارطة نوفي, عملناها للمغرومين بالشكبة التونسية  *جيب
                النوووفي و اربح خمسة ملاين كااااش *و ما تنساش... الثمنية تربحك
                ميات مرة سوم الكارطة  *و السبعة و الستة زادا يربحوك *مع قرقش
                استثمر بدينار تعمل اقوى افااار
              </h3>
            </v-col>
            <v-col cols="12" sm="6" md="6" class="pa-1 py-12">
              <video
                autoplay=""
                loop=""
                controls
                preload="auto"
                style="width: 100%"
                class="rounded-lg head-sahdow"
              >
                <source src="land2.mp4" type="video/mp4" />
              </video>
            </v-col>
          </v-row>
        </v-container>
      </div>
    </div>

    <section
      id="draw"
      v-if="last_game.id"
      style="positon: relative; z-index: 1"
    >
      <Play :id="last_game.id"></Play>
    </section>
    <!-- <section class="mb-6 pt-12" id="draw">
      <v-container grid-list-xs>
        <h3 class="pa-5 display-1" v-if="draws.length > 0">
          <v-icon x-large class="success--text">mdi-sale</v-icon> السحب القادم
        </h3>
        <v-row>
          <v-col cols="12" sm="6" md="6" v-for="(draw, i) in draws" :key="i">
            <v-card
              style="height: 100%"
              class="
                elevation-
                head-shadow
                rounded-lg
                grey
                lighten-5
                overflow-hidden
              "
              :to="{ name: 'play', query: { id: draw.id } }"
            >
              <div class="pa-0">
                <v-card
                  class="pa-3 py-2 bg-success elevation-0"
                  tile
                  dark
                  style="min-width: 130px"
                >
                  <v-card-actions class="headline pa-0 pb-2 font-weight-bold">
                    {{ $method.moneyFormat(draw.total_amount) }}
                    <v-spacer></v-spacer>
                  </v-card-actions>
                  <div class="body-1">
                    عدد الفائزين:
                    <b>{{ $method.numberFormat(draw.nb_winner) }}</b>
                  </div>
                </v-card>
              </div>

              <div
                class="title black--text font-weight-medium pa-2"
                v-if="draw.time_down"
              >
                <v-row dense>
                  <v-col cols="3">
                    <v-card
                      class="
                        rounded-lg
                        text-center
                        pa-2
                        py-1
                        elevation-0
                        grey
                        lighten-3
                        font-weight-black
                      "
                    >
                      {{ draw.time_down.d }}
                      <div class="caption font-weight-black">أيام</div>
                    </v-card>
                  </v-col>
                  <v-col cols="3">
                    <v-card
                      class="
                        rounded-lg
                        text-center
                        pa-2
                        py-1
                        elevation-0
                        grey
                        lighten-3
                        font-weight-black
                      "
                    >
                      {{ draw.time_down.h }}
                      <div class="caption font-weight-black">ساعات</div>
                    </v-card>
                  </v-col>
                  <v-col cols="3">
                    <v-card
                      class="
                        rounded-lg
                        text-center
                        pa-2
                        py-1
                        elevation-0
                        grey
                        lighten-3
                        font-weight-black
                      "
                    >
                      {{ draw.time_down.m }}
                      <div class="caption font-weight-black">دقائق</div>
                    </v-card>
                  </v-col>
                  <v-col cols="3">
                    <v-card
                      class="
                        rounded-lg
                        text-center
                        pa-2
                        py-1
                        elevation-0
                        grey
                        lighten-3
                        font-weight-black
                      "
                    >
                      {{ draw.time_down.s }}
                      <div class="caption font-weight-black">ثواني</div>
                    </v-card>
                  </v-col>
                </v-row>
              </div>

              <v-divider></v-divider>

              <v-card-actions class="headline pa-2 font-weight-bold">
                <div class="line-height-1 pb-1">
                  السحب رقم : {{ draw.number }}
                </div>
                <v-spacer></v-spacer>
                <v-btn
                  color="primary"
                  class="elevation-0 bg-primary font-weight-bold"
                  >أدخل</v-btn
                >
              </v-card-actions>
              <v-card-actions class="caption pa-2 font-weight-bold">
                <div class="line-height-1 pb-1">
                  التاريخ :
                  {{ $moment.unix(draw.event_date).format('hh:mm - DD/MM/Y') }}
                </div>
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </section> -->
    <!-- <section class="mb-6" id="draw" v-if="draws_old.length > 0">
      <v-container grid-list-xs>
        <h3 class="pa-5 display-1">
          <v-icon x-large class="success--text">mdi-sale</v-icon> النتائج
        </h3>
        <v-row>
          <v-col
            cols="12"
            sm="6"
            md="4"
            v-for="(draw, i) in draws_old"
            :key="i"
          >
            <v-card
              style="height: 100%"
              class="
                elevation-
                head-shadow
                rounded-lg
                grey
                lighten-5
                overflow-hidden
              "
              :to="{ name: 'play', query: { id: draw.id } }"
            >
              <div class="pa-0">
                <v-card
                  class="pa-3 py-2 bg-primary elevation-0"
                  tile
                  dark
                  style="min-width: 130px"
                >
                  <v-card-actions class="headline pa-0 pb-2 font-weight-bold">
                    {{ $method.moneyFormat(draw.total_amount) }}
                    <v-spacer></v-spacer>
                  </v-card-actions>
                  <div class="body-1">
                    عدد الفائزين:
                    <b>{{ $method.numberFormat(draw.nb_winner) }}</b>
                  </div>
                </v-card>
              </div>

              <v-card-actions class="headline pa-2 font-weight-bold">
                <div class="line-height-1 pb-1">
                  السحب رقم : {{ draw.number }}
                </div>
                <v-spacer></v-spacer>
              </v-card-actions>
              <v-card-actions class="caption pa-2 font-weight-bold">
                <div class="line-height-1 pb-1">
                  التاريخ :
                  {{ $moment.unix(draw.event_date).format('hh:mm - DD/MM/Y') }}
                </div>
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </section> -->

    <section id="contact" class="py-6 grey lighten-4">
      <v-container grid-list-xs id="find_us">
        <v-row>
          <v-col cols="12" sm="6" md="8"
            ><h3 class="pa-5 display-1">
              <v-icon x-large class="success--text">mdi-map</v-icon> نقاط البيع
            </h3>
            <v-divider class="mb-3" v-if="points.length > 0"></v-divider>
            <div
              id="map"
              tile
              style="z-index: 2; height: 355px"
              class="rounded-lg"
            ></div
          ></v-col>
          <v-col cols="12" sm="6" md="4">
            <h3 class="pa-5 display-1">
              <v-icon x-large class="success--text">mdi-mail</v-icon> اتصل بنا
            </h3>
            <v-divider class="mb-3"></v-divider>
            <v-card
              class="pa-12 font-weight-medium text-center title"
              light
              v-if="is_send"
            >
              شكرا لك تم استلام رسالتك 😊
            </v-card>
            <v-card class="elevation-0 transparent" light v-else>
              <v-form v-model="valid">
                <v-row dense class="pa-2 pb-0">
                  <v-col>
                    <v-row dense class="pa-2 py-0 pt-3 is_ltr">
                      <v-col cols="12">
                        <v-text-field
                          outlined
                          dense
                          type="number"
                          name="phone"
                          label="الهاتف"
                          append-icon="mdi-phone"
                          v-model="form.phone"
                          :rules="[$method.rule_required, $method.rule_min]"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12">
                        <v-text-field
                          outlined
                          dense
                          name="full_name"
                          label="الاسم و اللقب"
                          v-model="form.full_name"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" class="is_ltr">
                        <v-textarea
                          outlined
                          rows="5"
                          dense
                          append-icon="mdi-envelope"
                          name="comment"
                          label="الرسالة"
                          v-model="form.message"
                        ></v-textarea>
                        <v-card-actions class="pa-0">
                          <v-btn
                            color="primary"
                            large
                            block
                            :disabled="!valid"
                            class="elevation-0"
                            @click="sendMessage()"
                            >ارسال <v-icon>mdi-send</v-icon></v-btn
                          >
                        </v-card-actions>
                      </v-col>
                    </v-row></v-col
                  >
                </v-row>
              </v-form>
            </v-card></v-col
          >
        </v-row>
      </v-container>
    </section>
  </v-app>
</template>

<script>
import Play from './play'
export default {
  layout: 'empty',
  components: {
    Play,
  },
  data: () => ({
    is_down: false,
    status: '',
    lightbox: 0,
    draws: [],
    form: {},
    loading: false,
    is_send: false,
    map: null,
    markers: [],
    points: [],
    valid: false,
    draws_old: [],
    offsetTop: 0,
    last_game: {},
  }),
  head() {
    return {
      title: 'ڨرڨش',
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: 'ڨرڨش.',
        },
        {
          hid: 'og:title',
          property: 'og:title',
          content: 'ڨرڨش',
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
    async sendMessage() {
      this.loading = true
      await this.$api
        .post('public/contact', {
          ...this.form,
        })
        .then((res) => {
          this.is_send = true
        })
      this.loading = false
    },
    async timeDown(draw) {
      var time = this.$method.convSec(
        draw.event_date - this.$moment().format('x') / 1000
      )
      this.$set(draw, 'time_down', time)
      await this.$method.sleep(1000)
      this.timeDown(draw)
    },
  },
  mounted() {
    this.$api('public/draw/last-game', {
      params: { is_end: true },
    }).then((res) => {
      this.last_game = res.data.result
    })

    this.draws = this.$api('public/draw/all', {
      params: { is_end: true },
    }).then((res) => {
      this.draws = res.data.result

      this.draws.map((e) => {
        if (e.event_date) {
          this.timeDown(e)
        }
      })
    })
    window.addEventListener('scroll', (e) => {
      this.offsetTop = window.pageYOffset

      if (this.offsetTop < 200) {
        this.is_down = false
      } else {
        this.is_down = true
      }
    })

    this.draws = this.$api('public/draw/all', {
      params: {},
    }).then((res) => {
      this.draws_old = res.data.result
    })

    var grayscale = L.tileLayer(
        'http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',
        {
          foo: 'bar',
          attribution: '',
          maxZoom: 22,
          subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        }
      ),
      streets = L.tileLayer(
        'http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}',
        {
          foo: 'bar',
          attribution: '',
          maxZoom: 22,
          subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        }
      ),
      openstreetmap = L.tileLayer(
        'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?{foo}',
        {
          foo: 'bar',
          attribution: '',
          maxZoom: 22,
        }
      ),
      osm = L.tileLayer(
        'https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png',
        {
          foo: 'bar',
          attribution: '',
          maxZoom: 22,
        }
      )

    var c_layor = streets
    if (this.$method.isWebView()) {
      c_layor = osm
    }
    if (this.$cookies.get('layor_index')) {
      switch (this.$cookies.get('layor_index')) {
        case 1:
          c_layor = streets
          break
        case 2:
          c_layor = grayscale
          break
        case 3:
          c_layor = openstreetmap
          break
        case 4:
          c_layor = osm
          break
        default:
          break
      }
    }

    this.$api('public/location/all').then((res) => {
      this.points = res.data.result

      setTimeout(() => {
        if (this.points.length > 0) {
          this.map = L.map('map', {
            layers: [c_layor],
            zoomControl: false,
          }).setView([0, 0], 6)

          var baseLayers = {
            Default: grayscale,
            Satellite: streets,
            Openmap: openstreetmap,
            Osm: osm,
          }

          this.map.on('baselayerchange', (e) => {
            this.$cookies.set('layor_index', e.layer.options.zIndex, {
              path: '/',
              maxAge: 364 * 60 * 24 * 60 * 1000,
            })
          })

          L.control.layers(baseLayers).addTo(this.map)
          L.control.zoom({ position: 'topright' }).addTo(this.map)

          this.points.map((point) => {
            this.markers.push(
              L.marker([point.lat, point.lng], {
                icon: L.divIcon({
                  className: 'marker-pin',
                  html:
                    "<img src='" + require('~/assets/image/logo.png') + "'/>",
                }),
              })
                .on('click', (e) => {
                  this.setFocus(key)
                })
                .addTo(this.map)
            )

            this.map.setView([point.lat, point.lng])
          })
        }
      }, 100)
    })
  },
}
</script>


