export default function({ app, store }, inject) {
  var is_first_time = true;
  var recaptcha = {
    async init() {
      if (is_first_time) {
        await app.$method.loadScript(
          "https://www.google.com/recaptcha/api.js?render=6Ld5SgEVAAAAAM07QZCHaT2pVE_nmIu6eNawUUkq"
        );
      }
    },
    async getToken() {
      await this.init();
      is_first_time = false;
      return new Promise((res, rej) => {
        grecaptcha.ready(async () => {
          grecaptcha
            .execute("6Ld5SgEVAAAAAM07QZCHaT2pVE_nmIu6eNawUUkq", {
              action: "submit"
            })
            .then(token => {
              return res(token);
            });
        });
      });
    }
  };
  inject("recaptcha", recaptcha);
}
