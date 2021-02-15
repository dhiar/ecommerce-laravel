<template>
  <div class="container">
    <go-back></go-back>
    <div class="row mt-5">
      <div class="col-md-12">
        <div class="card">
          <!-- {{form}} <br>
          $route.params.id = {{$route.params.id}} -->
          <div class="card-header">
            <h3 v-if="$route.params.id" class="card-title">Edit Product</h3>
            <h3 v-else class="card-title">Create Product</h3>
          </div>

          <form @submit.prevent="save($event, $route.params.id)">
            <div class="card-body" style="padding: 20px">
              <div class="row">
                <div class="col-12 col-md-6 col-sm-6 themed-grid-col">
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
                      placeholder="slug"
                      class="form-control"
                      :class="{
                        'is-invalid': submitted && $v.form.slug.$error,

                        'is-valid': !$v.form.slug.$invalid,
                      }"
                    />
                    <div class="valid-feedback">Slug is valid.</div>
                    <div
                      v-if="submitted && !$v.form.slug.required"
                      class="invalid-feedback"
                    >
                      Slug harus diisi
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="stock">Stock</label>
                    <input
                      type="text"
                      v-model="form.stock"
                      id="stock"
                      name="stock"
                      class="form-control"
                      :class="{
                        'is-invalid': submitted && $v.form.stock.$error,
                        'is-valid': !$v.form.stock.$invalid,
                      }"
                    />
                    <div class="valid-feedback">Stock is valid.</div>
                    <div
                      v-if="submitted && !$v.form.stock.required"
                      class="invalid-feedback"
                    >
                      Stock harus diisi
                    </div>
                    <div
                      v-if="submitted && !$v.form.stock.maxLength"
                      class="invalid-feedback"
                    >
                      Input stock terlalu panjang ( maks :
                      {{ $v.form.stock.$params.maxLength.max }}
                      karakter )
                    </div>
                    <div
                      v-if="submitted && !$v.form.stock.minLength"
                      class="invalid-feedback"
                    >
                      Input stock terlalu pendek ( min :
                      {{ $v.form.stock.$params.minLength.min }}
                      karakter )
                    </div>
                    <div
                      v-if="submitted && !$v.form.stock.numeric"
                      class="invalid-feedback"
                    >
                      Input stock harus berupa angka
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Condition</label><br />
                    <el-select
                      v-model="form.condition"
                      filterable
                      remote
                      reserve-keyword
                      placeholder="Please select category"
                      style="width: 100%"
                      :class="{
                        'is-invalid': submitted && $v.form.condition.$error,
                        'is-valid': !$v.form.condition.$invalid,
                      }"
                    >
                      <el-option
                        v-for="item in conditions"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id"
                      >
                      </el-option>
                    </el-select>
                    <div class="valid-feedback">Condition is valid.</div>
                    <div
                      v-if="submitted && !$v.form.price.required"
                      class="invalid-feedback"
                    >
                      Condition harus diisi
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="image">Main Image</label>
                    <el-upload
                      :action="baseURL + '/api/upload'"
                      style="
                        border-style: dashed;
                        border-width: 1px;
                        border-color: gray;
                        width: 100%;
                      "
                      class="img-fluid text-center"
                      :show-file-list="false"
                      :on-success="handleImageSuccess"
                      :before-upload="beforeImageUpload"
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
                      >Pastikan gambar berukuran maksimal 2mb, berformat png,
                      jpg, jpeg. Dan berukuran 1600x400px</small
                    >
                    <div class="valid-feedback">Image is valid.</div>
                    <div
                      v-if="submitted && !$v.form.image.required"
                      class="invalid-feedback"
                    >
                      Image harus diisi
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-6 col-sm-6 themed-grid-col">
                  <div class="form-group">
                    <label for="price">Price</label>
                    <input
                      type="text"
                      v-model="displayPrice"
                      id="displayPrice"
                      name="displayPrice"
                      class="form-control"
                      @blur="isInputActive = false"
                      @focus="isInputActive = true"
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
                      Input price terlalu panjang ( maks :
                      {{ $v.form.price.$params.maxLength.max }}
                      karakter )
                    </div>
                    <div
                      v-if="submitted && !$v.form.price.minLength"
                      class="invalid-feedback"
                    >
                      Input price terlalu pendek ( min :
                      {{ $v.form.price.$params.minLength.min }}
                      karakter )
                    </div>
                    <div
                      v-if="submitted && !$v.form.price.numeric"
                      class="invalid-feedback"
                    >
                      Input price harus berupa angka
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Product Category</label><br />
                    <el-select
                      v-model="form.id_product_category"
                      filterable
                      remote
                      reserve-keyword
                      placeholder="Please select category"
                      style="width: 100%"
                      :class="{
                        'is-invalid':
                          submitted && $v.form.id_product_category.$error,
                        'is-valid': !$v.form.id_product_category.$invalid,
                      }"
                    >
                      <el-option
                        v-for="item in categories.data"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id"
                      >
                      </el-option>
                    </el-select>
                    <div class="valid-feedback">Category is valid.</div>
                    <div
                      v-if="submitted && !$v.form.id_product_category.required"
                      class="invalid-feedback"
                    >
                      Category harus diisi
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="weight">Weight</label>
                    <input
                      type="text"
                      v-model="form.weight"
                      id="weight"
                      name="weight"
                      class="form-control"
                      :class="{
                        'is-invalid': submitted && $v.form.weight.$error,
                        'is-valid': !$v.form.weight.$invalid,
                      }"
                    />
                    <small class="text-muted"
                      >Pastikan berat dalam satuan gram</small
                    >
                    <div class="valid-feedback">Weight is valid.</div>
                    <div
                      v-if="submitted && !$v.form.weight.required"
                      class="invalid-feedback"
                    >
                      Weight harus diisi
                    </div>
                    <div
                      v-if="submitted && !$v.form.weight.maxLength"
                      class="invalid-feedback"
                    >
                      Input weight terlalu panjang ( maks :
                      {{ $v.form.weight.$params.maxLength.max }}
                      karakter )
                    </div>
                    <div
                      v-if="submitted && !$v.form.weight.minLength"
                      class="invalid-feedback"
                    >
                      Input weight terlalu pendek ( min :
                      {{ $v.form.weight.$params.minLength.min }}
                      karakter )
                    </div>
                    <div
                      v-if="submitted && !$v.form.weight.numeric"
                      class="invalid-feedback"
                    >
                      Input weight harus berupa angka
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Status Publish</label><br />
                    <el-select
                      v-model="form.is_published"
                      filterable
                      remote
                      reserve-keyword
                      placeholder="Please select is publish"
                      style="width: 100%"
                      :class="{
                        'is-invalid': submitted && $v.form.is_published.$error,
                        'is-valid': !$v.form.is_published.$invalid,
                      }"
                    >
                      <el-option
                        v-for="item in publish"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id"
                      >
                      </el-option>
                    </el-select>
                    <div class="valid-feedback">Status publish is valid.</div>
                    <div
                      v-if="submitted && !$v.form.is_published.required"
                      class="invalid-feedback"
                    >
                      Status publish harus diisi
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="description">Description</label>
                    <editor ref="tuiEditor" />
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer text-right">
              <div class="card-tools">
                <button class="btn btn-success" type="submit">
                  Save <i class="fas fa-save fa-fw"></i>
                </button>
              </div>
            </div>
          </form>
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
      page: "product",
      endpoint: "/api/products",
      endpoint_category: "/api/product_category",
      conditions: [
        { id: "New", name: "New" },
        { id: "Second", name: "Second" },
      ],
      publish: [
        { id: "0", name: "Draft" },
        { id: "1", name: "Publish" },
      ],
      categories: {},
      form: new Form({
        id: "",
        id_product_category: "1n",
        id_admin: "",
        name: "Madu Hutan NTT (Bunga Kayu Putih)",
        image: "",
        storage_path_image: "",
        price: "125000",
        weight: "800",
        stock: "200",
        condition: "New",
        description: "Description madu hutan NTT",
        is_published: "1",
        slug: "",
        transaction: "123",
        promo_price: "120000",
        viewer: "321",

        // id_product_category: "",
        // id_admin: "",
        // name: "",
        // image: "",
        // storage_path_image: "",
        // price: "0",
        // weight: "0",
        // stock: "0",
        // condition: "New",
        // description: "",
        // is_published: "1",
        // slug: "",
        // transaction: "0",
        // promo_price: "0",
        // viewer: "0",
      }),
    };
  },
  validations: {
    form: {
      id_product_category: {
        required,
      },
      condition: {
        required,
      },
      is_published: {
        required,
      },
      name: {
        required,
        minLength: minLength(5),
        maxLength: maxLength(100),
      },
      slug: {
        required
      },
      image: {
        required,
        minLength: minLength(5),
        maxLength: maxLength(100),
      },
      price: {
        required,
        minLength: minLength(4),
        maxLength: maxLength(9),
        numeric,
      },
      stock: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(4),
        numeric,
      },
      weight: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(4),
        numeric,
      },
    },
  },
  mounted() {
    this.fetchData();
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
  methods: {
    inputSlug() {
      const self = this;
      self.form.slug = self.sanitizeTitle(self.form.name);
    },
    async fetchData() {
      let productId = this.$route.params.id;
      const self = this;
      this.submitted = false;

      if (productId) {
        this.form.id = productId;
        const result = await axios
          .get(self.endpoint + "/" + productId)
          .catch((error) => {
            let errMsg = "";
            if (typeof error.response.data === "object") {
              errMsg = _.flatten(_.toArray(error.response.data.errors));
            } else {
              errMsg = ["Something went wrong. Please try again."];
            }
            Swal.fire("Failed load data !", errMsg.join(""), "error");
          });

        console.log("result = " + JSON.stringify(result));

        this.form = result.data;
        this.$refs.tuiEditor.invoke("setHtml", this.form.description);
      } else {
        this.$refs.tuiEditor.invoke("setHtml", null);
      }

      // get category
      const result = await axios.get(self.endpoint_category).catch((error) => {
        let errMsg = "";
        if (typeof error.response.data === "object") {
          errMsg = _.flatten(_.toArray(error.response.data.errors));
        } else {
          errMsg = ["Something went wrong. Please try again."];
        }
        Swal.fire("Failed load data !", errMsg.join(""), "error");
      });

      this.categories = result.data;
    },
    handleImageSuccess(res, file) {
      this.form.storage_path_image = res.result;
      this.form.image = URL.createObjectURL(file.raw);
    },
    beforeImageUpload(file) {
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
    async save(e, id = false) {
      this.submitted = true;
      const self = this

      // return error if empty content
      let description = this.$refs.tuiEditor.invoke("getHtml");

      if (!description) {
        Swal.fire("Failed !", "Description must be filled", "error");
      } else {
        self.form.description = description;
      }

      // stop here if form is invalid
      this.$v.$touch();
      if (this.$v.$error) {
        Swal.fire(
          "Failed create " + this.page + " !",
          "All input is required.",
          "warning"
        );
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
          // Create
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
      }
    },
  },
};
</script>
