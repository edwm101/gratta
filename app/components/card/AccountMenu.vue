<template>
  <div>
    <div v-if="$store.state.user.is_auth">
      <v-card-actions class="pa-0">
        <v-menu
          bottom
          v-model="menu"
          origin="center center"
          transition="scale-transition"
        >
          <template v-slot:activator="{ on }">
            <v-card-actions
              exact
              v-on="on"
              class="rounded pa-1 px-0 cp px-2 py-2"
              v-ripple
              style="min-height: 35px !important; max-width: 180px"
            >
              <v-avatar
                icon
                class="cp primary white--text font-weight-bold text-decoration-none"
                v-ripple
                v-if="$store.state.user.info.first_name"
                size="40"
              >
                <img
                  :src="$store.state.user.info.image"
                  v-if="$store.state.user.info.image"
                  alt=""
                />
                <div v-else>{{ $store.state.user.info.first_name[0] }}</div>
              </v-avatar>
              <v-list-item-content
                class="pa-0 ml-2 d-sm-block d-none"
                v-if="$store.state.user.info.role != 'reseller'"
              >
                <v-list-item-title
                  class="ma-0 line-height-1 mb-2 body-1 font-weight-bold"
                  ><span class=""
                    >{{ $store.state.user.info.first_name }}
                    {{ $store.state.user.info.last_name }}</span
                  ></v-list-item-title
                >
                <v-list-item-subtitle class="caption line-height-1 grey--text"
                  >Bienvenue</v-list-item-subtitle
                >
              </v-list-item-content>
            </v-card-actions>
          </template>
          <v-card
            class="font-weight-medium rounded-lg grey lighten-4"
            width="250"
          >
            <v-list-item class="pa-1 px-2">
              <v-avatar
                icon
                class="primary white--text font-weight-bold rounded-circle text-decoration-none"
                size="40"
              >
                <img
                  :src="$store.state.user.info.image"
                  v-if="$store.state.user.info.image"
                  alt=""
                />
                <div v-else-if="$store.state.user.info.first_name">
                  {{ $store.state.user.info.first_name[0] }}
                </div>
              </v-avatar>
              <v-list-item-content class="mx-2 ma-0 py-0">
                <v-list-item-title class="ma-0 body-1 font-weight-bold d-flex">
                  {{ $store.state.user.info.first_name }}
                  {{ $store.state.user.info.last_name }}</v-list-item-title
                >
                <v-list-item-subtitle class="ma-0 caption success--text">
                  {{ $store.state.user.info.phone }}</v-list-item-subtitle
                >
              </v-list-item-content>
              <v-icon class="close" @click="menu = false">mdi-close</v-icon>
            </v-list-item>

            <v-list class="pa-1 transparent">
             

              <v-list-item
                class="border px-2 pa-1 ma-1 py-0 body-2 rounded-lg rounded-lg overflow-hidden font-weight-medium"
                text
                exact
                @click="
                  $store.commit('user/logout')
                  $router.push({ name: 'index' })
                "
              >
                <v-icon left class="">mdi-lock</v-icon>Se déconnecter
              </v-list-item>
            </v-list>
          </v-card>
        </v-menu>
      </v-card-actions>
    </div>
    <v-card-actions v-else class="px-0">
      <v-btn
        :to="{ name: 'login', query: {} }"
        large
        class="grey lighten-5 black--text px-2"
      >
        Se connecter
      </v-btn>
    </v-card-actions>
  </div>
</template>

<script>
export default {
  data() {
    return {
      menu: false,
    }
  },
  watch: {
    '$route.name'() {
      this.$method.goToUp()
    },
  },
  mounted() {},
}
</script>