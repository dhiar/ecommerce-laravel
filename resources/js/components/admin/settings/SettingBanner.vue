<template>
  <!-- Begin Page Content -->
  <div class="container-fluid mb-5">
    <div
      class="modal fade"
      id="modalBanner"
      tabindex="-1"
      aria-labelledby="addNewLabel"
      aria-hidden="true"
      style="width: 100%"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <div class="h5 text-gray-800 line-height-222">
              {{ form.id ? "Update " : "Tambah" }} Banner Slide
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

          <form @submit.prevent="createBanner(form.id)">
            <div class="modal-body">
              <div class="form-group">
                <label for="image">Banner</label>
                <el-upload
                  :action="baseURL + '/api/upload-slide'"
                  style="
                    border-style: dashed;
                    border-width: 1px;
                    border-color: gray;
                    width: 100%;
                  "
                  class="img-fluid text-center"
                  :show-file-list="false"
                  :on-success="handleBannerSuccess"
                  :before-upload="beforeBannerUpload"
                >
                  <img
                    v-if="form.image"
                    :src="form.image"
                    class="img-fluid text-center"
                    @error="imgErrorCondition"
                    :class="{
                      'is-invalid': submitted && $v.form.image.$error,

                      'is-valid': !$v.form.image.$invalid,
                    }"
                  />
                  <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                </el-upload>
                <small class="text-muted"
                  >Pastikan gambar berukuran maksimal 2mb, berformat png, jpg,
                  jpeg. Dan berukuran 1600x400px</small
                >
                <div class="valid-feedback">Image is valid.</div>
                <div
                  v-if="submitted && !$v.form.image.required"
                  class="invalid-feedback"
                >
                  Image harus diisi
                </div>
              </div>
              <div class="form-group">
                <label for="title">Title</label>
                <input
                  v-model="form.title"
                  type="text"
                  id="title"
                  name="title"
                  placeholder="Title"
                  class="form-control"
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
                <label for="id_product">Product</label>
                <multiselect
                  v-model="product"
                  :options="products"
                  placeholder="Select one"
                  label="name"
                  track-by="id"
                  :searchable="true"
                  :max-height="150"
                  :max="7"
                  @search-change="asyncFind"
                ></multiselect>
                <input
                  v-model="form.id_product"
                  type="hidden"
                  id="id_product"
                  name="id_product"
                  class="form-control"
                  :class="{
                    'is-invalid': submitted && $v.form.id_product.$error,

                    'is-valid': !$v.form.id_product.$invalid,
                  }"
                />
                <div class="valid-feedback">Product is valid.</div>
                <div
                  v-if="submitted && !$v.form.id_product.required"
                  class="invalid-feedback"
                >
                  Product harus dipilih
                </div>
              </div>

              <div class="form-group">
                <label for="description">Description</label>
                <editor
                  ref="tuiEditor"
                />
              </div>
              <div class="form-group">
                <label for="url">URL (opsional - defaut is '#')</label>
                <input
                  v-model="form.url"
                  type="text"
                  id="url"
                  name="url"
                  placeholder="Url"
                  class="form-control"
                  :class="{
                    'is-invalid': submitted && $v.form.url.$error,

                    'is-valid': !$v.form.url.$invalid,
                  }"
                />
                <small class="text-muted"
                  >Jika banner di klik maka akan mengarah ke link/url diatas.
                  Misal: https://domain.com/p/produk-keren</small
                >
                <div class="valid-feedback">Url is valid.</div>
                <div
                  v-if="submitted && !$v.form.url.required"
                  class="invalid-feedback"
                >
                  Url harus diisi
                </div>
                <div
                  v-if="submitted && !$v.form.url.maxLength"
                  class="invalid-feedback"
                >
                  Url terlalu panjang ( maks :
                  {{ $v.form.url.$params.maxLength.max }} karakter )
                </div>
                <div
                  v-if="submitted && !$v.form.url.minLength"
                  class="invalid-feedback"
                >
                  Url terlalu pendek ( maks :
                  {{ $v.form.url.$params.minLength.min }} karakter )
                </div>
              </div>
              <div class="form-group">
                <label>Is Active</label>
                <select
                  name="active"
                  v-model="form.active"
                  id="active"
                  class="form-control"
                >
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
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
            <h2 class="lead text-dark mb-0">Banner Slide</h2>
          </div>
          <div class="card-body table-responsive">
            <button @click="showModalBanner()" class="btn btn-primary">
              Tambah Banner
            </button>
          </div>
          <table class="table table-hover">
            <thead>
              <tr>
                <th class="text-center" style="width: 8% !important">No</th>
                <th style="width: 20% !important">Title</th>
                <th class="text-center" style="width: 23% !important">Image</th>
                <th style="width: 20% !important">Url</th>
                <th class="text-center" style="width: 10% !important">Active</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, idx) in results.data" :key="item.id">
                <td class="text-center">{{ getNumber(currentPage, idx) }}</td>
                <td>{{ item.title }}
                  <a
                  @click="cloneBanner(item.id)"
                  class="badge badge-success text-gray-100 btn"
                  >clone</a
                >
                </td>
                <td>
                  <img
                    :src="item.image"
                    @error="imgErrorCondition"
                    class="img-fluid"
                    style="max-height: 100px !important"
                  />
                </td>
                <td>{{ item.url }}</td>
                <td class="text-center">{{ item.active | yesNo }}</td>
                <td class="text-center">
                  <a
                    class="btn btn-sm btn-info"
                    href="#"
                    @click="showModalBanner(item.id)"
                    ><i class="fa fa-pen text-gray-100"></i
                  ></a>
                  <a
                    class="btn btn-sm btn-danger"
                    href="#"
                    @click="deleteBanner(item.id, item.title)"
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
      page: "banner",
      endpoint: "/api/base/slides",
      endpoint_clone: "/api/base/clone-slide",
      form: new Form({
        id: "",
        title: "",
        url: "#",
        description: "",
        image: "", // path image
        storage_path_image: "",
        active: "1",
        id_product: "",
      }),
      product: { id: "", name: "" },
      products: [],
    };
  },
  validations: {
    form: {
      title: {
        required,
        minLength: minLength(5),
        maxLength: maxLength(30),
      },
      image: {
        required,
      },
      id_product: {
        required,
      },
      url: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(50),
      },
    },
  },
  mounted() {
    this.fetchData(1);
  },
  methods: {
    async cloneBanner(id) {
      await axios
            .put(this.endpoint_clone + "/" + id)
            .then(({ data }) => {
              if (data.success) {
                Swal.fire("Success !", data.message, "success");
              } else {
                Swal.fire("Failed !", data.message, "error");
              }
              this.fetchData();
              $("#modalBanner").modal("hide");
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
    async showModalBanner(id) {
      this.submitted = false;
      const self = this;
      this.form.id = id;
      // list of products
      const products = await axios
        .get("/api/products", {
          params: {
            fieldOrder: "name",
            sort: "ASC",
          },
        })
        .catch((error) => {
          let errMsg = "";
          if (typeof error.response.data === "object") {
            errMsg = _.flatten(_.toArray(error.response.data.errors));
          } else {
            errMsg = ["Something went wrong. Please try again."];
          }
          Swal.fire("Failed load data !", errMsg.join(""), "error");
        });

      self.products = products.data.data;

      $("#modalBanner").modal("show");

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
        this.form.id_product = result.data.relationships.product.id;
        this.product = {
          id: result.data.relationships.product.id,
          name: result.data.relationships.product.name,
        };

        this.$refs.tuiEditor.invoke("setHtml", this.form.description);
      } else {
        // clear form
        if (!this.submitted) {
          this.$refs.tuiEditor.invoke("setHtml", null);
        }
      }
    },
    async asyncFind(query) {
      // list of products
      const products = await axios
        .get("/api/products?q=" + query, {
          params: {
            fieldOrder: "name",
            sort: "ASC",
          },
        })
        .catch((error) => {
          let errMsg = "";
          if (typeof error.response.data === "object") {
            errMsg = _.flatten(_.toArray(error.response.data.errors));
          } else {
            errMsg = ["Something went wrong. Please try again."];
          }
          Swal.fire("Failed load data !", errMsg.join(""), "error");
        });
      this.products = products.data.data;
    },
    handleBannerSuccess(res, file) {
      this.form.storage_path_image = res.result;
      this.form.image = URL.createObjectURL(file.raw);

      console.log("storage_path_url = " + this.form.storage_path_image);
      console.log("image = " + this.form.image);
    },
    beforeBannerUpload(file) {
      const isJPG = file.type === "image/jpeg";
      const isPNG = file.type === "image/png";

      if (!isJPG && !isPNG) {
        Swal.fire(
          "Oops...!",
          "Image picture must be JPG / PNG format!",
          "error"
        );
      }

      return isJPG || isPNG;
    },
    async createBanner(id) {
      this.submitted = true;
      const self = this;

      this.form.id_product = self.product.id ? self.product.id : null;

      this.$v.$touch();
      if (this.$v.$error) {
        return;
      } else {
        // return error if empty content
        let description = this.$refs.tuiEditor.invoke("getHtml");

        if (!description) {
          Swal.fire("Failed !", "Description must be filled", "error");
        } else {
          self.form.description = description;
        }
        if (id) {
          this.form.relationships = [];
          await axios
            .put(self.endpoint + "/" + id, self.form)
            .then(({ data }) => {
              if (data.success) {
                Swal.fire("Success !", data.message, "success");
              } else {
                Swal.fire("Failed !", data.message, "error");
              }
              this.fetchData();
              $("#modalBanner").modal("hide");
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
              $("#modalBanner").modal("hide");
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
      this.clearForm(this.form);
    },
    deleteBanner(id, name) {
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
              $("#modalBanner").modal("hide");
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


