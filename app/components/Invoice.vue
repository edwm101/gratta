<template>
  <v-dialog v-model="dialog" scrollable max-width="1200px">
    <template v-slot:activator="{ on, attrs }">
      <v-btn class="rounded-lg" large text height="52" v-bind="attrs" v-on="on">
        Facture <v-icon class="mx-2">mdi-printer</v-icon>
      </v-btn>
    </template>
    <div class="grey lighten-4">
      <v-card-actions class="pa-2 font-weight-medium title">
        Order #{{ form.id }}
        <v-spacer></v-spacer>
        <v-icon class="close" @click="dialog = false">mdi-close</v-icon>
      </v-card-actions>
      <v-row no-gutters style="max-height: calc(100vh)" class="overflow-auto">
        <v-col cols="12" sm="3" lg="2" class="pa-2">
          <div
            class="elevation-0"
            @click="$set(facture_filter, 'is_show', false)"
            outlined
          >
            <v-switch
              label="Ticket"
              color="success"
              hide-details
              class="pa-2 pt-0"
              v-model="facture_filter.is_ticket"
              @change="
                $method.setStorage('is_ticket', facture_filter.is_ticket)
              "
            ></v-switch>

            <v-card
              class="elevation-0 mb-1 pa-1 px-2"
              v-if="
                (last_invoice.invoice_number || last_invoice.invoice_text) &&
                !facture_filter.is_ticket
              "
            >
              <div
                class="caption grey--text"
                v-if="last_invoice.invoice_number"
              >
                Dernier déclarée facture number
              </div>
              <div v-if="last_invoice.invoice_number">
                {{ last_invoice.invoice_number }}
              </div>
              <div class="caption grey--text" v-if="last_invoice.invoice_text">
                Dernier facture text
              </div>
              <v-list-item-subtitle v-if="last_invoice.invoice_text">{{
                last_invoice.invoice_text
              }}</v-list-item-subtitle>
              <v-card-actions class="pa-0">
                <v-spacer></v-spacer>
                <v-btn
                  color="success"
                  text
                  @click="
                    form.invoice_number = last_invoice.invoice_number + 1
                    last_invoice.invoice_text
                      ? (form.invoice_text = last_invoice.invoice_text + '')
                      : ''
                  "
                  >Insérer {{ last_invoice.invoice_number + ' + 1' }}</v-btn
                >
              </v-card-actions>
            </v-card>
            <v-select
              v-if="!facture_filter.is_ticket"
              outlined
              :items="['Bon de livraison', 'Facture', 'Devis']"
              v-model="facture_filter.type"
              label="Type"
              class="ma-2 mb-3"
              hide-details
              dense
            ></v-select>

            <v-text-field
              class="ma-2 mb-3"
              outlined
              dense
              rows="2"
              v-if="!facture_filter.is_ticket"
              hide-details
              label="Numéro de facture"
              name="name"
              v-model="form.invoice_number"
            ></v-text-field>
            <v-textarea
              class="ma-2 mb-2"
              outlined
              dense
              v-if="!facture_filter.is_ticket"
              rows="3"
              hide-details
              label="Texte en bas de la facture"
              name="name"
              counter
              textarea
              v-model="form.invoice_text"
            ></v-textarea>
            <v-card-actions>
              <v-btn
                color="primary"
                class="shadow"
                block
                :loading="facture_filter.loading"
                :disabled="facture_filter.loading"
                large
                @click="
                  savePdf()
                  save()
                "
                >Créer la facture</v-btn
              >
            </v-card-actions>
          </div>
        </v-col>
        <v-col cols="12" sm="9" lg="10" class="pa-2">
          <div
            class="overflow-auto mx-auto rounded shadow"
            style="max-height: calc(100vh - 150px)"
            tile
            :style="{
              'max-width': facture_filter.is_ticket ? '400px' : '100%',
            }"
          >
            <div v-if="facture_filter.is_show" class="line-height-0">
              <iframe
                type="application/pdf"
                :src="facture_filter.pdf_src"
                style="height: calc(100vh - 150px); border: 0"
                width="100%"
                class="overflow-auto"
              ></iframe>
            </div>
            <div v-else ref="ticket">
              <div
                class="page white rounded pa-8"
                v-if="!facture_filter.is_ticket"
              >
                <v-row>
                  <v-col cols="12" sm="6">
                    <div class="pa-3 pt-0 grey lighten-4 rounded-lg border">
                      <div class="pa-4 display-2 font-weight-bold text-center">
                        {{ $store.state.shop.info.name }}
                      </div>

                      <div
                        v-if="$store.state.shop.info.company_name"
                        style="font-weight-medium"
                      >
                        {{ $store.state.shop.info.company_name }}
                      </div>
                      <div v-if="$store.state.shop.info.company_mf">
                        Matricule Fiscal :
                        {{ $store.state.shop.info.company_mf }}
                      </div>
                      <div v-if="$store.state.shop.info.address">
                        {{ $store.state.shop.info.address }}
                      </div>
                      <div v-if="$store.state.shop.info.phone">
                        Tél : {{ $store.state.shop.info.phone }}
                      </div>
                      <div v-if="$store.state.shop.info.emai">
                        Email : {{ $store.state.shop.info.email }}
                      </div>
                    </div>
                  </v-col>
                  <v-col cols="12" sm="6">
                    <h3>
                      {{ facture_filter.type }}
                      {{
                        form.invoice_number ? 'n°' + form.invoice_number : ''
                      }}
                    </h3>
                    <div>
                      du
                      {{ $moment.unix(form.creation_date).format('d/m/YYYY') }}
                    </div>
                  </v-col>
                </v-row>
                <v-divider class="my-2 mt-3"></v-divider>
                <v-row>
                  <v-col
                    cols="12"
                    md="6"
                    v-if="
                      form.first_name ||
                      form.last_name ||
                      form.phone ||
                      form.company_name ||
                      form.company_mf ||
                      company_address
                    "
                  >
                    <h3>FACTURATION</h3>
                    <div>{{ form.first_name }} {{ form.last_name }}</div>
                    <div v-if="form.company_name">
                      Ste : {{ form.company_name }}
                    </div>
                    <div v-if="form.company_mf">
                      Matricule Fiscal : {{ form.company_mf }}
                    </div>
                    <div>
                      {{ form.company_address }}
                    </div>
                    <div v-if="form.phone">Tél : {{ form.phone }}</div>
                  </v-col>
                  <v-col
                    cols="12"
                    md="6"
                    v-if="form.first_name || form.last_name || form.phone"
                  >
                    <h3>LIVRAISON</h3>

                    <div>{{ form.first_name }} {{ form.last_name }}</div>
                    <div v-if="form.phone">Tél : {{ form.phone }}</div>

                    <div>
                      {{ form.address
                      }}{{ form.zip_code ? ', ' + form.zip_code : ''
                      }}{{ form.governorate ? ', ' + form.governorate : ''
                      }}{{ form.country ? ', ' + form.country : '' }}
                    </div>
                  </v-col>
                </v-row>
                <div outlined class="my-3">
                  <v-simple-table> </v-simple-table>

                  <div class="v-data-table theme--light">
                    <div class="v-data-table__wrapper border rounded-lg">
                      <table>
                        <thead>
                          <tr class="grey lighten-3">
                            <th class="text-left">Désignation</th>
                            <th class="text-left">Qté</th>
                            <th class="text-left">TVA</th>
                            <th class="text-left">Prix U. HT</th>
                            <th class="text-left">Total HT</th>
                            <th class="text-left">Prix U. TTC</th>
                            <th class="text-left">Total TTC</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="product in form.products"
                            :key="product.id"
                          >
                            <td style="max-width: 170px">{{ product.name }}</td>
                            <td>{{ product.qty }}</td>
                            <td>{{ product.tva ? product.tva + '%' : '' }}</td>
                            <td>
                              {{
                                product.tva
                                  ? $method.moneyFormat(
                                      (product.price * 100) /
                                        (100 + product.tva)
                                    )
                                  : ''
                              }}
                            </td>

                            <td>
                              {{
                                product.tva
                                  ? $method.moneyFormat(
                                      ((product.price * 100) /
                                        (100 + product.tva)) *
                                        product.qty
                                    )
                                  : ''
                              }}
                            </td>
                            <td>
                              {{ $method.moneyFormat(product.price) }}
                            </td>
                            <td>
                              {{
                                $method.moneyFormat(product.price * product.qty)
                              }}
                            </td>
                          </tr>
                          <tr v-if="form.delivery_price">
                            <td>Frais de livraison</td>
                            <td>1</td>
                            <td>
                              {{
                                form.delivery_tva ? form.delivery_tva + '%' : ''
                              }}
                            </td>
                            <td>
                              {{
                                form.delivery_tva
                                  ? $method.moneyFormat(
                                      (form.delivery_price * 100) /
                                        (100 + form.delivery_tva)
                                    )
                                  : ''
                              }}
                            </td>
                            <td>{{ form.delivery_price }}</td>
                            <td>
                              {{
                                form.delivery_tva
                                  ? $method.moneyFormat(
                                      (form.delivery_price * 100) /
                                        (100 + form.delivery_tva)
                                    )
                                  : ''
                              }}
                            </td>
                            <td>{{ form.delivery_price }}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <v-card-actions class="pa-0">
                  <h4>Mode de règlement : {{ $c[form.payment_method] }}</h4>
                  <v-spacer></v-spacer>
                  <div
                    outlined
                    style="min-width: 300px"
                    class="pa-3 mt-2 border rounded-lg"
                  >
                    <div class="title" v-if="form.total_offer">
                      Remise promotionnelle :
                      <b> -{{ $method.moneyFormat(form.total_offer) }}</b>
                      <v-divider class="my-2"></v-divider>
                    </div>
                    <div class="title" v-if="form.total_tva">
                      Total HT :
                      <b>
                        {{
                          $method.moneyFormat(
                            form.total_tva +
                              (form.delivery_price * 100) /
                                (100 + form.delivery_tva)
                          )
                        }}</b
                      >
                      <v-divider class="my-2"></v-divider>
                    </div>

                    <div class="title">
                      Total TTC :
                      <b>
                        {{
                          $method.moneyFormat(
                            form.total_price + form.delivery_price
                          )
                        }}</b
                      >
                    </div>
                  </div>
                </v-card-actions>

                <div
                  class="pa-2 rounded grey lighten-3 mt-4"
                  v-if="form.invoice_text"
                >
                  {{ form.invoice_text }}
                </div>
              </div>
              <div class="page white rounded pa-4" v-else>
                <div class="pa-0 rounded-lg">
                  <div class="pa-1 pb-2 text-center font-weight-bold display-1">
                    {{ $store.state.shop.info.name }}
                  </div>
                  <div v-if="$store.state.shop.info.company_name">
                    {{ $store.state.shop.info.company_name }}
                  </div>
                  <div v-if="$store.state.shop.info.company_mf">
                    Matricule Fiscal : {{ $store.state.shop.info.company_mf }}
                  </div>
                  <div v-if="$store.state.shop.info.address">
                    {{ $store.state.shop.info.address }}
                  </div>
                  <div v-if="$store.state.shop.info.phone">
                    Tél : {{ $store.state.shop.info.phone }}
                  </div>
                  <div v-if="$store.state.shop.info.emai">
                    Email : {{ $store.state.shop.info.email }}
                  </div>
                </div>
                <v-divider class="my-2 mt-3"></v-divider>

                <div>Référence: {{ form.id }}</div>
                <div>
                  Date de commande :
                  {{ $moment.unix(form.creation_date).format('LLL') }}
                </div>
                <div outlined class="my-3">
                  <v-simple-table> </v-simple-table>

                  <div class="v-data-table theme--light">
                    <div class="v-data-table__wrapper border rounded-lg">
                      <table>
                        <thead>
                          <tr class="grey lighten-3">
                            <th class="text-left">Désignation</th>
                            <th class="text-left">Total HT</th>
                            <th class="text-left">Total TTC</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="product in form.products"
                            :key="product.id"
                          >
                            <td>
                              <b class="body-1 font-weight-bold"
                                >x{{ product.qty }}</b
                              >
                              {{ product.name }}
                            </td>

                            <td>
                              {{
                                product.tva
                                  ? $method.moneyFormat(
                                      ((product.price * 100) /
                                        (100 + product.tva)) *
                                        product.qty,
                                      ''
                                    )
                                  : ''
                              }}
                            </td>

                            <td>
                              {{
                                $method.moneyFormat(
                                  product.price * product.qty,
                                  ''
                                )
                              }}
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="my-1 font-weight-medium">
                  Mode de règlement : {{ $c[form.payment_method] }}
                </div>
                <div class="pa-0 mt-2">
                  <div v-if="form.total_price != total_price_ht">
                    <b>Total HT</b> :
                    {{
                      $method.moneyFormat(
                        form.total_tva +
                          (form.delivery_price * 100) /
                            (100 + form.delivery_tva)
                      )
                    }}
                    <v-divider class="my-2"></v-divider>
                  </div>
                  <div>
                    <b>Total TTC</b> :
                    {{
                      $method.moneyFormat(
                        form.total_price + form.delivery_price
                      )
                    }}
                  </div>
                </div>
                <div class="mt-2 text-center">
                  Merci de votre visite ... A bientot
                </div>
              </div>
            </div>
          </div>
        </v-col>
      </v-row>
    </div>
  </v-dialog>
</template>


<script>
import html2PDF from 'jspdf-html2canvas'

export default {
  props: {
    value: {
      type: Object,
      default() {
        return {}
      },
    },
  },
  data() {
    return {
      dialog: false,
      form: {},
      loading: false,
      facture_filter: {
        type: 'Facture',
        method: 'Espèces',
        pdf_src: null,
        is_print: false,
        loading: false,
        is_show: false,
        is_ticket: false,
      },
      last_invoice: {},
    }
  },
  computed: {
    total_price_ht: function () {
      var value = 0
      if (this.form.products) {
        this.form.products.map((product) => {
          value +=
            (product.discount_price
              ? product.price * parseInt(product.qty) - product.discount_price
              : product.price * parseInt(product.qty)) /
            (1 + product.tva / 100)
        })
      }
      return value
    },
  },
  watch: {
    value() {
      this.form = this.value
    },
    dialog(val) {
      if (val) {
        if (this.facture_filter.is_ticket) {
          this.savePdf()
        }
        this.$api('user/order/last-invoice', {
          params: { id: this.form.id },
        }).then((res) => {
          this.last_invoice = res.data.result
        })
      }
    },
  },
  methods: {
    async savePdf() {
      this.$emit('input', this.form)
      this.$set(this.facture_filter, 'is_show', false)
      this.facture_filter.loading = true
      this.$method.goToUp()
      let page = document.getElementsByClassName('page')
      var width = null
      var height = null
      await this.$method.sleep(500)
      if (this.$refs.ticket) {
        var width = this.$refs.ticket.clientWidth
        var height = this.$refs.ticket.clientHeight
      }
      const pdf = await html2PDF(page, {
        jsPDF: {
          fontFamily: 'initial',
          imageType: 'image/jpeg',
          format: this.facture_filter.is_ticket
            ? [width * 2, (height + 300) * 2]
            : 'a4',
        },
        html2canvas: {
          imageTimeout: 15000,
          logging: true,
          useCORS: false,
        },
        imageQuality: 1,

        save: false,
        imageType: 'image/jpeg',
        success: (t) => {
          this.$set(this.facture_filter, 'is_show', true)
        },
      })
      this.facture_filter.loading = false
      this.facture_filter.pdf_src = pdf.output('datauri')
      pdf.autoPrint()
    },
    save() {
      this.$api.post('user/order', {
        ...this.form,
      })
    },
  },
  mounted() {
    this.form = this.value

    if (this.$method.getStorage('is_ticket') !== null)
      this.facture_filter.is_ticket = JSON.parse(
        this.$method.getStorage('is_ticket')
      )
  },
}
</script>