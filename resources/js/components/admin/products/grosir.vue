<template>
  <div class="container">
    <go-back></go-back>
    <div class="row mt-5">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-10 align-self-center">
                <h3 class="lead text-dark mb-0">
                  {{ form.relationships.product.name }}
                </h3>
              </div>
              <div class="col-md-2 float-right text-right">
                <button
                  class="btn btn-info text-gray-100"
                  type="button"
                  @click="prevPage()"
                >
                  <i class="fas fa-chevron-left"></i> Go Back
                </button>
              </div>
            </div>
          </div>

          <div class="card-body" style="padding: 20px">
            <div class="row">
              <div class="col-12 col-md-6 col-sm-6 themed-grid-col">
                <div class="card shadow">
                  <div class="card-header">
                    <h2 class="lead text-dark mb-0">
                      Price Per Item :
                      {{ formatCurrency(form.relationships.product.price) }}
                    </h2>
                  </div>
                  <div class="card-header">
                    <h2 class="lead text-dark mb-0">List Price of Grosir</h2>
                  </div>
                  <div class="card-body">
                    <!-- # 	Jumlah min. 	Harga Grosir 	Aksi -->
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th class="text-center" style="width: 8% !important">
                            No
                          </th>
                          <th class="text-center" style="width: 25% !important">Min. Beli</th>
                          <th class="text-right" style="width: 35% !important">
                            Harga Grosir
                          </th>
                          <th class="text-center">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(item, idx) in form.data" :key="item.id">
                          <td class="text-center">{{ idx }}</td>
                          <td class="text-center">{{ item.min }}</td>
                          <td class="text-right">{{ formatCurrency(item.price) }}</td>
                          <td class="text-center">
                            <a
                              class="btn btn-sm btn-info"
                              href="#"
                              @click="updateGrosir(item.id)"
                              ><i class="fa fa-pen text-gray-100"></i
                            ></a>
                            <a
                              class="btn btn-sm btn-danger"
                              href="#"
                              @click="deleteGrosir(item.id, item.id)"
                              ><i class="fa fa-trash-alt text-gray-100"></i
                            ></a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-sm-6 themed-grid-col">
                <form
                  @submit.prevent="
                    save($event, $route.params.id, form.id_grosir)
                  "
                >
                  <div class="card shadow">
                    <div class="card-header">
                      <h2 class="lead text-dark mb-0">Add Price Grosir</h2>
                    </div>
                    <div class="card-body">
                      <div class="form-group">
                        <label for="min"
                          >Minimum quantity of products (minimal
                          {{ form.current_min }})</label
                        >
                        <input
                          type="text"
                          v-model="form.min"
                          id="min"
                          name="min"
                          class="form-control"
                          :class="{
                            'is-invalid': submitted && $v.form.min.$error,
                            'is-valid': !$v.form.min.$invalid,
                          }"
                        />
                        <div class="valid-feedback">
                          Minimum quantity is valid.
                        </div>
                        <div
                          v-if="submitted && !$v.form.min.required"
                          class="invalid-feedback"
                        >
                          Minimum quantity harus diisi
                        </div>
                        <div
                          v-if="submitted && !$v.form.min.maxLength"
                          class="invalid-feedback"
                        >
                          Minimum quantity terlalu panjang ( maks :
                          {{ $v.form.min.$params.maxLength.max }}
                          karakter )
                        </div>
                        <div
                          v-if="submitted && !$v.form.min.minLength"
                          class="invalid-feedback"
                        >
                          Minimum quantity terlalu pendek ( min :
                          {{ $v.form.min.$params.minLength.min }}
                          karakter )
                        </div>
                        <div
                          v-if="submitted && !$v.form.min.numeric"
                          class="invalid-feedback"
                        >
                          Minimum quantity harus berupa angka
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="price">Price</label>
                        <input
                          type="text"
                          v-model="displayPrice"
                          id="price"
                          name="price"
                          @blur="isInputActive = false"
                          @focus="isInputActive = true"
                          class="form-control"
                          :class="{
                            'is-invalid': submitted && $v.form.price.$error,
                            'is-valid': !$v.form.price.$invalid,
                          }"
                        />
                        <div class="valid-feedback">Price is valid.</div>
                        <div
                          v-if="submitted && !$v.form.price.required"
                          class="invalid-feedback"
                        >
                          Price harus diisi
                        </div>
                        <div
                          v-if="submitted && !$v.form.price.maxLength"
                          class="invalid-feedback"
                        >
                          Price terlalu panjang ( maks :
                          {{ $v.form.price.$params.maxLength.max }}
                          karakter )
                        </div>
                        <div
                          v-if="submitted && !$v.form.price.minLength"
                          class="invalid-feedback"
                        >
                          Price terlalu pendek ( min :
                          {{ $v.form.price.$params.minLength.min }}
                          karakter )
                        </div>
                        <div
                          v-if="submitted && !$v.form.min.numeric"
                          class="invalid-feedback"
                        >
                          Price harus berupa angka
                        </div>
                      </div>
                    </div>
                    <div class="card-footer text-right">
                      <div class="card-tools">
                        <button
                          v-if="form.id_grosir"
                          class="btn btn-primary"
                          type="submit"
                        >
                          Update <i class="fas fa-save fa-fw"></i>
                        </button>
                        <button v-else class="btn btn-success" type="submit">
                          Create <i class="fas fa-save fa-fw"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import GoBack from "../GoBack.vue";
import {
  required,
  minLength,
  maxLength,
  numeric,
} from "vuelidate/lib/validators";
export default {
  components: {
    GoBack,
  },
  data() {
    return {
      submitted: false,
      isInputActive: false,
      page: "grosir",
      endpoint: "/api/grosirs",
      form: new Form({
        id: "",
        id_product: "",
        id_grosir: "",
        current_min: 0,
        min: 0,
        price: 0,
        data: [],
        relationships: {
          product: {},
        },
      }),
    };
  },
  computed: {
    displayPrice: {
      get: function () {
        if (this.isInputActive) {
          // Cursor is inside the input field. unformat display value for user
          return this.form.price.toString();
        } else {
          return this.formatCurrency(this.form.price);
        }
      },
      set: function (modifiedValue) {
        let newValue = parseFloat(modifiedValue.replace(/[^\d\.]/g, ""));
        if (isNaN(newValue)) {
          newValue = 0;
        }
        this.form.price = newValue;
      },
    },
  },
  validations: {
    form: {
      min: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(4),
        numeric,
      },
      price: {
        required,
        minLength: minLength(4),
        maxLength: maxLength(9),
        numeric,
      },
    },
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      let productId = this.$route.params.id;
      let endpoint_grosirs = "/api/products/" + productId + "/grosirs";

      if (productId) {
        this.form.id_product = productId;
        const result = await axios.get(endpoint_grosirs).catch((error) => {
          let errMsg = "";
          if (typeof error.response.data === "object") {
            errMsg = _.flatten(_.toArray(error.response.data.errors));
          } else {
            errMsg = ["Something went wrong. Please try again."];
          }
          Swal.fire("Failed load data !", errMsg.join(""), "error");
        });

        let productGrosirs = result.data.data;

        this.form.data = productGrosirs;
        if (productGrosirs.length > 0) {
          productGrosirs.forEach((data) => {
            this.form.relationships = data.relationships;
            this.form.current_min = data.min;
          });
        } else {
          const product = await axios.get("/api/products").catch((error) => {
            let errMsg = "";
            if (typeof error.response.data === "object") {
              errMsg = _.flatten(_.toArray(error.response.data.errors));
            } else {
              errMsg = ["Something went wrong. Please try again."];
            }
            Swal.fire("Failed load data !", errMsg.join(""), "error");
          });

          this.form.relationships.product = product.data.data[0];

          // current_min
          this.form.current_min = 0;
        }
      }
    },
    async updateGrosir(id) {
      // change value & button pada input editor
      const self = this;

      // getData
      const result = await axios
        .get(self.endpoint + "/" + id)
        .catch((error) => {
          let errMsg = "";
          if (typeof error.response.data === "object") {
            errMsg = _.flatten(_.toArray(error.response.data.errors));
          } else {
            errMsg = ["Something went wrong. Please try again."];
          }
          Swal.fire("Failed load data !", errMsg.join(""), "error");
        });

      self.form = result.data;
      self.form.id_grosir = id;

      self.fetchData();
    },
    deleteGrosir(id) {
      const self = this;
      Swal.fire({
        title: "Are you sure delete " + this.page + " : " + id + " ?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.value) {
          axios
            .delete(self.baseURL + self.endpoint + "/" + id)
            .then(({ data }) => {
              if (data.success) {
                Swal.fire("Success !", data.message, "success");
              } else {
                Swal.fire("Failed !", data.message, "error");
              }
              this.fetchData();
            })
            .catch((error) => {
              let errMsg = "";
              if (typeof error.response.data === "object") {
                errMsg = _.flatten(_.toArray(error.response.data.errors));
              } else {
                errMsg = ["Something went wrong. Please try again."];
              }
              Swal.fire("Failed save data !", errMsg.join(""), "error");
            });
        }
      });
    },
    async save(e, idProduct = false, idGrosir = false) {
      const self = this;
      this.submitted = true;

      this.$v.$touch();
      if (this.$v.$error) {
        Swal.fire(
          "Failed create " + this.page + " !",
          "All input is required.",
          "warning"
        );
        return;
      }

      // minimal beli validation
      if (self.form.min <= self.form.current_min && !idGrosir) {
        let minimum = self.form.current_min + 1
        Swal.fire(
          "Failed create " + this.page + " !",
          "Minimum pembelian produk sebanyak : " + minimum,
          "warning"
        );
        return;
      }

      // update
      if (idGrosir) {
        axios
          .put(self.endpoint + "/" + idGrosir, this.form)
          .then(({ data }) => {
            if (data.success) {
              Swal.fire("Success !", data.message, "success");
            } else {
              Swal.fire("Failed !", data.message, "error");
            }
            this.fetchData();
          })
          .catch((error) => {
            let errMsg = "";
            if (typeof error.response.data === "object") {
              errMsg = _.flatten(_.toArray(error.response.data.errors));
            } else {
              errMsg = ["Something went wrong. Please try again."];
            }
            Swal.fire("Failed save data !", errMsg.join(""), "error");
          });
      } else {
        // create
        await axios
          .post(self.endpoint, this.form)
          .then(({ data }) => {
            if (data.success) {
              Swal.fire("Success !", data.message, "success");
            } else {
              Swal.fire("Failed !", data.message, "error");
            }
            this.fetchData();
          })
          .catch((error) => {
            let errMsg = "";
            if (typeof error.response.data === "object") {
              errMsg = _.flatten(_.toArray(error.response.data.errors));
            } else {
              errMsg = ["Something went wrong. Please try again."];
            }
            Swal.fire("Failed save data !", errMsg.join(""), "error");
          });
      }

      self.form.id_grosir = "";
    },
  },
};
</script>
