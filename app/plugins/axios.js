export default function (
  { app, $axios, redirect, store, context, req, route },
  inject
) {
  if (process.browser) {
    var host = process.browser ? window.location.hostname : req.headers.hostname

    const api = $axios.create({
      baseURL: '//' + host + process.env.VUE_APP_API_URL,
      timeout: 25000,
    })

    api.interceptors.request.use((config) => {
      config.progress = false
      config.headers['shopid'] = app.$cookies.get('shopid')
        ? app.$cookies.get('shopid')
        : null
      config.headers['X-Date'] = new Date().toISOString().substr(0, 16)
      config.headers['X-Token'] = app.$cookies.get('X-Token')
        ? app.$cookies.get('X-Token')
        : null
      config.headers['X-Token-Driver'] = app.$cookies.get('X-Token-Driver')
        ? app.$cookies.get('X-Token-Driver')
        : null
      config.data = JSON.stringify(config.data)
      return config
    })

    api.onError((error) => {
      var status = false
      if (!error.response) {
        console.log(error)
      } else {
        var status = error.response.data.status
        switch (status) {
          case 'INVALID_PERMISSION':
            app.$toast.error('Autorisation invalide')
            break
          case 'INSUFFICIENT_BALANCE':
            app.$toast.error('Solde insuffisant')
            break
          case 'IS_DEMO':
            app.$toast.error(
              "Ce compte n'a pas le droit de réaliser des modifications."
            )
            break
          case 'ALREADY_EXISTS':
            app.$toast.error('Existe déjà')
            break
          case 'USER_NOT_EXISTS':
            app.$toast.error('USER_NOT_EXISTS')
            break
          case 'INVALID_CODE':
            app.$toast.error('Le code est invalide')
            break
          case 'EMAIL_NOT_EXISTS':
            app.$toast.error("L'utilisateur n'existe pas")
            break
          case 'EMAIL_EXISTS':
            app.$toast.error('Cet utilisateur  existe déjà')
            break
          case 'INVALID_EMAIL':
            app.$toast.error("L'utilisateur est invalide")
            break
          case 'INVALID_PASSWORD':
            app.$toast.error('Mot de passe incorrect')
            break
          case 'IS_NULL':
            app.$toast.error('Veuillez remplir tous les champs obligatoires')
            break
          case 'EXPIRED':
            app.$toast.error('La date a expiré')
            break
          default:
            break
        }
      }
      return Promise.reject(error.response)
    })

    inject('api', api)

    //Upload

    var api_upload = $axios.create({
      baseURL: process.env.VUE_APP_API_URL,
      timeout: 1000 * 60 * 920,
    })

    api_upload.interceptors.request.use((config) => {
      config.progress = false
      config.headers['shopid'] = app.$cookies.get('shopid')
        ? app.$cookies.get('shopid')
        : null
      config.headers['X-Date'] = new Date().toISOString().substr(0, 16)
      config.headers['X-Token'] = app.$cookies.get('X-Token')
        ? app.$cookies.get('X-Token')
        : null
      return config
    })

    api_upload.onError((error) => {
      var status = false
      if (!error.response) {
        console.log(error)
      } else {
        var status = error.response.data.status
        switch (status) {
          case 'INVALID_SHOP_PERMISSION':
            app.$toast.error('Autorisation invalide')
            break
          case 'IS_NULL':
            app.$toast.error('Veuillez remplir tous les champs obligatoires')
            break
          default:
            break
        }
      }
      return Promise.reject(error.response)
    })

    inject('api_upload', api_upload)
  }
}
