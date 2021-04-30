<template>
  <div>
    <v-card
      :loading="is_loading"
      :disabled="is_loading"
      class="elevation-0 transparent"
    >
      <v-btn
        block
        large
        class="mb-2 py-7 white shadow black--text"
        @click="googleAuth()"
      >
        <img
          src="https://www.flaticon.com/svg/static/icons/svg/270/270799.svg"
          width="35"
          class="mr-1"
          alt="Connectez-vous avec Google"
        />
        <v-spacer></v-spacer>
        Connectez-vous avec Google
      </v-btn>
      <v-card-actions class="grey--text px-0">
        <v-divider></v-divider>
        <div class="mx-2">Ou</div>
        <v-divider></v-divider>
      </v-card-actions>
      <v-form
        v-if="is_signin"
        v-model="is_valid_signin"
        ref="is_valid_signin"
        class="no-shadow"
      >
        <div class="py-0 px-3 title font-weight-medium">Se connecter</div>
        <v-row no-gutters class="pa-1 pb-0">
          <v-col cols="12" class="pa-1"
            ><v-text-field
              filled
              rounded
              dense
              class="rounded input"
              name="phone"
              label="Email"
              v-model="form.email"
              :rules="[$method.rule_required]"
            ></v-text-field>
          </v-col>
          <v-col cols="12" class="pa-1">
            <v-text-field
              filled
              rounded
              dense
              class="input"
              name="password"
              :append-icon="show_password ? 'mdi-eye' : 'mdi-eye-off'"
              :rules="[$method.rule_required, $method.rule_min]"
              :type="show_password ? 'text' : 'password'"
              v-model="form.password"
              label="Mot de passe"
              @click:append="show_password = !show_password"
            >
            </v-text-field>
            <v-card-actions class="pt-0">
              <v-spacer></v-spacer>
              <a @click="forget_dialog = true">Mot de passe oublié?</a>
            </v-card-actions>
          </v-col>
        </v-row>
        <v-card-actions>
          <v-btn
            class="success rounded shadow"
            block
            :disabled="!is_valid_signin"
            @click="signin"
            v-if="is_signin"
            large
            :loading="is_loading"
            >Connexion</v-btn
          ></v-card-actions
        >
      </v-form>
      <v-form
        v-else
        v-model="is_valid_signup"
        ref="is_valid_signup"
        class="no-shadow"
      >
        <div class="py-0 px-3 title font-weight-medium">Créez votre compte</div>
        <v-row no-gutters class="pa-1 pb-0">
          <v-col cols="6" class="pa-1 pb-0">
            <v-text-field
              filled
              rounded
              dense
              class="input"
              name="first_name"
              label="Prénom"
              v-model="form.first_name"
              :rules="[$method.rule_required]"
            ></v-text-field>
          </v-col>
          <v-col cols="6" class="pa-1 pb-0">
            <v-text-field
              filled
              rounded
              dense
              class="input"
              name="last_name"
              label="Nom"
              v-model="form.last_name"
            ></v-text-field>
          </v-col>

          <v-col cols="12" class="pa-1 pt-0 pb-4 ">
            <country
              :get_visitor="true"
              @change="
                ($e) => {
                  $set(form, 'country_id', $e.id)
                }
              "
            ></country>
          </v-col>
          <v-col cols="12" class="pa-1 input">
            <v-text-field
              filled
              rounded
              dense
              name="email"
              label="Email"
              append-icon="mdi-email"
              v-model="form.email"
              :rules="[$method.rule_required, $method.rule_email]"
            ></v-text-field>
          </v-col>
          <v-col cols="12" class="pa-1">
            <v-text-field
              filled
              rounded
              dense
              class="input"
              type="number"
              name="phone"
              label="Téléphone"
              append-icon="mdi-phone"
              v-model="form.phone"
              :rules="[$method.rule_required, $method.rule_min]"
            ></v-text-field>
          </v-col>

          <v-col cols="12" class="pa-1">
            <v-text-field
              filled
              rounded
              dense
              class="input"
              name="password"
              :append-icon="show_password ? 'mdi-eye' : 'mdi-eye-off'"
              :rules="[$method.rule_required, $method.rule_min]"
              :type="show_password ? 'text' : 'password'"
              v-model="form.password"
              label="Mot de passe"
              @click:append="show_password = !show_password"
            >
            </v-text-field>
          </v-col>
        </v-row>
        <v-card-actions>
          <v-btn
            class="success rounded shadow"
            block
            @click="signup"
            large
            :disabled="!is_valid_signup"
            :loading="is_loading"
            >Enregistrer</v-btn
          >
        </v-card-actions>
      </v-form>
      <v-card-actions
        class="pa-3 px-3 body-2 cp"
        v-ripple
        v-if="is_signin"
        @click="is_signin = false"
      >
        <v-spacer></v-spacer>
        <span
          text
          class="text-center mx-auto black--text body-1 cp blue--text"
          :class="{ 'grey lighten-5': !is_signin }"
          >Créer un compte</span
        >
      </v-card-actions>
      <v-card-actions
        class="pa-3 px-3 body-2 cp"
        v-ripple
        v-else
        @click="is_signin = true"
      >
        Vous avez déjà un compte ? <v-spacer></v-spacer>
        <span
          text
          class="text-center mx-auto black--text body-1 cp blue--text"
          :class="{ 'grey lighten-5': is_signin }"
          >Connexion</span
        >
      </v-card-actions>
    </v-card>

    <v-dialog v-model="phone_dialog" scrollable persistent max-width="300px"
      ><v-card class="input back">
        <v-card-actions class="title pr-2">
          Téléphone
          <v-spacer></v-spacer>
          <v-icon class="close" @click="phone_dialog = false">mdi-close</v-icon>
        </v-card-actions>
        <div class="pa-2">
          <v-text-field
            filled
            rounded
            hide-details
            dense
            type="number"
            class="rounded"
            name="phone"
            prefix="+216"
            placeholder="Ex : 200xxxxx"
            v-model="form.phone"
            :rules="[$method.rule_required]"
          ></v-text-field>
          <div class="caption pa-2 grey--text">
            Quel est ton numéro de téléphone ?
          </div>
        </div>
        <v-card-actions class="pa-2">
          <v-spacer></v-spacer>
          <v-btn
            color="success"
            class="shadow"
            :disabled="!form.phone"
            @click="saveAccount()"
            >Enregistrer</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="forget_dialog" scrollable persistent max-width="300px"
      ><v-card class="input back" :disabled="is_loading" :loading="is_loading">
        <v-card-actions class="title pr-2">
          Mot de passe oublié
          <v-spacer></v-spacer>
          <v-icon class="close" @click="forget_dialog = false"
            >mdi-close</v-icon
          >
        </v-card-actions>
        <div v-if="!is_reset_password">
          <div class="pa-2">
            <v-text-field
              filled
              rounded
              dense
              hide-details
              name="email"
              label="Email"
              append-icon="mdi-email"
              v-model="form.email"
              :rules="[$method.rule_required, $method.rule_email]"
            ></v-text-field>
            <div class="caption pa-2 grey--text">
              Pour réinitialiser votre mot de passe, saisisser l'adresse e-mail
              que vous utilisez pour vous connecter à votre compte MonShop
            </div>
          </div>
          <v-card-actions class="pa-2">
            <v-btn color="error" @click="forget_dialog = false" text
              >Annuler</v-btn
            >

            <v-spacer></v-spacer>
            <v-btn
              color="success"
              class="shadow"
              :disabled="!form.email"
              @click="sendForgetCode()"
              >Envoyer</v-btn
            >
          </v-card-actions>
        </div>
        <div v-else-if="!is_new_password">
          <div class="pa-2">
            <v-card
              v-if="reset_password_seconds !== null"
              class="elevation-0 grey lighten-2 pa-2 text-center mb-4 display-1"
            >
              {{ $moment(reset_password_seconds).format('mm:ss') }}
              <div
                class="caption error--text"
                v-if="reset_password_seconds < 1"
              >
                La date a expiré
                <v-card-actions class="pa-0 mt-2">
                  <v-btn
                    block
                    color="white"
                    class="elevation-0"
                    @click="sendForgetCode()"
                    >Renvoyer un nouveau code</v-btn
                  >
                </v-card-actions>
              </div>
            </v-card>
            <div v-if="reset_password_seconds > 0">
              <v-text-field
                filled
                rounded
                type="number"
                dense
                hide-details
                label="Code"
                placeholder="# # # # # # # #"
                append-icon="mdi-key"
                v-model="form.code"
                :rules="[$method.rule_required]"
              ></v-text-field>
              <div class="caption pa-2 grey--text">
                Nous enverrons un code de validation au <b>{{ form.email }}</b>
              </div>
            </div>
          </div>
          <v-card-actions class="pa-2">
            <v-btn color="error" @click="forget_dialog = false" text
              >Annuler</v-btn
            >

            <v-spacer></v-spacer>
            <v-btn
              color="success"
              :disabled="!form.code && reset_password_seconds < 1"
              @click="checkCode()"
              >Verifier</v-btn
            >
          </v-card-actions>
        </div>
        <div v-else>
          <div class="pa-2">
            <v-card
              v-if="reset_password_seconds !== null"
              class="elevation-0 grey lighten-2 pa-2 text-center mb-2 display-1"
            >
              {{ $moment(reset_password_seconds).format('mm:ss') }}
              <div
                class="caption error--text"
                v-if="reset_password_seconds == 0"
              >
                La date a expiré
                <v-card-actions class="pa-0 mt-2">
                  <v-btn
                    block
                    color="white"
                    class="elevation-0"
                    @click="
                      sendForgetCode()
                      is_new_password = false
                    "
                    >Renvoyer un nouveau code</v-btn
                  >
                </v-card-actions>
              </div>
            </v-card>
            <div v-if="reset_password_seconds > 0">
              <v-text-field
                filled
                rounded
                dense
                hide-details
                name="password"
                :append-icon="show_password ? 'mdi-eye' : 'mdi-eye-off'"
                :rules="[$method.rule_required, $method.rule_min]"
                :type="show_password ? 'text' : 'password'"
                v-model="form.password"
                label="Mot de passe"
                @click:append="show_password = !show_password"
              ></v-text-field>
              <div class="caption pa-2 grey--text">
                Quel est votre nouveau mot de passe
              </div>
            </div>
          </div>
          <v-card-actions class="pa-2">
            <v-btn color="error" @click="forget_dialog = false" text
              >Annuler</v-btn
            >
            <v-spacer></v-spacer>
            <v-btn
              color="primary"
              :disabled="!form.password && reset_password_seconds < 1"
              @click="newPassword()"
              >Enregistrer</v-btn
            >
          </v-card-actions>
        </div>
      </v-card>
    </v-dialog>
  </div>
</template>


<script>
export default {
  props: {
    is_signin_f: {
      default: true,
    },
  },
  data: () => ({
    phone_dialog: false,
    forget_dialog: false,
    is_reset_password: false,
    reset_password_date: null,
    reset_password_seconds: null,
    is_new_password: false,
    is_signin: true,
    is_loading: false,
    show_password: false,
    is_valid_signin: false,
    is_valid_signup: false,
    form: {},
    dialog: false,
  }),
  computed: {
    progress() {
      return Math.min(
        100,
        (this.form.password ? this.form.password.length : 0) * 10
      )
    },
    color() {
      return ['error', 'warning', 'success'][Math.floor(this.progress / 40)]
    },
  },
  watch: {
    forget_dialog(val) {
      if (!val) this.is_reset_password = false
    },
    '$route.query.email'() {
      this.$store.commit('user/logout')
      this.form.email = this.$route.query.email
      this.form.password = this.$route.query.password
      this.signin()
    },
  },
  methods: {
    async newPassword() {
      this.is_loading = true

      var recaptcha_token = await this.$recaptcha.getToken()

      await this.$api
        .post('auth/new-reset-password', {
          ...this.form,
          ...{ recaptcha_token },
        })
        .then((res) => {
          this.$router.push({ name: 'shops' })
          this.forget_dialog = false
          this.$toast.success('Votre mot de passe a été changé')
        })
        .catch((e) => {
          this.is_loading = false
        })

      this.is_loading = false
    },
    async checkCode() {
      this.is_loading = true

      var recaptcha_token = await this.$recaptcha.getToken()

      await this.$api
        .post('auth/verification-reset-password', {
          ...this.form,
          ...{ recaptcha_token },
        })
        .then((res) => {
          this.is_new_password = true
          this.$store.commit('user/setInfo', res.data.result)
        })
        .catch((e) => {
          this.is_loading = false
        })

      this.is_loading = false
    },
    async sendForgetCode() {
      this.is_loading = true

      var recaptcha_token = await this.$recaptcha.getToken()

      await this.$api
        .post('auth/send-reset-password', {
          ...this.form,
          ...{ recaptcha_token },
        })
        .then((res) => {
          if (res.data.result) {
            this.reset_password_date = res.data.result.reset_password_date
          }
          this.is_reset_password = true
        })
        .catch((e) => {
          this.is_loading = false
        })

      this.is_loading = false
    },
    async signin() {
      if (this.is_valid_signin) {
        this.is_loading = true

        var recaptcha_token = await this.$recaptcha.getToken()

        await this.$api
          .post('auth/signin', { ...this.form, ...{ recaptcha_token } })
          .then((res) => {
            if (!res.data.result.info.phone) {
              this.phone_dialog = true
            } else {
              this.$store.commit('user/setInfo', res.data.result)
            }
          })
          .catch((e) => {
            this.is_loading = false
          })

        this.is_loading = false
      }
    },
    async signup() {
      if (this.is_valid_signup) {
        this.is_loading = true
        var recaptcha_token = await this.$recaptcha.getToken()
        await this.$api
          .post('auth/signup', { ...this.form, ...{ recaptcha_token } })
          .then((res) => {
            this.$store.commit('user/setInfo', res.data.result)
          })
          .catch((e) => {})
        this.is_loading = false
      }
      setTimeout(async () => {}, 1500)
    },
    async googleAuth() {
      this.$gAuth.signIn().then(async (res) => {
        this.is_loading = true

        var recaptcha_token = await this.$recaptcha.getToken()

        await this.$api
          .post('auth/signin', {
            access_token: res.getAuthResponse().access_token,
            access_type: 'google',
            ...{ recaptcha_token },
          })
          .then((res) => {
            if (!res.data.result.info.phone) {
              this.phone_dialog = true
            }
            this.$store.commit('user/setInfo', res.data.result)
          })
          .catch((e) => {
            this.is_loading = false
          })

        this.is_loading = false
      })
    },
    async saveAccount() {
      this.loading = true
      await this.$api
        .post('user/account', { phone: this.form.phone })
        .then((res) => {
          this.$router.push({ name: 'shops' })
          this.$store.dispatch('user/validation')
        })
      this.loading = false
    },
  },
  async mounted() {
    setInterval(() => {
      if (
        this.reset_password_date &&
        this.reset_password_date >= this.$moment().unix()
      ) {
        this.reset_password_seconds =
          (this.reset_password_date - this.$moment().unix()) * 1000
      }
    }, 1000)
    this.is_signin = this.is_signin_f

    if (this.$route.query.email) {
      this.$store.commit('user/logout')
      this.form.email = this.$route.query.email
      this.form.password = this.$route.query.password
      this.signin()
    }

    if (this.$route.query.r)
      this.$cookies.set('referral-code', this.$route.query.r, {
        path: '/',
        maxAge: 60 * 60 * 24 * 7,
      })
    if (this.$cookies.get('referral-code')) {
      this.form.referral_code = this.$cookies.get('referral-code')
    }
  },
}
</script>