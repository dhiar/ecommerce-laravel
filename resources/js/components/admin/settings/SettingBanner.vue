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
            <setting-menu :baseUrl="$parent.baseURL"></setting-menu>
          </div>
        </div>
      </div>

      <div class="col-md-9">
        <div class="card shadow">
          <div class="card-header">
            <h2 class="lead text-dark mb-0">Banner Slider</h2>
          </div>
          <div class="card-body">
            <form
              @submit.prevent="createSlides(form.id)"
              method="post"
              enctype="multipart/form-data"
            >
              <div class="form-group">
                <label for="image">Gambar Banner</label>
                <el-upload
                  :action="baseUrl + '/api/upload'"
                  style="
                    border-style: dashed;
                    border-width: 1px;
                    border-color: gray;
                    width: 50%;
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
                  />
                  <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                </el-upload>
                <small class="text-muted"
                  >Pastikan gambar berukuran maksimal 2mb, berformat png, jpg,
                  jpeg. Dan berukuran 1600x400px</small
                >
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
              <button type="submit" class="btn btn-primary">Tambah</button>
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
      baseUrl: process.env.MIX_APP_URL,
      results: [],
      submitted: false,
      page: 'slide',
      endpoint: '/api/base/slides',
      form: new Form({
        id: "",
        title: "",
        url: "#",
        image: "", // path image
        storage_path_image: "",
        active: true
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
      image: {
        required
      },
      url: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(50),
      }
    },
  },
  mounted() {},
  methods: {
    handleBannerSuccess(res, file) {
      this.form.storage_path_image = res.result;
      this.form.image = URL.createObjectURL(file.raw);

      console.log(
        "storage_path_url = " + this.form.storage_path_image
      );
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
    async createSlides(id) {
      this.submitted = true;
      const self = this

      this.$v.$touch();
      if (this.$v.$error) {
        return;
      } else {
        if (id) {
          console.log('update')
        }
        else {
          await axios
            .post(self.endpoint, this.form)
            .then(({ data }) => {
              if (data.success) {
                Swal.fire("Success !", data.message, "success");
              } else {
                Swal.fire("Failed !", data.message, "error");
              }
              // $("#modalRekening").modal("hide");
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


