export default async function ({ store, redirect, app, params, query }) {
  await app
    .$api('auth/validation')
    .then((res) => {
      store.commit('user/setInfo', res.data.result)
    })
    .catch((e) => {
      store.commit('user/is_auth', false)
      redirect({ name: 'login' })
    })
}
