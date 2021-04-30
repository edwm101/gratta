<template>
  <div
    class="back"
    style="
      background-position: BOTTOM right;
      background-repeat: no-repeat;
      background-size: auto;
      background-image: url(https://cdn.jsdelivr.net/gh/edwm101/cdn@master/dist/img/banner-v3-min.20ce963.png) !important;
      height: 100%;
      outline: none;
      width: 100%;
    "
    :style="
      'background-image: url(' + require('~/assets/image/back-login.png') + ')'
    "
  >
    <div
      style="
        background: rgb(3, 2, 15, 0.82);
        position: absolute;
        width: 100%;
        height: 100%;
      "
    ></div>
    <div class="dash-content">
      <v-container>
        <v-card
          class="rounded elevation-0 mt-12 transparent mx-auto"
          max-width="320"
          outlined
        >
          <div class="text-center mb-6">
            <v-avatar
              size="avatarSize"
              class="pa-0 elevation-4 rounded-xl lighten-4"
            >
              <img
                :src="require('~/assets/image/icon.png')"
                width="70px"
                style="width: 120px"
                alt=""
              />
            </v-avatar>
          </div>
          <div v-if="!is_loading">
            <v-form v-model="valid" ref="valid">
              <v-row no-gutters class="pa-1">
                <v-col cols="12" class="pa-1 mb-2">
                  <v-text-field
                    class="white shadow input"
                    filled
                    rounded
                    hide-details
                    prepend-inner-icon="mdi-account"
                    name="username"
                    label="Identifiant"
                    v-model="form.username"
                    :rules="[$method.rule_required]"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" class="pa-1">
                  <v-text-field
                    class="white shadow input"
                    filled
                    rounded
                    @keyup.enter.native="signin()"
                    prepend-inner-icon="mdi-key"
                    name="password"
                    hide-details
                    :append-icon="show_password ? 'mdi-eye-off' : 'mdi-eye'"
                    :type="show_password ? 'text' : 'password'"
                    v-model="form.password"
                    :rules="[$method.rule_required]"
                    label="Mot de passe"
                    @click:append="show_password = !show_password"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-form>

            <v-card-actions v-if="valid">
              <v-btn
                :disabled="!valid"
                class="rounded-md success shadow"
                block
                color="white"
                large
                @click="signin()"
                >Connexion</v-btn
              >
            </v-card-actions>
            <div class="text-center pa-3 caption white--text">
              2021 © Tous les droits sont réservés
            </div>
          </div>
          <div v-else>
            <div class="text-center white--text my-3">
              <v-progress-circular
                indeterminate
                color="white"
                :size="30"
                class="mr-2"
              ></v-progress-circular>
              Bienvenue
            </div>
          </div>
        </v-card>
      </v-container>
    </div>
  </div>
</template>


<script>
export default {
  layout: 'empty',
  data: () => ({
    is_loading: false,
    login_info: {},
    valid: false,
    form: {},
    show_password: false,
  }),
  watch: {
    '$store.state.user.is_auth'(val) {
      if (val) {
        var user = this.$store.state.user.info
        switch (user.role) {
          case 'admin':
            this.$router.push({ name: 'manager' })
            break
          default:
            break
        }
      }
    },
  },
  methods: {
    async signin() {
      this.is_loading = true

      this.$refs.valid.validate()

      if (this.valid) {
        await this.$api
          .post('auth/signin', { ...this.form })
          .then((res) => {
            this.$store.commit('user/setInfo', res.data.result)

            var user = this.$store.state.user.info
            switch (user.role) {
              case 'admin':
                this.$router.push({ name: 'manager' })
                break

              default:
                break
            }
          })
          .catch((e) => {})
      }

      setTimeout(() => {
        this.is_loading = false
      }, 1000)
    },
  },
  mounted() {
    this.$store.dispatch('user/validation')
    var user = this.$store.state.user.info
    switch (user.role) {
      case 'admin':
        this.$router.push({ name: 'manager' })
        break
      default:
        break
    }
  },
}
</script>