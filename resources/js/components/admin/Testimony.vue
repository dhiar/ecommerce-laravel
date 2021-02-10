<template>
  <!-- Begin Page Content -->
  <div class="container-fluid mb-5">
    <div
      class="modal fade"
      id="modalTestimony"
      tabindex="-1"
      aria-labelledby="addNewLabel"
      aria-hidden="true"
      style="width: 100%"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <div class="h5 text-gray-800 line-height-222">
              {{ form.id ? "Update " : "Tambah" }} Testimony
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

          <form @submit.prevent="createAdminTestimony(form.id)">
            <div class="modal-body">
              <div class="form-group">
                <textarea
                  v-model="form.content"
                  name="content"
                  id="content"
                  class="form-control"
                  rows="5"
                  :class="{
                    'is-invalid': submitted && $v.form.content.$error,

                    'is-valid': !$v.form.content.$invalid,
                  }"
                ></textarea>
                <div class="valid-feedback">Content is valid.</div>
                <div
                  v-if="submitted && !$v.form.content.required"
                  class="invalid-feedback"
                >
                  Content harus diisi
                </div>
                <div
                  v-if="submitted && !$v.form.content.maxLength"
                  class="invalid-feedback"
                >
                  Content terlalu panjang ( maks :
                  {{ $v.form.content.$params.maxLength.max }} karakter )
                </div>
                <div
                  v-if="submitted && !$v.form.content.minLength"
                  class="invalid-feedback"
                >
                  Content terlalu pendek ( min :
                  {{ $v.form.content.$params.minLength.min }} karakter )
                </div>
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

    <go-back></go-back><br />
    <!-- Page Heading -->

    <div class="card shadow">
      <div class="card-header">
        <h2 class="lead text-dark mb-0">Testimony</h2>
      </div>
      <div class="card-body table-responsive">
        <button @click="showModalTestimony()" class="btn btn-primary">
          Tambah Testimony
        </button>
      </div>
      <table class="table table-hover">
        <thead>
          <tr>
            <th class="text-center" style="width: 8% !important">No</th>
            <th style="width: 40% !important">Content</th>
            <th style="width: 20% !important">Created By</th>
            <th style="width: 20% !important">Created At</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, idx) in results.data" :key="item.id">
            <td class="text-center">{{ getNumber(currentPage, idx) }}</td>
            <td>{{ item.content }}</td>
            <td>{{ item.relationships.user.name }}</td>
            <td>{{ item.created_at | myDate }}</td>
            <td>
              <a
                class="btn btn-sm btn-danger"
                href="#"
                @click="deleteTestimony(item.id, item.id)"
                ><i class="fa fa-trash-alt text-gray-100"></i
              ></a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import GoBack from "./GoBack.vue";
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
      page: "testimony",
      endpoint: "/api/testimony",
      form: new Form({
        id: "",
        content: "",
        relationships: [],
      }),
    };
  },
  validations: {
    form: {
      content: {
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
    async showModalTestimony(id) {
      this.submitted = false;
      $("#modalTestimony").modal("show");
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
          self.clearForm(self.form);
        }
      }
    },
    async createAdminTestimony(id) {
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
              $("#modalTestimony").modal("hide");
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
              $("#modalTestimony").modal("hide");
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
    deleteTestimony(id, name) {
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
              $("#modalTestimony").modal("hide");
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