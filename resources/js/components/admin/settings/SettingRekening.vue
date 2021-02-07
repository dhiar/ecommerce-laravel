<template>
  <!-- Begin Page Content -->
  <div class="container-fluid mb-5">
    <div
      class="modal fade"
      id="modalRekening"
      tabindex="-1"
      aria-labelledby="addNewLabel"
      aria-hidden="true"
      style="width: 100%"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <div class="h5 text-gray-800 line-height-222">Tambah Rekening</div>
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

          <form @submit.prevent="createRekening(form.id)">
            <div class="modal-body">
              <label for="rekening">Nama Bank / Provider E-Money</label>
              <div class="form-group">
                <input
                  v-model="form.rekening"
                  type="text"
                  id="rekening"
                  name="rekening"
                  placeholder="Nama Bank / Provider E-Money"
                  class="form-control"
                  :class="{
                    'is-invalid': submitted && $v.form.rekening.$error,

                    'is-valid': !$v.form.rekening.$invalid,
                  }"
                />
                <div class="valid-feedback">Description is valid.</div>
                <div
                  v-if="submitted && !$v.form.rekening.required"
                  class="invalid-feedback"
                >
                  Nama Bank harus diisi
                </div>
                <div
                  v-if="submitted && !$v.form.rekening.maxLength"
                  class="invalid-feedback"
                >
                  Nama Bank terlalu panjang ( maks :
                  {{ $v.form.rekening.$params.maxLength.max }} karakter )
                </div>
                <div
                  v-if="submitted && !$v.form.rekening.minLength"
                  class="invalid-feedback"
                >
                  Nama Bank terlalu pendek ( maks :
                  {{ $v.form.rekening.$params.minLength.min }} karakter )
                </div>
              </div>
              <label for="name">Atas Nama</label>
              <div class="form-group">
                <input
                  v-model="form.name"
                  type="text"
                  id="name"
                  name="name"
                  placeholder="Atas Nama"
                  class="form-control"
                  :class="{
                    'is-invalid': submitted && $v.form.name.$error,

                    'is-valid': !$v.form.name.$invalid,
                  }"
                />
                <div class="valid-feedback">Atas Nama is valid.</div>
                <div
                  v-if="submitted && !$v.form.name.required"
                  class="invalid-feedback"
                >
                  Atas Nama harus diisi
                </div>
                <div
                  v-if="submitted && !$v.form.name.maxLength"
                  class="invalid-feedback"
                >
                  Atas Nama terlalu panjang ( maks :
                  {{ $v.form.name.$params.maxLength.max }} karakter )
                </div>
                <div
                  v-if="submitted && !$v.form.name.minLength"
                  class="invalid-feedback"
                >
                  Atas Nama terlalu pendek ( maks :
                  {{ $v.form.name.$params.minLength.min }} karakter )
                </div>
              </div>
              <label for="number">Nomor Rekening</label>
              <div class="form-group">
                <input
                  v-model="form.number"
                  type="text"
                  id="number"
                  name="number"
                  placeholder="Nomor Rekening"
                  class="form-control"
                  :class="{
                    'is-invalid': submitted && $v.form.number.$error,

                    'is-valid': !$v.form.number.$invalid,
                  }"
                />
                <div class="valid-feedback">Nomor Rekening is valid.</div>
                <div
                  v-if="submitted && !$v.form.number.required"
                  class="invalid-feedback"
                >
                  Nomor Rekening harus diisi
                </div>
                <div
                  v-if="submitted && !$v.form.number.maxLength"
                  class="invalid-feedback"
                >
                  Nomor Rekening terlalu panjang ( maks :
                  {{ $v.form.number.$params.maxLength.max }} karakter )
                </div>
                <div
                  v-if="submitted && !$v.form.number.minLength"
                  class="invalid-feedback"
                >
                  Nomor Rekening terlalu pendek ( maks :
                  {{ $v.form.number.$params.minLength.min }} karakter )
                </div>
              </div>
            </div>
            <!-- modal-body -->

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
            <!-- modal-footer -->
          </form>
        </div>
      </div>
    </div>

    <!-- Page Heading -->
    <go-back></go-back>
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
            <h2 class="lead text-dark mb-0">Rekening</h2>
          </div>
          <div class="card-body table-responsive">
            <button @click="showModalRekening()" class="btn btn-primary">
              Tambah Rekening
            </button>
            <hr />
            <table class="table table-hover">
              <thead>
                <tr>
                  <th style="width: 10% !important">No</th>
                  <th style="width: 15% !important">Nama Bank</th>
                  <th style="width: 25% !important">Atas Nama</th>
                  <th style="width: 35% !important">No Rekening</th>
                  <th style="width: 15% !important">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, idx) in results.data" :key="item.id">
                  <td class="text-center">{{ getNumber(currentPage, idx) }}</td>
                  <td>{{ item.rekening }}</td>
                  <td>{{ item.name }}</td>
                  <td>{{ item.number }}</td>
                  <td>
                    <a
                      class="btn btn-sm btn-info"
                      href="#"
                      @click="showModalRekening(item.id)"
                      ><i class="fa fa-pen text-gray-100"></i
                    ></a>
                    <a
                      class="btn btn-sm btn-danger"
                      @click="deleteRekening(item.id, item.name)"
                      ><i class="fa fa-trash-alt text-gray-100"></i
                    ></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- card-body -->
          <div class="card-footer">
            <!-- pagination -->
            <div class="overflow-auto">
              <b-pagination
                size="md"
                first-text="First"
                prev-text="Prev"
                next-text="Next"
                last-text="Last"
                :total-rows="totalItems"
                v-model="currentPage"
                :per-page="perPage"
                align="center"
              ></b-pagination>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import GoBack from "../GoBack.vue";
import { BPagination } from "bootstrap-vue";
import { required, minLength, maxLength } from "vuelidate/lib/validators";
export default {
  components: {
    "b-pagination": BPagination,
    GoBack,
  },
  props: {
    baseUrl: {
      type: String,
    },
  },
  data() {
    return {
      currentPage: 1,
      perPage: 10,
      totalItems: 50,
      results: {},
      submitted: false,
      page: "rekening",
      endpoint: "/api/base/rekenings",
      form: new Form({
        id: "",
        name: "",
        rekening: "",
        number: "",
      }),
    };
  },
  validations: {
    form: {
      name: {
        required,
        minLength: minLength(5),
        maxLength: maxLength(30),
      },
      rekening: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(30),
      },
      number: {
        required,
        minLength: minLength(10),
        maxLength: maxLength(30),
      },
    },
  },
  mounted() {
    this.fetchData(1);
  },
  methods: {
    async showModalRekening(id) {
      $("#modalRekening").modal("show");
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
    async createRekening(id) {
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
              this.fetchData();
              $("#modalRekening").modal("hide");
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
              this.fetchData();
              $("#modalRekening").modal("hide");
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
    deleteRekening(id, name) {
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
              $("#modalRekening").modal("hide");
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
