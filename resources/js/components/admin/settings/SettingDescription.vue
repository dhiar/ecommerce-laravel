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
            <h2 class="lead text-dark mb-0">Deskripsi Singkat</h2>
          </div>
          <div class="card-body">
            <p class="text-muted">
              Deskripsi singkat ini ditampilkan pada footer
            </p>
            <form @submit.prevent="createDescription($event, '')">
              <div class="form-group">
                <textarea
                  v-model="description"
                  name="description"
                  id="desc"
                  class="form-control"
                  rows="5"
                  :class="{
                    'is-invalid': submitted && $v.description.$error,

                    'is-valid': !$v.description.$invalid,
                  }"
                ></textarea>
                <div class="valid-feedback">Description is valid.</div>
                <div
                  v-if="submitted && !$v.description.required"
                  class="invalid-feedback"
                >
                  Description harus diisi
                </div>
                <div
                  v-if="submitted && !$v.description.maxLength"
                  class="invalid-feedback"
                >
                  Description terlalu panjang ( maks :
                  {{ $v.description.$params.maxLength.max }} karakter )
                </div>
                <div
                  v-if="submitted && !$v.description.minLength"
                  class="invalid-feedback"
                >
                  Description terlalu pendek ( min :
                  {{ $v.description.$params.minLength.min }} karakter )
                </div>
              </div>
              <button class="btn btn-primary">Edit Deskripsi</button>
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
      description: "",
    };
  },
  validations: {
    description: {
      required,
      minLength: minLength(5),
      maxLength: maxLength(300),
    },
  },
  methods: {
    async createDescription() {
      this.submitted = true;
      this.$v.$touch();
      if (this.$v.$error) {
        return;
      } else {
        await axios
          .post("/api/base/create", {
            description: this.description,
          })
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
    fetchData() {
      axios.get("/api/base/show-description").then(({ data }) => {
        if (data.id) {
          this.description = data.description;
        }
      });
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>
