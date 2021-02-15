<template>
  <!-- Begin Page Content -->
  <div class="container-fluid mb-5">
    <div
      class="modal fade"
      id="modalPage"
      tabindex="-1"
      aria-labelledby="addNewLabel"
      aria-hidden="true"
      style="width: 100%"
    >
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <div class="h5 text-gray-800 line-height-222">{{ form.id ? 'Update ' : 'Tambah' }} Halaman</div>
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

          <form @submit.prevent="createPage(form.id)">
            <div class="modal-body">
              <div class="form-group">
                <label for="title">Title</label>
                <input
                  v-model="form.title"
                  type="text"
                  id="title"
                  name="title"
                  placeholder="title"
                  class="form-control"
                  @input="inputSlug()"
                  :class="{
                    'is-invalid': submitted && $v.form.title.$error,

                    'is-valid': !$v.form.title.$invalid,
                  }"
                />
                <div class="valid-feedback">Title is valid.</div>
                <div
                  v-if="submitted && !$v.form.title.required"
                  class="invalid-feedback"
                >
                  Title harus diisi
                </div>
                <div
                  v-if="submitted && !$v.form.title.maxLength"
                  class="invalid-feedback"
                >
                  Title terlalu panjang ( maks :
                  {{ $v.form.title.$params.maxLength.max }} karakter )
                </div>
                <div
                  v-if="submitted && !$v.form.title.minLength"
                  class="invalid-feedback"
                >
                  Title terlalu pendek ( maks :
                  {{ $v.form.title.$params.minLength.min }} karakter )
                </div>
              </div>
              <div class="form-group">
                <label for="slug">Slug</label>
                <input
                  v-model="form.slug"
                  type="text"
                  id="slug"
                  name="slug"
                  placeholder="slug"
                  class="form-control"
                  :class="{
                    'is-invalid': submitted && $v.form.slug.$error,

                    'is-valid': !$v.form.slug.$invalid,
                  }"
                />
                <small class="text-muted"
                  >Gunakan tanda - jika lebih dari 1 kata. Contoh:
                  about-us</small
                >
                <div class="valid-feedback">Slug is valid.</div>
                <div
                  v-if="submitted && !$v.form.slug.required"
                  class="invalid-feedback"
                >
                  Slug harus diisi
                </div>
                <div
                  v-if="submitted && !$v.form.slug.maxLength"
                  class="invalid-feedback"
                >
                  Slug terlalu panjang ( maks :
                  {{ $v.form.slug.$params.maxLength.max }} karakter )
                </div>
                <div
                  v-if="submitted && !$v.form.slug.minLength"
                  class="invalid-feedback"
                >
                  Slug terlalu pendek ( maks :
                  {{ $v.form.slug.$params.minLength.min }} karakter )
                </div>
              </div>
              <div class="form-group">
                <label for="content">Content</label>
                <editor ref="tuiEditor" />
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

    <h1 class="h3 mb-2 text-gray-800 mb-4">Halaman</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3 right-align">
        <button @click="showModalPage()" class="btn btn-primary">Tambah</button>
      </div>
      <div class="card-body table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th class="text-center" style="width: 10% !important">No</th>
              <th style="width: 40% !important">Title</th>
              <th style="width: 35% !important">Slug</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, idx) in results.data" :key="item.id">
              <td class="text-center">{{ getNumber(currentPage, idx) }}</td>
              <td>{{ item.title }}</td>
              <td>{{ item.slug }}</td>
              <td>
                <a
                  class="btn btn-sm btn-info"
                  href="#"
                  @click="showModalPage(item.id)"
                  ><i class="fa fa-pen text-gray-100"></i
                ></a>
                <a
                  class="btn btn-sm btn-danger"
                  href="#"
                  @click="deletePage(item.id, item.title)"
                  ><i class="fa fa-trash-alt text-gray-100"></i
                ></a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>

import GoBack from "./GoBack.vue";
import { required, minLength, maxLength } from "vuelidate/lib/validators";

export default {
  components: {
    GoBack
  },
  data() {
    return {
      currentPage: 1,
      perPage: 10,
      totalItems: 50,
      results: {},
      submitted: false,
      page: "page",
      endpoint: "/api/pages",
      form: new Form({
        id: "",
        title: "",
        content: "",
        slug: "",
      }),
    };
  },
  validations: {
    form: {
      title: {
        required,
        minLength: minLength(5),
        maxLength: maxLength(30),
      },
      slug: {
        required,
        minLength: minLength(5),
        maxLength: maxLength(25),
      },
    },
  },
  mounted() {
    this.fetchData(1);
  },
  methods: {
    inputSlug() {
      const self = this;
      self.form.slug = self.sanitizeTitle(self.form.title);
    },
    async showModalPage(id) {
      this.submitted = false;
      $("#modalPage").modal("show");
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
        this.$refs.tuiEditor.invoke("setHtml", this.form.content);
      } else {
        // clear form
        if (!this.submitted) {
          self.clearForm(self.form)
        }
        this.$refs.tuiEditor.invoke("setHtml", null);
      }
    },
    async createPage(id) {
      this.submitted = true;
      const self = this;

      // return error if empty content
      let content = this.$refs.tuiEditor.invoke("getHtml");

      if (!content) {
        Swal.fire("Failed !", "Content must be filled", "error");
      } else {
        self.form.content = content;
      }

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
              $("#modalPage").modal("hide");
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
              $("#modalPage").modal("hide");
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

        this.submitted = false;
      }
    },
    deletePage(id, name) {
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
              $("#modalPage").modal("hide");
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