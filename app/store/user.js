export const state = () => ({
  is_auth: null,
  info: {},
})

export const mutations = {
  is_auth(state, is_auth) {
    state.is_auth = is_auth
  },
  team_id(state, team_id) {
    state.info.team_id = team_id
  },
  logout(state, payload) {
    this.$cookies.remove('X-Token')
    state.is_auth = false
    state.info = {}
    this.$router.push({ name: 'login' })
  },
  setInfo(state, payload) {
    this.$cookies.set('X-Token', payload.token, {
      path: '/',
      maxAge: 60 * 60 * 24 * 7,
    })
    state.info = payload.info
    state.is_auth = true
  },
}

export const actions = {
  async validation({ commit }, payload) {
    await this.$api('auth/validation')
      .then((res) => {
        commit('setInfo', res.data.result)
      })
      .catch((e) => {
        commit('is_auth', false)
      })
  },
}