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
                      Price fee harus diisi
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
  betwween,
  email,
  numeric,
  sameAs,
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
      conditions: [
        { id: "New", label: "New" },
        { id: "Second", label: "Second" },
      ],
      form: new Form({
        id: "",
        id_product_category: "",
        id_admin: "",
        name: "",
        image: "",
        price: "0",
        weight: "0",
        stock: "0",
        condition: "New",
        description: "",
        is_published: "",
        slug: "",
        transaction: "0",
        promo_price: "0",
        viewer: "",
      }),
    };
  },
  validations: {
    //         $table->integer('weight')->default(0);
    //         $table->integer('stock')->default(0);
    //         $table->enum('condition', ['New', 'Second'])->default('New');
    //         $table->text('description');
    //         // 1 is publish, 0 is draft
    //         $table->enum('is_published', ['0', '1'])->default('1');
    //         $table->string('slug', 100)->unique();
    //         $table->integer('transaction')->default(0);
    //         $table->integer('promo_price')->default(0);
    //         $table->integer('viewer')->default(0);
    form: {
      name: {
        required,
        minLength: minLength(5),
        maxLength: maxLength(100),
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
    async fetchData() {
      let productId = this.$route.params.id;
      const self = this;
      this.submitted = false;

      alert("productId = " + productId);

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
      }

      // if (sponsorId) {
      //   await axios
      //   .get("/api/admin/"+this.endpoint+"/" + sponsorId,
      //   {
      //     params: {
      //       token: localStorage.token,
      //     }
      //   })
      //   .then(({ data }) => {
      //     if (data.success) {
      //       let attributes = data.data.attributes;
      //       this.form = attributes;
      //       this.form.event_id = data.data.relationships.event.id
      //       this.event_id = data.data.relationships.event.id
      //     }
      //   })
      //   .catch((err, vm, info) => {
      //     Swal.fire(
      //       "Failed load "+ this.page +" edit page!",
      //       "Something went wrong. Please contact admin.",
      //       "warning"
      //     );
      //   });
      // }
    },
    async save(e, sponsorId = false) {
      this.submitted = true;
      // this.form.event_id = this.event_id

      // stop here if form is invalid
      this.$v.$touch();
      // if (this.$v.$error) {
      //   Swal.fire("Failed create "+this.page+" !", "All input is required.", "warning");
      //   return;
      // } else {
      //   if (sponsorId) {
      //     this.form.token = localStorage.token;
      //     axios
      //       .put(
      //         process.env.MIX_APP_URL + "/api/admin/"+this.endpoint+"/" + sponsorId,
      //         this.form
      //       )
      //       .then(({ data }) => {
      //         if (data.code == 201 || data.code == 200) {
      //           Swal.fire("Success!", "Success update "+this.endpoint+".", "success");
      //           this.$router.push({ path: `/admin/`+this.endpoint });
      //         } else {
      //           let errors = data.data.errors;
      //           let key = Object.keys(errors)[0];
      //           let errMsg = errors[key][0];
      //           Swal.fire("Failed update "+this.endpoint+"!", errMsg, "warning");
      //         }
      //       })
      //       .catch((err, vm, info) => {
      //         Swal.fire(
      //           "Failed update "+this.endpoint+"!",
      //           "Something went wrong. Please contact admin.",
      //           "warning"
      //         );
      //       });
      //   } else {
      //     // Create
      //     await this.form
      //       .post(process.env.MIX_APP_URL + "/api/admin/"+this.endpoint)
      //       .then(({ data }) => {
      //         if (data.code == 201 || data.success) {
      //           Swal.fire("Success!", "Success create "+this.page+".", "success");
      //           this.$router.push({ path: `/admin/`+this.endpoint });
      //         } else {
      //           let errors = data.data.errors;
      //           let key = Object.keys(errors)[0];
      //           let errMsg = errors[key][0];
      //           Swal.fire("Failed create "+this.page+" !", errMsg, "warning");
      //         }
      //       })
      //       .catch((err, vm, info) => {
      //         Swal.fire(
      //           "Failed create "+this.page+"!",
      //           "Something went wrong. Please contact admin.",
      //           "warning"
      //         );
      //       });
      //   }
      // }
    },
  },
};
</script>
