<template>
  <div class>
      <PFilter
        :operator="true"
        class="pa-0 ma-0 shadow rounded-0"
        :sort="false"
        :search="false"
        v-model="filter"
        @input="init()"
      ></PFilter>
    <toolbar name="Rapport"></toolbar>

    <v-container>
      <v-row>
        <v-col cols="12">
          <v-card
            class="rounded-lg overflow-hidden elevation-0 transparent"
            min-height="100"
          >
            <v-row class="my-0">
              <v-col>
                <div class="px-2">
                  <div class="headline font-weight-bold">
                    {{ $method.numberFormat(resume.total) }}
                  </div>
                  <div class="caption">Nombre de codes</div>
                </div>
              </v-col>
              <v-col>
                <div class="px-2">
                  <div class="headline font-weight-bold">
                    {{ $method.numberFormat(resume.winner) }}
                  </div>
                  <div class="caption">Nombre de gagnants</div>
                </div>
              </v-col>
              <v-col>
                <div class="px-2">
                  <div class="headline font-weight-bold">
                    {{ $method.moneyFormat(resume.amount) }}
                  </div>
                  <div class="caption">Montant total</div>
                </div>
              </v-col>
              <v-col v-if="resume.winner">
                <div class="px-2">
                  <div class="headline font-weight-bold">
                    {{ $method.moneyFormat(resume.amount / resume.winner) }}
                  </div>
                  <div class="caption">Montant moyen</div>
                </div>
              </v-col>
            </v-row>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      resume: {},
      filter: {},
    }
  },

  methods: {
    async init() {
      this.$api('person/admin/analytics/resume', {
        params: { ...this.filter },
      }).then((res) => {
        this.resume = res.data.result
      })
    },
  },
  async mounted() {
    this.init()
  },
}
</script>
 