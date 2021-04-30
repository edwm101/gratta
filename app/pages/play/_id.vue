<template>
  <div class="white">
    <section class="back-home" style="min-height: 100vh">
      <v-card
        class="elevation-0 mx-auto py-5 px-4"
        tile
        dark
        style="background: rgb(11, 34, 57, 1)"
      >
        <v-card
          width="120px"
          class="line-height-0 mx-auto shadow rounded-xl"
          :to="{ name: 'index' }"
        >
          <img :src="require('~/assets/image/logo.png')" alt="" />
        </v-card>
        <h1 class="display-1 pt-8 text-center pb-5 font-weight-black">
          Tirage №{{ form.id }}
        </h1>
        <div
          class="title text-center"
          v-if="
            form.is_start ||
            (form.start_date &&
              count_date &&
              form.current_winner < resume.winner)
          "
        >
          IL y a {{ $method.convSec(count_date, true) }}
        </div>
        <div
          class="title text-center"
          v-else-if="resume.winner == form.current_winner"
        >
          Cet événement est terminé
        </div>
        <div class="title text-center" v-else-if="time_down && form.event_date">
          {{ time_down }}
        </div>
      </v-card>
      <div class="">
        <v-container grid-list-xs>
          <v-row>
            <v-col cols="12" md="4">
              <v-card class="elevation-0">
                <div
                  class="pa-2"
                  v-if="
                    !form.is_start &&
                    form.current_winner < resume.winner &&
                    $store.state.user.info.role == 'admin'
                  "
                >
                  <v-btn
                    class="elevation-0 black--text rounded-lg border shadow title"
                    x-large
                    block
                    @click="start()"
                    ><v-icon large class="mr-4">mdi-play</v-icon> Play</v-btn
                  >
                </div>

                <div>
                  <v-simple-table fixed-header v-if="1 == 0"></v-simple-table>
                  <div
                    class="v-data-table v-data-table--fixed-header theme--light rounded-lg overflow-hidden"
                  >
                    <div class="v-data-table__wrapper">
                      <div class="title pa-2">Les gagnants</div>

                      <v-divider></v-divider>
                      <table>
                        <thead>
                          <tr>
                            <th class="text-left">Code</th>
                            <th class="text-left">Montante</th>
                          </tr>
                        </thead>

                        <tbody>
                          <tr v-for="item in winners" :key="item.id" class="cp">
                            <td>
                              <b> {{ item.code }}</b>
                            </td>
                            <td>
                              <b class="success--text">
                                {{ $method.moneyFormat(item.amount) }}</b
                              >
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <card-list-vide
                        title="La liste est vide"
                        v-if="winners.length < 1"
                        class="mx-auto"
                      ></card-list-vide>
                    </div>
                  </div>
                </div>

                <v-btn
                  v-if="
                    winners.length > 0 && $store.state.user.info.role == 'admin'
                  "
                  color="error"
                  class="white--text"
                  small
                  text
                  @click="cancel()"
                  ><v-icon class="mr-1">mdi-reload</v-icon> Annuler</v-btn
                >
              </v-card>
            </v-col>
            <v-col cols="12" md="8">
              <v-row class="my-0">
                <v-col>
                  <div class="px-2">
                    <div class="headline font-weight-bold">
                      {{ $method.numberFormat(resume.winner) }}
                    </div>
                    <div class="caption">Nombre de gagnants</div>
                  </div>
                </v-col>
                <v-col>
                  <div class="px-2">
                    <div class="headline font-weight-bold">
                      {{ $method.moneyFormat(resume.amount) }}
                    </div>
                    <div class="caption">Montant total</div>
                  </div>
                </v-col>
                <v-col v-if="resume.winner">
                  <div class="px-2">
                    <div class="headline font-weight-bold">
                      {{ $method.moneyFormat(resume.amount / resume.winner) }}
                    </div>
                    <div class="caption">Montant moyen</div>
                  </div>
                </v-col>
              </v-row>

              <div class="body-1 pa-2">{{ form.description }}</div>
              <iframe
                :src="
                  'https://www.facebook.com/plugins/video.php?height=341&href=' +
                  form.live_link
                "
                class="rounded-lg mt-3 black"
                style="height: 341px; width: 500px"
                frameborder="0"
              ></iframe>
            </v-col>
          </v-row>
        </v-container>
      </div>
    </section>

    <v-dialog
      v-model="dialog_select"
      scrollable
      persistent
      :overlay="false"
      max-width="300px"
      transition="dialog-transition"
    >
      <v-card v-if="form.is_start">
        <v-list
          class="overflow-auto pa-2 font-weight-bold"
          style="height: 240px"
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

        <div>
          <div class="pa-2 font-weight-bold">
            ({{ form.current_winner }}/{{ resume.winner }})
          </div>
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
        <div>
          <v-list-item-content class="pa-3 py-1">
            <v-list-item-subtitle> Le code gagnant</v-list-item-subtitle>
            <v-list-item-title class="display-1">
              {{ current_winner.code }}
            </v-list-item-title>
          </v-list-item-content>
          <v-list-item-content class="pa-3">
            <v-list-item-subtitle> Montant </v-list-item-subtitle>
            <v-list-item-title class="display-3 success--text font-weight-bold">
              {{ $method.moneyFormat(current_winner.amount) }}
            </v-list-item-title>
          </v-list-item-content>
          <v-list-item-title
            class="display-1 success--text font-weight-bold pa-3 pt-0"
          >
            Félicitation
            <v-icon class="display-1 success--text font-weight-bold"
              >mdi-gift</v-icon
            >
          </v-list-item-title>
          <v-card-actions
            v-if="!form.is_start && form.current_winner < resume.winner"
          >
            <v-btn
              class="border elevation-0"
              block
              @click="start()"
              v-if="$store.state.user.info.role == 'admin'"
              ><v-icon>mdi-play</v-icon> Suivante</v-btn
            >
          </v-card-actions>
          <v-card-actions class="title" v-else>
            Cet événement est terminé ({{ form.current_winner }}/{{
              resume.winner
            }})</v-card-actions
          >
          <v-card-actions>
            <v-btn
              class="border elevation-0"
              block
              @click="dialog_select = false"
            >
              Fermer</v-btn
            >
          </v-card-actions>
        </div>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  layout: 'empty',
  data() {
    return {
      dialog_select: false,
      form: {},
      resume: {},
      winners: [],
      count_date: null,
      random_list: [],
      current_winner: {},
      is_first_time: true,
      time_down: null,
    }
  },
  methods: {
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

      if (!this.form.current_winner) {
        this.form.start_date = this.$moment().format('X')
        this.form.current_winner = 0
      }

      this.dialog_select = true
      this.form.current_winner++
      this.save()
      this.startRandom()
    },
    stop() {
      this.find(false)
    },
    find(is_start = true) {
      this.$api('public/draw/winner', { params: { id: this.form.id } }).then(
        (res) => {
          this.winners = res.data.result

          if (!is_start) {
            this.form.is_start = false
            this.save()
          }

          if (this.form.is_start) {
            this.winners.pop()
          }
          if (this.winners.length > 0) {
            this.random_list.push(
              this.randomIntFromInterval(this.form.start, this.form.end)
            )
            this.random_list.push(
              this.randomIntFromInterval(this.form.start, this.form.end)
            )

            this.random_list.push(this.winners[this.winners.length - 1].code)

            this.current_winner = this.winners[this.winners.length - 1]

            this.random_list.push(
              this.randomIntFromInterval(this.form.start, this.form.end)
            )

            this.random_list.push(
              this.randomIntFromInterval(this.form.start, this.form.end)
            )
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
      this.$api('public/draw', {
        params: { id: this.$route.params.id },
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

        this.random_list.push(
          this.randomIntFromInterval(
            this.form.start_interval,
            this.form.end_interval
          )
        )
        this.random_list.push(
          this.randomIntFromInterval(
            this.form.start_interval,
            this.form.end_interval
          )
        )

        this.random_list.push(
          this.randomIntFromInterval(
            this.form.start_interval,
            this.form.end_interval
          )
        )

        this.random_list.push(
          this.randomIntFromInterval(
            this.form.start_interval,
            this.form.end_interval
          )
        )

        this.random_list.push(
          this.randomIntFromInterval(
            this.form.start_interval,
            this.form.end_interval
          )
        )

        await this.$method.sleep(10)
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
        time.d + 'd ' + time.h + 'h ' + time.m + 'm ' + time.s
      )
      this.time_down = time.d + 'd ' + time.h + 'h ' + time.m + 'm ' + time.s
      await this.$method.sleep(1000)
      this.timeDown({ ...form })
    },
  },
  mounted() {
    this.$store.dispatch('user/validation')

    this.init()
    this.setCountDate()
  },
}
</script>