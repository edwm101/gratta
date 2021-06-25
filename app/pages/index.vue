<template>
  <div>
    <section
      id="home"
      style="
        background-position: right 50%;
        background-repeat-x: no-repeat;
        background-color: #000;
      "
      :style="{
        'background-image':
          'url(https://wallpapercave.com/wp/Hn2vHmJ.jpg)!important',
      }"
    >
      <v-card
        tile
        dark
        min-height="250"
        class="align-end elevation-0 pb-12"
        style="background: rgb(2, 9, 16, 0.6)"
      >
        <div
          dark
          class="elevation-0 py-2"
          tile
          :class="offsetTop > 100 ? 'black' : 'transparent'"
          style="
            transition: 0.5s all;
            background: #000;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 5;
          "
          :clipped-left="$vuetify.breakpoint.lgAndUp"
          app
        >
          <v-container grid-list-xs class="py-0">
            <v-tabs
              center-active
              show-arrows
              align-with-title
              background-color="transparent"
            >
              <v-tab
                @click="$vuetify.goTo('#draw')"
                class="title font-weight-bold"
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
          </v-container>
        </div>

        <v-container class="py-12">
          <v-row no-gutters class="pt-12">
            <v-col cols="12" sm="6" md="5" class="pa-8 py-12">
              <div class="text-center">
                <img
                  :src="require('~/assets/image/logo.png')"
                  alt=""
                  style="width: 180px"
                  class="mx-auto"
                />
              </div>
              <v-divider class="my-5"></v-divider>

              <h3 class="body-1 py-2">
                تحب تحقق حلمك العب و اربح اكثر من 15 مليون... ساهل برشااا تشري
                كارطة بدينار 🎫 تخربش تربح بالوقت تاخذ فلوسك من نقطة البيع اللي
                شريت منها و تزيد تدخل في القرعة اللي تصير كل يوم أحد تربحك حتى
                500 دينار و قرعة كل آخر شهر اللي تنجم تربحك حتى 15 مليون كااااش
                💵
              </h3>
            </v-col>
            <v-col cols="12" sm="6" md="7" class="pa-2 py-8">
              <video
                width="100%"
                controls
                style="width: 100%"
                class="rounded-lg"
              >
                <source src="/land.mp4" />
              </video>
            </v-col>
          </v-row>
        </v-container>
      </v-card>
      <v-card class="mb-12" style="">
        <div class="coin is_ltr">
          <div class="front jump">
            <div class="star"></div>
            <span class="currency"> و </span>
            <div class="shapes">
              <div class="shape_l"></div>
              <div class="shape_r"></div>
              <span class="top"> العب </span>
              <span class="bottom"> اربح </span>
            </div>
          </div>
          <div class="shadow"></div>
        </div>
      </v-card>
    </section>
    <section class="mb-6 pt-12" id="draw">
      <v-container grid-list-xs>
        <h3 class="pa-5 display-1" v-if="draws.length > 0">
          <v-icon x-large class="success--text">mdi-sale</v-icon> السحب القادم
        </h3>
        <v-row>
          <v-col cols="12" sm="6" md="6" v-for="(draw, i) in draws" :key="i">
            <v-card
              style="height: 100%"
              class="elevation-0 grey lighten-5 overflow-hidden"
              :to="{ name: 'play-id', params: { id: draw.id } }"
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
                      class="rounded-lg text-center pa-2 py-1 elevation-0 grey lighten-3 font-weight-black"
                    >
                      {{ draw.time_down.d }}
                      <div class="caption font-weight-black">أيام</div>
                    </v-card>
                  </v-col>
                  <v-col cols="3">
                    <v-card
                      class="rounded-lg text-center pa-2 py-1 elevation-0 grey lighten-3 font-weight-black"
                    >
                      {{ draw.time_down.h }}
                      <div class="caption font-weight-black">ساعات</div>
                    </v-card>
                  </v-col>
                  <v-col cols="3">
                    <v-card
                      class="rounded-lg text-center pa-2 py-1 elevation-0 grey lighten-3 font-weight-black"
                    >
                      {{ draw.time_down.m }}
                      <div class="caption font-weight-black">دقائق</div>
                    </v-card>
                  </v-col>
                  <v-col cols="3">
                    <v-card
                      class="rounded-lg text-center pa-2 py-1 elevation-0 grey lighten-3 font-weight-black"
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
    </section>
    <section class="mb-6" id="draw">
      <v-container grid-list-xs>
        <h3 class="pa-5 display-1" v-if="draws.length > 0">
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
              class="elevation-0 grey lighten-5 overflow-hidden"
              :to="{ name: 'play-id', params: { id: draw.id } }"
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
    </section>
    <section id="find_us" class="py-6">
      <v-container grid-list-xs>
        <h3 class="pa-5 display-1" v-if="draws.length > 0">
          <v-icon x-large class="success--text">mdi-map</v-icon> نقاط البيع
        </h3>
        <v-divider class="mb-3"></v-divider>
        <div
          id="map"
          tile
          style="z-index: 2; height: 355px"
          class="rounded-lg"
        ></div>
      </v-container>
    </section>
    <section id="contact" class="py-6">
      <v-container grid-list-xs>
        <h3 class="pa-5 display-1" v-if="draws.length > 0">
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
        <v-card class="elevation-0 white" style="max-width: 700px" light v-else>
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
                  </v-col> </v-row
              ></v-col>
              <v-col>
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
                  <v-card-actions class="pa-2">
                    <v-btn
                      color="primary"
                      large
                      :disabled="!valid"
                      class="elevation-0"
                      @click="sendMessage()"
                      >ارسال <v-icon>mdi-send</v-icon></v-btn
                    >
                  </v-card-actions>
                </v-col></v-col
              >
            </v-row>
          </v-form>
        </v-card></v-container
      >
    </section>
  </div>
</template>

<script>
export default {
  data: () => ({
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

    var c_layor = grayscale
    if (this.$method.isWebView()) {
      c_layor = osm
    }
    if (this.$cookies.get('layor_index')) {
      switch (this.$cookies.get('layor_index')) {
        case 1:
          c_layor = grayscale
          break
        case 2:
          c_layor = streets
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

    this.$api('public/location/all').then((res) => {
      this.points = res.data.result
      this.points.map((point) => {
        this.markers.push(
          L.marker([point.lat, point.lng], {
            icon: L.divIcon({
              className: 'marker-pin',
              html: "<img src='" + require('~/assets/image/logo.png') + "'/>",
            }),
          })
            .on('click', (e) => {
              this.setFocus(key)
            })
            .addTo(this.map)
        )

        this.map.setView([point.lat, point.lng])
      })
    })
  },
}
</script>


