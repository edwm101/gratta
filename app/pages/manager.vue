<template>
  <div>
    <client-only>
      <v-navigation-drawer
        v-model="drawer"
        app
        :clipped="$vuetify.breakpoint.lgAndUp"
        class="elevation-0 white"
        width="240px"
      >
        <div v-if="loading" class="mt-2">
          <v-skeleton-loader
            v-for="item in 6"
            :key="item"
            class="mx-auto skeleton"
            max-width="300"
            type="list-item-avatar"
          ></v-skeleton-loader>
        </div>
        <v-list dense class="pb-0 pt-1 transparent">
          <template v-for="(item, key) in items">
            <v-list-item
              :key="item.name"
              :to="{ name: item.name }"
              exact
              class="overflow-hidden pa-0 px-1 ma-1 mb-1 mx-2 rounded"
            >
              <v-icon class="mr-3 pa-1">{{ item.icon }}</v-icon>
              <v-list-item-title class="body-2">
                {{ item.text }}
              </v-list-item-title>
            </v-list-item>
          </template>
        </v-list>
        <template v-slot:append>
          <div class="pa-1">
            <v-btn
              class="font-weight-bold black--text white--text"
              color="header"
              x-large
              block
              :to="{ name: 'manager-draw-edit' }"
            >
              Nouveau tirage <v-spacer></v-spacer>
              <v-icon>mdi-clover</v-icon></v-btn
            >
          </div>
          <v-divider></v-divider>
        </template>
      </v-navigation-drawer>
      <v-app-bar
        :clipped-left="$vuetify.breakpoint.lgAndUp"
        dark
        class="header elevation-0"
        app
      >
        <v-btn @click.stop="drawer = !drawer" icon fab small class="small">
          <v-icon>mdi-menu</v-icon>
        </v-btn>
        <div v-if="loading">
          <v-skeleton-loader
            class="mx-auto transparent rounded"
            max-width="300"
            height="40"
            type="card-avatar"
          ></v-skeleton-loader>
        </div>
        <v-card-actions style="max-width: 55%; width: 370px" class="px-0 mx-1">
    
          <v-list-item-content class="mx-2">
            <v-list-item-title class="ma-0  headline text-uppercase font-weight-black d-flex">
           <nuxt-link :to="{name:'manager-draw'}" class="white--text">   Gargech</nuxt-link>
            </v-list-item-title>
          </v-list-item-content>
        </v-card-actions>

        <v-spacer></v-spacer>

        <card-account-menu></card-account-menu>
      </v-app-bar>

      <v-main>
        <div class="dash-content">
          <v-slide-y-transition mode="out-in">
            <nuxt />
          </v-slide-y-transition>
        </div>
        <div class="caption grey--text pa-2 px-4">
          2021 © Tous droits réservés
        </div>
      </v-main>
    </client-only>
  </div>
</template>

<script>
export default {
  layout: 'empty',
  middleware: 'auth',
  data: () => ({
    loading: false,
    drawer: false,
    count: {},
    shop: {},
    items: [],
    is_owner: false,
  }),
  watch: {
    '$store.state.user.is_auth'(val) {
      if (!val) {
        this.$router.push({ name: 'index' })
      }
    },
  },
  methods: {},
  async mounted() {
    this.items = [
      {
        icon: 'mdi-clover',
        text: 'Tirage',
        name: 'manager-draw',
      },
      {
        icon: 'mdi-email',
        text: 'Contact',
        name: 'manager-contact',
      },
      {
        icon: 'mdi-poll',
        text: 'Rapport',
        name: 'manager-report',
      },
    ]
  },
}
</script>


