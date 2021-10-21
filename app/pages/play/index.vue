<template>
  <div class="is_rtl">
    <section
      class="back-home"
      style="
        background-position: right 50%;
        background-repeat-x: no-repeat;
        min-height: 100vh;
      "
    >
      <v-card
        class="elevation-0 shadow mx-auto py-9 px-4 transparent"
        tile
        style="min-height: 100vh; background: rgb(255, 255, 255, 0.95)"
      >
        <v-card
          class="py-12 pt-0 transparent elevation-0"
          style="margin-top: -50px; margin-bottom: 100px"
        >
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
        <!-- <v-card
          width="150px"
          class="
            line-height-0
            mx-auto
            shadow
            rounded-xl
            transparent
            elevation-0
          "
          :to="{ name: 'index' }"
        >
          <img :src="require('~/assets/image/logo.png')" alt="" />
        </v-card> -->
        <h1 class="display-1 pt-8 text-center pb-5 font-weight-black">
          السحب رقم {{ form.number }}
        </h1>
        <div>
          <div
            class="text-center py-5"
            v-if="winners.length < 1 && $store.state.user.info.role != 'admin'"
          >
            <v-btn
              @click="
                dialog_code = true
                form_code = {}
              "
              color="error"
              class="
                mb-3 
                mx-auto
                px-12
                wave
                font-weight-black
                title
              "
              x-large
              >سجل في السحب  <v-icon>mdi-plus</v-icon></v-btn
            >
          </div>
        </div>
        <!-- <div
          class="title text-center"
          v-if="
            form.is_start ||
            (form.start_date &&
              count_date &&
              form.current_winner < resume.winner)
          "
        ></div> -->

        <!-- <div
          class="title text-center"
          v-else-if="
            resume.winner == form.current_winner &&
            form.current_winner == form.nb_winner
          "
        >
          انتهى هذا الحدث
        </div> -->
        <!-- <div class="title text-center" v-else-if="time_down && form.event_date">
          <div
            class="title font-weight-medium pa-2 mx-auto"
            style="max-width: 300px"
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
                    black--text
                    font-weight-black
                  "
                >
                  {{ time_down.d }}
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
                    black--text
                    font-weight-black
                  "
                >
                  {{ time_down.h }}
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
                    black--text
                    font-weight-black
                  "
                >
                  {{ time_down.m }}
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
                    black--text
                    font-weight-black
                  "
                >
                  {{ time_down.s }}
                  <div class="caption font-weight-black">ثواني</div>
                </v-card>
              </v-col>
            </v-row>
          </div>
        </div>
        <div class="line-height-1 pb-1 text-center pt-5">
          التاريخ :
          {{ $moment.unix(form.event_date).format('HH:mm - DD/MM/Y') }}
        </div>
        <div class="py-4">
          <v-container grid-list-xs style="max-width: 700px">
            <v-row>
              <v-col cols="12" md="12">
                <v-card class="elevation-0 shadow transparent">
                  <div
                    class="pa-0 mb-4"
                    v-if="
                      !form.is_start &&
                      form.current_winner < resume.winner &&
                      $store.state.user.info.role == 'admin'
                    "
                  >
                    <v-btn
                      class="
                        elevation-0
                        black--text
                        grey
                        lighten-4
                        rounded-lg
                        border
                        shadow
                        title
                      "
                      x-large
                      block
                      @click="start()"
                      ><v-icon large class="mr-4">mdi-play</v-icon> Play</v-btn
                    >
                  </div>

                  <v-card
                    class="elevation-0 pa-2 mt-2 px-3 rounded-lg transparent"
                  >
                    <div class="headline font-weight-black pa-0">
                      {{ form.name }}
                    </div>
                    <div class="title pa-0">{{ form.description }}</div>
                    <iframe
                      v-if="form.live_link"
                      :src="
                        'https://www.facebook.com/plugins/video.php?height=350&href=' +
                        form.live_link
                      "
                      style="width: 100%; height: 350px"
                      class="mt-6 elevation-0 line-height-0"
                      frameborder="0"
                    ></iframe>
                  </v-card>
                  <v-btn
                    v-if="
                      winners.length > 0 &&
                      $store.state.user.info.role == 'admin'
                    "
                    color="error"
                    class="white--text mt-4"
                    small
                    text
                    @click="cancel()"
                    ><v-icon class="mr-1">mdi-reload</v-icon> Annuler</v-btn
                  >
                </v-card>
              </v-col>
              <v-col cols="12" md="12">
             <v-row class="text-center">
                  <v-col cols="12" sm="6">
                    <v-card class="bg-success head-shadow rounded-xl pa-4" dark>
                      <div class="display-1 font-weight-black">
                        {{ $method.moneyFormat(form.total_amount) }}
                      </div>
                      <div class="caption">المبلغ الإجمالي</div>
                    </v-card>
                  </v-col>
                  <v-col cols="12" sm="6">
                    <v-card class="bg-primary head-shadow rounded-xl pa-4" dark>
                      <div class="display-1 font-weight-black">
                        {{ $method.numberFormat(form.nb_winner) }}
                      </div>
                      <div class="caption">عدد الفائزين</div>
                    </v-card>
                  </v-col>
                </v-row> -->

                <!-- <v-simple-table fixed-header v-if="1 == 0"></v-simple-table>
                <div
                  class="
                    v-data-table v-data-table--fixed-header
                    theme--light
                    mt-2
                    rounded-lg head-shadow
                    overflow-hidden
                  "
                >
                  <div class="v-data-table__wrapper">
                    <div class="title font-weight-bold pa-2 px-4">الفائزون</div>

                    <v-divider></v-divider>
                    <table v-if="winners.length > 0">
                      <thead class="">
                        <tr>
                          <th class="text-right">الرمز</th>
                          <th class="text-right">المبلغ</th>
                          <th class="text-right">الرابح</th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr v-for="item in winners" :key="item.id" class="cp">
                          <td>
                            <b> {{ item.code }}</b>
                          </td>
                          <td>
                            <b class="success--text title font-weight-black">
                              {{ $method.moneyFormat(item.amount) }}</b
                            >
                          </td>
                          <td>
                            {{ item.full_name }}
                            <div class="caption grey--text">
                              {{ item.governorate }}
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <card-list-vide
                      title="القائمة فارغة"
                      v-if="winners.length < 1"
                      class="mx-auto"
                    ></card-list-vide>
                  </div>
                </div>
              </v-col>
            </v-row>
          </v-container>
        </div>  -->
      </v-card>
    </section>

    <v-dialog
      v-model="dialog_select"
      scrollable
      persistent
      :overlay="false"
      max-width="350px"
      transition="dialog-transition"
    >
      <v-card v-if="form.is_start || !is_select">
        <v-list
          class="overflow-auto pa-2 font-weight-bold"
          style="height: 250px"
        >
          <template v-for="(item, key) in random_list">
            <div
              v-if="key == 2"
              :key="key"
              style="box-shadow: 0 0 0px 110px #9d9d9d; border: 4px solid"
              class="rounded-lg title"
            >
              <v-card-actions
                class="rounded-lg pa-3"
                style="box-shadow: inset 0 0 8px #c7c7c7"
              >
                <v-icon style="transform: rotate(90deg)">mdi-navigation</v-icon>
                {{ item }}
              </v-card-actions>
            </div>
            <v-card-actions :key="key" v-else>
              {{ item }}
            </v-card-actions>
          </template>
        </v-list>

        <div class="is_rtl">
          <v-card-actions class="pa-2 font-weight-bold">
            ({{ form.current_winner }}/{{ resume.winner }})
            <v-spacer></v-spacer>
            <v-icon
              @click="
                !is_play_audio ? audio.play() : audio.pause()
                is_play_audio = !is_play_audio
              "
              >{{
                is_play_audio ? 'mdi-volume-high' : 'mdi-volume-off'
              }}</v-icon
            >
          </v-card-actions>
          <v-card-actions v-if="$store.state.user.info.role == 'admin'">
            <v-btn color="success" class="shadow" block @click="stop()"
              ><v-icon>mdi-stop</v-icon> Arrêter</v-btn
            >
          </v-card-actions>
        </div>
      </v-card>
      <v-card v-else>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-icon class="close" @click="dialog_select = false"
            >mdi-close</v-icon
          >
        </v-card-actions>
        <div class="is_rtl">
          <v-list-item-content class="pa-3 py-1">
            <v-list-item-subtitle> الكود الفائز</v-list-item-subtitle>
            <v-list-item-title class="display-1 font-weight-black">
              {{ current_winner.code }}
            </v-list-item-title>
            <v-list-item-title class="title font-weight-black">
              {{ current_winner.full_name }}
            </v-list-item-title>
            <v-list-item-title class="title font-weight-black">
              {{ current_winner.governorate }}
            </v-list-item-title>
          </v-list-item-content>
          <v-list-item-content class="pa-3">
            <v-list-item-subtitle class="pa-1"> المبلغ </v-list-item-subtitle>
            <v-list-item-title
              class="display-3 py-2 success--text font-weight-bold"
            >
              {{ $method.moneyFormat(current_winner.amount) }}
            </v-list-item-title>
          </v-list-item-content>

          <v-card-actions
            v-if="!form.is_start && form.current_winner < resume.winner"
          >
            <v-btn
              class="border elevation-0"
              block
              @click="start()"
              v-if="$store.state.user.info.role == 'admin'"
              ><v-icon>mdi-play</v-icon> Suivant</v-btn
            >
          </v-card-actions>
          <v-card-actions class="title" v-else>
            انتهى هذا الحدث ({{ form.current_winner }}/{{ resume.winner }})
            <v-spacer></v-spacer>
          </v-card-actions>
          <v-card-actions>
            <v-btn
              class="border elevation-0"
              block
              @click="dialog_select = false"
            >
              اغلاق</v-btn
            >
          </v-card-actions>
        </div>
      </v-card>
    </v-dialog>

    <v-dialog
      v-model="dialog_code"
      scrollable
      max-width="350px"
      transition="dialog-transition"
    >
      <v-card class="gre white lighten-4 is_rtl">
        <v-card-actions class="pa-2"
          ><div class="title font-weight-bold">انشا لله مربوحة ‎</div>
          <v-spacer></v-spacer>
          <v-icon class="close" @click="dialog_code = false">mdi-close</v-icon>
        </v-card-actions>
        <div>
          <v-form v-model="valid_code" class="is_ltr">
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
                label="الكود"
                v-model="form_code.code"
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
                label="الاسم كامل"
                v-model="form_code.full_name"
                :rules="[$method.rule_required]"
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
                label="رقم الهاتف"
                v-model="form_code.phone"
                :rules="[$method.rule_required]"
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
                label="الولاية"
                v-model="form_code.governorate"
                :rules="[$method.rule_required]"
              ></v-select>
            </div>
          </v-form>
          <div class="caption pa-2 is_rtl">
            *الرجاء الاحتفاض بالكارتا والاستظهار بيها في حالة الربح‎
          </div>
        </div>
        <v-card-actions class="pa-2">
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            :disabled="!valid_code"
            @click="saveCode(form_code)"
            class="shadow"
            >ارسال</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: {
    id: {
      default: null,
    },
  },
  layout: 'empty',
  data() {
    return {
      dialog_code: false,
      valid_code: false,
      form_code: {},
      dialog_select: false,
      form: {},
      resume: {},
      winners: [],
      count_date: null,
      random_list: [],
      current_winner: {},
      is_first_time: true,
      time_down: null,
      audio: null,
      is_audio: null,
      is_play_audio: true,
      governorates: [],
      is_select: false,
    }
  },
  head() {
    return {
      title: 'ڨرڨش',
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: 'السحب رقم ' + this.form.number + ' - ڨرڨش',
        },
        {
          hid: 'og:title',
          property: 'og:title',
          content: 'السحب رقم ' + this.form.number + ' - ڨرڨش',
        },
        {
          hid: 'og:description',
          property: 'og:description',
          content: 'السحب رقم ' + this.form.number + ' - ڨرڨش',
        },
        {
          hid: 'og:image',
          property: 'og:image',
          content: require('@/assets/image/icon.png'),
        },
      ],
    }
  },
  watch: {
    'form.is_start'(val) {
      if (val && this.is_play_audio) {
        this.audio.play()
        this.is_audio = setInterval(() => {
          this.audio.currentTime = 0
        }, 14000)
      } else {
        this.audio.pause()
        this.audio.currentTime = 0

        clearInterval(this.is_audio)
      }
    },
  },
  methods: {
    async saveCode() {
      this.loading = true
      await this.$api
        .post('public/draw/code', {
          ...this.form_code,
          draw_id: this.form.id,
        })
        .then((res) => {})
        .catch((e) => {})
      this.$toast.success('تم التسجيل بنجاح ✔️')

      this.dialog_code = false
      this.form_code = {}
      this.loading = false
    },
    cancel() {
      this.form.is_start = false
      this.form.current_winner = null
      this.form.start_date = null
      this.save()
      this.is_first_time = true
      this.winners = []
    },
    start() {
      this.form.is_start = true
      this.is_select = false
      if (!this.form.current_winner) {
        this.form.start_date = this.$moment().format('X')
        this.form.current_winner = 0
      }

      this.dialog_select = true
      this.form.current_winner++
      this.save()
      this.startRandom()
    },
    async stop() {
      this.find(false)
    },
    find(is_start = true) {
      this.$api('public/draw/winner', { params: { id: this.form.id } }).then(
        async (res) => {
          this.winners = res.data.result

          if (!is_start) {
            this.form.is_start = false

            this.save()
          }

          if (this.form.is_start) {
            this.winners.pop()
          }
          this.random_list = []

          if (this.winners.length > 0) {
            this.random_list.push(this.randomIntFromInterval(1000, 1000000))
            this.random_list.push(this.randomIntFromInterval(1000, 1000000))

            this.random_list.push(this.winners[this.winners.length - 1].code)

            this.current_winner = this.winners[this.winners.length - 1]

            this.random_list.push(this.randomIntFromInterval(1000, 1000000))

            this.random_list.push(this.randomIntFromInterval(1000, 1000000))
          }
          if (!this.form.is_start) {
            await this.$method.sleep(3000)
            this.is_select = true
          } else {
            this.is_select = false
          }
        }
      )
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
    setResume() {
      this.$api('public/draw/resume', {
        params: { id: this.form.id },
      }).then((res) => {
        this.resume = res.data.result
      })
    },
    init() {
      var id = this.id ? this.id : this.$route.query.id
      this.$api('public/draw', {
        params: { id },
      }).then(async (res) => {
        var old_start = this.form.is_start
        this.form = res.data.result
        if (this.form.id) {
          if (this.is_first_time) {
            this.timeDown(this.form)
            this.setResume()
            this.is_first_time = false
          }
          this.find()
          this.random_list = []
          await this.$method.sleep(2000)
          if (this.form.is_start && !old_start) {
            this.startRandom()
            this.dialog_select = true
            this.current_winner = {}
          }

          this.init()
        }
      })
    },
    async setCountDate() {
      if (this.form.start_date) {
        this.count_date = this.$moment().format('X') - this.form.start_date
      }
      await this.$method.sleep(1000)
      this.setCountDate()
    },
    async startRandom() {
      if (this.form.is_start) {
        this.random_list = []

        this.random_list.push(this.randomIntFromInterval(1000, 1000000))
        this.random_list.push(this.randomIntFromInterval(1000, 1000000))

        this.random_list.push(this.randomIntFromInterval(1000, 1000000))

        this.random_list.push(this.randomIntFromInterval(1000, 1000000))

        this.random_list.push(this.randomIntFromInterval(1000, 1000000))

        await this.$method.sleep(5)
        this.startRandom()
      }
    },
    randomIntFromInterval(min, max) {
      // min and max included
      return Math.floor(Math.random() * (max - min + 1) + min)
    },
    async timeDown(form) {
      var time = this.$method.convSec(
        form.event_date - this.$moment().format('x') / 1000
      )
      this.$set(
        form,
        'time_down',
        time.d + 'أيام ' + time.h + 'ساعات ' + time.m + ' دقائق ' + time.s
      )
      this.time_down = time
      await this.$method.sleep(1000)
      this.timeDown({ ...form })
    },
  },
  mounted() {
    this.$store.dispatch('user/validation')
    this.audio = new Audio(require('~/assets/spin.mp3').default)
    this.$api('public/location/governorate').then((res) => {
      this.governorates = res.data.result
    })
    this.init()
    this.setCountDate()
  },
}
</script>