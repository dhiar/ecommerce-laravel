<template>
  <!-- Begin Page Content -->
  <div class="container-fluid mb-5">
    <div
      class="modal fade"
      id="modalCod"
      tabindex="-1"
      aria-labelledby="addNewLabel"
      aria-hidden="true"
      style="width: 100%"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <div class="h5 text-gray-800 line-height-222">
              Tambah Lokasi COD
            </div>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <!-- modal-header -->

          <form @submit.prevent="createCod(form.id)">
            <div class="modal-body">
              <div class="form-group">
                <label for="name">Lokasi COD</label>
                <input
                  v-model="form.location"
                  type="text"
                  id="location"
                  name="location"
                  placeholder="location"
                  class="form-control"
                  :class="{
                    'is-invalid': submitted && $v.form.location.$error,

                    'is-valid': !$v.form.location.$invalid,
                  }"
                />
                <div class="valid-feedback">Location is valid.</div>
                <div
                  v-if="submitted && !$v.form.location.required"
                  class="invalid-feedback"
                >
                  Location harus diisi
                </div>
                <div
                  v-if="submitted && !$v.form.location.maxLength"
                  class="invalid-feedback"
                >
                  Location terlalu panjang ( maks :
                  {{ $v.form.location.$params.maxLength.max }} karakter )
                </div>
                <div
                  v-if="submitted && !$v.form.location.minLength"
                  class="invalid-feedback"
                >
                  Location terlalu pendek ( maks :
                  {{ $v.form.location.$params.minLength.min }} karakter )
                </div>
              </div>
              <div class="form-group">
                <label for="url_gmaps">URL Google Maps</label>
                <textarea
                  v-model="form.url_gmaps"
                  name="url_gmaps"
                  id="url_gmaps"
                  class="form-control"
                  rows="5"
                ></textarea>
              </div>
            </div>
            <!--modal body-->

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">
                Close
              </button>
              <button
                type="submit"
                :disabled="form.busy"
                class="btn btn-primary"
              >
                Save
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <go-back></go-back>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800 mb-4 mt-3">Pengaturan</h1>
    <div class="row">
      <div class="col-md-3">
        <div class="card shadow">
          <div class="card-body">
            <setting-menu></setting-menu>
          </div>
        </div>
      </div>

      <div class="col-md-9">
        <div class="card shadow">
          <div class="card-header">
            <h2 class="lead text-dark mb-0">COD</h2>
          </div>
          <div class="card-body table-responsive">
            <button @click="showModalCod()" class="btn btn-primary">
              Tambah Lokasi COD
            </button>
          </div>
          <table class="table table-hover">
            <thead>
              <tr>
                <th class="text-center" style="width: 10% !important">No</th>
                <th style="width: 40% !important">Location Name</th>
                <th style="width: 30% !important">Url Gmaps</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, idx) in results.data" :key="item.id">
                <td class="text-center">{{ getNumber(currentPage, idx) }}</td>
                <td>{{ item.location }}</td>
                <td>
                  <a :href="item.url_gmaps">{{ item.short_url_gmaps }}</a>
                </td>
                <td>
                  <a
                    class="btn btn-sm btn-info"
                    href="#"
                    @click="showModalCod(item.id)"
                    ><i class="fa fa-pen text-gray-100"></i
                  ></a>
                  <a
                    class="btn btn-sm btn-danger"
                    href="#"
                    @click="deleteCod(item.id, item.name)"
                    ><i class="fa fa-trash-alt text-gray-100"></i
                  ></a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import GoBack from "../GoBack.vue";
import { required, minLength, maxLength } from "vuelidate/lib/validators";

export default {
  components: {
    GoBack,
  },
  data() {
    return {
      currentPage: 1,
      perPage: 10,
      totalItems: 50,
      results: {},
      submitted: false,
      page: "cod",
      endpoint: "/api/base/cods",
      form: new Form({
        id: "",
        location: "",
        url_gmaps: "",
        short_url_gmaps: "",
      }),
    };
  },
  validations: {
    form: {
      location: {
        required,
        minLength: minLength(10),
        maxLength: maxLength(200),
      },
    },
  },
  mounted() {
    this.fetchData(1);
  },
  methods: {
    async showModalCod(id) {
      this.submitted = false;
      $("#modalCod").modal("show");
      const self = this;
      this.form.id = id;
      if (id) {
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

        this.form = result.data;
      } else {
        // clear form
        if (!this.submitted) {
          Object.keys(this.form).forEach(function (key, index) {
            self.form[key] = "";
          });
        }
      }
    },
    async createCod(id) {
      this.submitted = true;
      const self = this;

      this.$v.$touch();
      if (this.$v.$error) {
        return;
      } else {
        if (id) {
          axios
            .put(self.endpoint + "/" + id, this.form)
            .then(({ data }) => {
              if (data.success) {
                Swal.fire("Success !", data.message, "success");
              } else {
                Swal.fire("Failed !", data.message, "error");
              }
              $("#modalCod").modal("hide");
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
          await axios
            .post(self.endpoint, this.form)
            .then(({ data }) => {
              if (data.success) {
                Swal.fire("Success !", data.message, "success");
              } else {
                Swal.fire("Failed !", data.message, "error");
              }
              $("#modalCod").modal("hide");
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
      }
      this.submitted = false;
    },
    deleteCod(id, name) {
      const self = this;
      Swal.fire({
        title: "Are you sure delete " + this.page + " : " + name + " ?",
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
              $("#modalCod").modal("hide");
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
    async fetchData(page = 1) {
      const self = this;
      await axios.get(self.endpoint + "?page=" + page).then(({ data }) => {
        this.currentPage = data.current_page;
        this.perPage = data.per_page;
        this.totalItems = data.total;
        this.results = data;
      });
    },
  },
  watch: {
    currentPage: {
      handler: function (value) {
        this.fetchData(value);
      },
    },
  },
};
</script>