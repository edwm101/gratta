const colors = require('vuetify/es5/util/colors').default
const fr = require('vuetify/es5/locale/fr')

const api_url =
  process.env.NODE_ENV !== 'production'
    ? 'http://localhost/gratta/api/v1/'
    : 'https://gratta.tvibrant.com/api/v1/'

module.exports = {
  ssr: true,
  pwa: {
    workbox: { publicPath: '/_nuxt/' },
    manifest: {
      publicPath: '/_nuxt/',
      start_url: '/user',
      name: 'Gratta',
      background_color: '#0b2239',
      short_name: 'Gratta',
      lang: 'fr',
      gcm_sender_id: '357455137326',
      display: 'standalone',
      theme_color: '#0b2239',
    },
  },
  loading: { color: '#1976d2', throttle: 0 },

  env: {
    VUE_APP_FACEBOOK_ID:
      process.env.NODE_ENV !== 'production'
        ? 1151783968514867
        : 265961204481209,
    VUE_APP_NAME: process.env.npm_package_name,
    VUE_APP_API_URL: api_url,
  },

  server: {
    port: 61000, // default: 3000   http://localhost/autoprix/api/v1
  },

  head: {
    htmlAttrs: {
      translate: 'no',
      prefix: 'og: http://ogp.me/ns#',
    },
    titleTemplate: '%s - Gratta',

    title: 'Gratta' || '',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      {
        hid: 'description',
        name: 'description',
        content: process.env.npm_package_description || '',
      },
    ],
    link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }],
    script: [],
  },

  css: ['@/assets/css/leaflet.css', '@/assets/css/style.css'],

  plugins: [
    '~/plugins/date-picker',
    '~/plugins/constant',
    '~/plugins/method',
    '~/plugins/axios',
    '~/plugins/recaptcha',
  ],
  components: true,

  buildModules: ['@nuxtjs/vuetify', '@nuxtjs/moment'],
  moment: {
    locales: ['fr'],
  },
  modules: [
    '@nuxtjs/axios',
    '@nuxtjs/pwa',
    'cookie-universal-nuxt',
    'vue-toastification/nuxt',
  ],

  axios: {},

  vuetify: {
    lang: {
      locales: { fr },
      current: 'fr',
    },
    customVariables: ['~/assets/variables.scss'],
    treeShake: true,
    defaultAssets: {
      font: {
        family: 'Roboto',
      },
      icons: 'mdi', // 'mdi' || 'mdiSvg' || 'md' || 'fa' || 'fa4' || 'faSvg'
    },
    theme: {
      dark: false,
      themes: {
        light: {
          primary: '#1976D2',
          secondary: '#424242',
          accent: '#82B1FF',
          error: '#FF5252',
          info: '#2196F3',
          warning: '#FFC107',
          success: '#05a081',
        },
        dark: {
          primary: colors.blue.darken2,
          accent: colors.grey.darken3,
          secondary: colors.amber.darken3,
          info: colors.teal.lighten1,
          warning: colors.amber.base,
          error: colors.deepOrange.accent4,
          success: colors.green.accent3,
        },
      },
    },
  },

  build: {
    analyze: false,
    extractCSS: true,
    extend(config, ctx) {
      config.module.rules.push({
        test: /\.(ogg|mp3|wav|mpe?g)$/i,
        loader: 'file-loader',
        options: {
          name: '[path][name].[ext]',
        },
      })
    },
  },
}
