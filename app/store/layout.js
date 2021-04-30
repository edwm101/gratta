export const state = () => ({
  resume: {},
  call: {},
  is_call_open: false,
  is_drawer: null,
})

export const mutations = {
  resume(state, resume) {
    state.resume = resume
  },
  call(state, call) {
    state.call = call
    state.is_call_open = true
  },
  is_call_open(state, is_call_open) {
    state.is_call_open = is_call_open
  },
  is_drawer(state, is_drawer) {
    state.is_drawer = is_drawer
  },
}
