<template>
  <!-- Begin Page Content -->
  <div class="container-fluid mb-5">
    <div
      class="modal fade"
      id="modalCategory"
      tabindex="-1"
      aria-labelledby="addNewLabel"
      aria-hidden="true"
      style="width: 100%"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <div class="h5 text-gray-800 line-height-222">
              {{ form.id ? "Update " : "Tambah" }} Category
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

          <form @submit.prevent="createAdminCategory(form.id)">
            <div class="modal-body">
              <div class="form-group">
                <label for="name">Name</label>
                <input
                  v-model="form.name"
                  type="text"
                  id="name"
                  name="name"
                  placeholder="name"
                  @input="inputSlug()"
                  class="form-control"
                  :class="{
                    'is-invalid': submitted && $v.form.name.$error,

                    'is-valid': !$v.form.name.$invalid,
                  }"
                />
                <div class="valid-feedback">Name is valid.</div>
                <div
                  v-if="submitted && !$v.form.name.required"
                  class="invalid-feedback"
                >
                  Name harus diisi
                </div>
                <div
                  v-if="submitted && !$v.form.name.maxLength"
                  class="invalid-feedback"
                >
                  Name terlalu panjang ( maks :
                  {{ $v.form.name.$params.maxLength.max }} karakter )
                </div>
                <div
                  v-if="submitted && !$v.form.name.minLength"
                  class="invalid-feedback"
                >
                  Name terlalu pendek ( min :
                  {{ $v.form.name.$params.minLength.min }} karakter )
                </div>
              </div>

              <div class="form-group">
                <label for="slug">Slug</label>
                <input
                  v-model="form.slug"
                  type="text"
                  id="slug"
                  name="slug"
                  class="form-control"
                  :class="{
                    'is-invalid': submitted && $v.form.slug.$error,

                    'is-valid': !$v.form.slug.$invalid,
                  }"
                  :readonly="true"
                />
                <div class="valid-feedback">Slug is valid.</div>
              </div>

              <div class="form-group">
                <label for="image">Icon</label>
                <el-upload
                  :action="baseURL + '/api/upload-category'"
                  style="
                    border-style: dashed;
                    border-width: 1px;
                    border-color: gray;
                    width: 100%;
                  "
                  class="img-fluid text-center"
                  :show-file-list="false"
                  :on-success="handleIconSuccess"
                  :before-upload="beforeIconUpload"
                >
                  <img
                    v-if="form.icon"
                    :src="form.icon"
                    class="img-fluid text-center"
                    @error="imgErrorCondition"
                    :class="{
                      'is-invalid': submitted && $v.form.icon.$error,

                      'is-valid': !$v.form.icon.$invalid,
                    }"
                  />
                  <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                </el-upload>
                <small class="text-muted"
                  >Pastikan gambar berukuran maksimal 2mb, berformat png, jpg,
                  jpeg. Dan berukuran 1600x400px</small
                >
                <div class="valid-feedback">Icon is valid.</div>
                <div
                  v-if="submitted && !$v.form.icon.required"
                  class="invalid-feedback"
                >
                  Icon harus diisi
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
        <div class="row">
          <div class="col-md-8 align-self-center">
            <h2 class="lead text-dark mb-0">Category</h2>
          </div>
          <div class="col-md-4 float-right text-right">
            <button @click="showModalCategory()" class="btn btn-primary">
              Tambah Category
            </button>
          </div>
        </div>
      </div>
      <div class="card-body table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th class="text-center" style="width: 8% !important">No</th>
              <th style="width: 25% !important">Category</th>
              <th style="width: 25% !important">Slug</th>
              <th class="text-center" style="width: 20% !important">Icon</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, idx) in results.data" :key="item.id">
              <td class="text-center">{{ getNumber(currentPage, idx) }}</td>
              <td>{{ item.name }} <br>
                <a
                  @click="cloneCategory(item.id)"
                  class="badge badge-primary text-gray-100 btn"
                  >clone</a
                >
              </td>
              <td>{{ item.slug }}</td>
              <td class="text-center">
                <img
                  :src="item.icon"
                  @error="imgErrorCondition"
                  class="img-fluid"
                  style="max-height: 100px !important"
                />
              </td>
              <td class="text-center">
                <a
                  class="btn btn-sm btn-info"
                  href="#"
                  @click="showModalCategory(item.id)"
                  ><i class="fa fa-pen text-gray-100"></i
                ></a>

                <a
                  class="btn btn-sm btn-danger"
                  href="#"
                  @click="deleteCategory(item.id, item.name)"
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
    GoBack,
  },
  data() {
    return {
      currentPage: 1,
      perPage: 10,
      totalItems: 50,
      results: {},
      submitted: false,
      page: "category",
      endpoint: "/api/product_category",
      endpoint_clone: "/api/clone-category",
      form: new Form({
        id: "",
        name: "",
        slug: "",
        icon: "",
        storage_path_icon: "",
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
      slug: {
        required,
        minLength: minLength(5),
        maxLength: maxLength(30),
      },
      icon: {
        required,
      },
    },
  },
  mounted() {
    this.fetchData(1);
  },
  methods: {
    inputSlug() {
      const self = this;
      self.form.slug = self.sanitizeTitle(self.form.name);
    },
    async showModalCategory(id) {
      this.submitted = false;
      $("#modalCategory").modal("show");
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
    handleIconSuccess(res, file) {
      this.form.storage_path_icon = res.result;
      this.form.icon = URL.createObjectURL(file.raw);
    },
    beforeIconUpload(file) {
      const isJPG = file.type === "image/jpeg";
      const isPNG = file.type === "image/png";

      if (!isJPG && !isPNG) {
        Swal.fire(
          "Oops...!",
          "Icon picture must be JPG / PNG format!",
          "error"
        );
      }

      return isJPG || isPNG;
    },
    async createAdminCategory(id) {
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
              $("#modalCategory").modal("hide");
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
              $("#modalCategory").modal("hide");
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
    deleteCategory(id, name) {
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
              $("#modalCategory").modal("hide");
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
    async cloneCategory(id) {
      await axios
        .put(this.endpoint_clone + "/" + id)
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