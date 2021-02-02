<template>
  <!-- Begin Page Content -->
  <div class="container-fluid mb-5">
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
            <h2 class="lead text-dark mb-0">Alamat</h2>
          </div>
          <div class="card-body">
            <p class="text-muted">
              Deskripsi singkat ini ditampilkan pada footer
            </p>
            <form @submit.prevent="createAddress($event, '')">
              <div class="form-group">
                <label for="province">Propinsi</label>
                <multiselect
                  v-model="province"
                  :options="data_province"
                  placeholder="Select one"
                  label="name"
                  track-by="id"
                  :searchable="true"
                  :hide-selected="true"
                  :max-height="150"
                  :max="3"
                  @select="onSelectProvince"
                  :class="{
                    'is-invalid': submitted && $v.form.province_id.$error,
                    'is-valid': !$v.form.province_id.$invalid,
                  }"
                ></multiselect>

                <div class="valid-feedback">Propinsi is valid.</div>
                <div
                  v-if="submitted && !$v.form.province_id.required"
                  class="invalid-feedback"
                >
                  Propinsi harus diisi
                </div>
              </div>

              <div class="form-group">
                <label for="province">Kabupaten</label>
                <multiselect
                  v-model="city"
                  :options="data_city"
                  placeholder="Select one"
                  label="name"
                  track-by="id"
                  :searchable="true"
                  :hide-selected="true"
                  :max-height="150"
                  :max="3"
                  @select="onSelectCity"
                  :class="{
                    'is-invalid': submitted && $v.form.city_id.$error,
                    'is-valid': !$v.form.city_id.$invalid,
                  }"
                ></multiselect>

                <div class="valid-feedback">Kabupaten is valid.</div>
                <div
                  v-if="submitted && !$v.form.city_id.required"
                  class="invalid-feedback"
                >
                  Kabupaten harus diisi
                </div>
              </div>

              <div class="form-group">
                <label for="district">Kecamatan</label>
                <multiselect
                  v-model="district"
                  :options="data_district"
                  placeholder="Select one"
                  label="name"
                  track-by="id"
                  :searchable="true"
                  :hide-selected="true"
                  :max-height="150"
                  :max="3"
                  @select="onSelectDistrict"
                  :class="{
                    'is-invalid': submitted && $v.form.district_id.$error,
                    'is-valid': !$v.form.district_id.$invalid,
                  }"
                ></multiselect>
                <div class="valid-feedback">Kecamatan is valid.</div>
                <div
                  v-if="submitted && !$v.form.district_id.required"
                  class="invalid-feedback"
                >
                  Kecamatan harus diisi
                </div>
              </div>

              <div class="form-group">
                <label for="address">Alamat (Desa / Kelurahan)</label>
                <input
                  v-model="form.address"
                  type="text"
                  id="address"
                  name="address"
                  placeholder="Address"
                  class="form-control"
                  :class="{
                    'is-invalid': submitted && $v.form.address.$error,

                    'is-valid': !$v.form.address.$invalid,
                  }"
                />
                <div class="valid-feedback">Address is valid.</div>
                <div
                  v-if="submitted && !$v.form.address.required"
                  class="invalid-feedback"
                >
                  Address harus diisi
                </div>
                <div
                  v-if="submitted && !$v.form.address.maxLength"
                  class="invalid-feedback"
                >
                  Address terlalu panjang ( maks :
                  {{ $v.form.address.$params.maxLength.max }} karakter )
                </div>
                <div
                  v-if="submitted && !$v.form.address.minLength"
                  class="invalid-feedback"
                >
                  Address terlalu pendek ( maks :
                  {{ $v.form.address.$params.minLength.min }} karakter )
                </div>
              </div>
              <button class="btn btn-primary">Edit Address</button>
            </form>
          </div>
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
      submitted: false,
      page: "Address",
      endpoint: "/api/base/create",
      form: new Form({
        province_id: "",
        city_id: "",
        district_id: "",
        address: "",
      }),
      province: { id: "", name: "" },
      data_province: [],
      city: { id: "", name: "" },
      data_city: [],
      district: { id: "", name: "" },
      data_district: [],
    };
  },
  validations: {
    form: {
      province_id: {
        required,
      },
      city_id: {
        required,
      },
      district_id: {
        required,
      },
      address: {
        required,
        minLength: minLength(10),
        maxLength: maxLength(100),
      },
    },
  },
  methods: {
    async fetchData() {
      const self = this;

      // get exist data
      axios.get("/api/base/show-description").then(({ data }) => {
        self.form = data;
        self.form.province_id = data.province_id;
      });

      // get exist data area-dropdown
      axios
        .get("/api/list-province")
        .then(({ data }) => {
          if (data.success) {
            self.data_province = data.data.data.results;

            self.province = _.find(self.data_province, function (obj) {
              return obj.id == self.form.province_id;
            });

            self.getDataCity();
            self.getDataDistrict();

          } else {
            Swal.fire("Failed !", data.message, "error");
          }
        })
        .catch((error) => {
          let errMsg = "";
          if (typeof error.response.data === "object") {
            errMsg = _.flatten(_.toArray(error.response.data.errors));
          } else {
            errMsg = ["Something went wrong. Please try again."];
          }
          Swal.fire("Failed show data !", errMsg.join(""), "error");
        });
    },
    async getDataCity () {
      const self = this
      await axios
        .get("/api/list-city/" + self.form.province_id)
        .then(({ data }) => {
          if (data.success) {
            self.data_city = data.data.data.results;
            self.city = _.find(self.data_city, function (obj) {
              return obj.id == self.form.city_id;
            });

          } else {
            Swal.fire("Failed !", data.message, "error");
          }
        })
        .catch((error) => {
          let errMsg = "";
          if (typeof error.response.data === "object") {
            errMsg = _.flatten(_.toArray(error.response.data.errors));
          } else {
            errMsg = ["Something went wrong. Please try again."];
          }
          Swal.fire("Failed show data !", errMsg.join(""), "error");
        });
    },

    async getDataDistrict () {
      const self = this
      await axios
        .get("/api/list-district/" + self.form.city_id)
        .then(({ data }) => {
          if (data.success) {
            self.data_district = data.data.data.results;
            self.district = _.find(self.data_district, function (obj) {
              return obj.id == self.form.district_id;
            });

          } else {
            Swal.fire("Failed !", data.message, "error");
          }
        })
        .catch((error) => {
          let errMsg = "";
          if (typeof error.response.data === "object") {
            errMsg = _.flatten(_.toArray(error.response.data.errors));
          } else {
            errMsg = ["Something went wrong. Please try again."];
          }
          Swal.fire("Failed show data !", errMsg.join(""), "error");
        });
    },
    async onSelectProvince(option) {
      const self = this;
      self.form.province_id = option.id;

      // clear city & district
      self.city = { id: "", name: "" };
      self.data_city = [];
      self.form.city_id = "";

      self.district = { id: "", name: "" };
      self.data_district = [];
      self.form.district_id = "";

      // select city
      axios
        .get("/api/list-city/" + option.id)
        .then(({ data }) => {
          if (data.success) {
            self.data_city = data.data.data.results;
          } else {
            Swal.fire("Failed !", data.message, "error");
          }
        })
        .catch((error) => {
          let errMsg = "";
          if (typeof error.response.data === "object") {
            errMsg = _.flatten(_.toArray(error.response.data.errors));
          } else {
            errMsg = ["Something went wrong. Please try again."];
          }
          Swal.fire("Failed show data !", errMsg.join(""), "error");
        });
    },
    async onSelectCity(option) {
      const self = this;
      self.form.city_id = option.id;

      // clear district
      self.district = { id: "", name: "" };
      self.data_district = [];
      self.form.district_id = "";

      // select district
      axios
        .get("/api/list-district/" + option.id)
        .then(({ data }) => {
          if (data.success) {
            self.data_district = data.data.data.results;
          } else {
            Swal.fire("Failed !", data.message, "error");
          }
        })
        .catch((error) => {
          let errMsg = "";
          if (typeof error.response.data === "object") {
            errMsg = _.flatten(_.toArray(error.response.data.errors));
          } else {
            errMsg = ["Something went wrong. Please try again."];
          }
          Swal.fire("Failed show data !", errMsg.join(""), "error");
        });
    },
    async onSelectDistrict(option) {
      const self = this;
      self.form.district_id = option.id;
    },
    createAddress() {
      const self = this;
      this.submitted = true;
      this.$v.$touch();
      if (this.$v.$error) {
        return;
      } else {
        alert(JSON.stringify(self.form))
        axios
          .post(self.baseURL + self.endpoint,self.form)
          .then(({ data }) => {
            if (data.success) {
              Swal.fire(data.process + "!", data.message, "success");
            } else {
              Swal.fire(data.process + "!", data.message, "error");
            }
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
      }
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>