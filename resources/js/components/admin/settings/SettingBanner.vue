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
              @submit.prevent="createSlides($event, '')"
              method="post"
              enctype="multipart/form-data"
            >
              <div class="form-group">
                <div class="form-group">
                  <label for="banner_image">Gambar Banner</label>
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
                  >
                      <img
                        v-if="form.banner_image"
                        :src="form.banner_image"
                        class="img-fluid text-center"
                        :on-success="handleBannerSuccess"
                        :before-upload="beforeBannerUpload"
                      />
                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                  </el-upload>
                </div>
                <small class="text-muted"
                  >Pastikan gambar berukuran maksimal 2mb, berformat png, jpg,
                  jpeg. Dan berukuran 1600x400px</small
                >
              </div>
              <div class="form-group">
                <label for="url">URL (opsional)</label>
                <input
                  type="text"
                  class="form-control"
                  name="url"
                  autocomplete="off"
                  id="url"
                />
                <small class="text-muted"
                  >Jika banner di klik maka akan mengarah ke link/url diatas.
                  Misal: https://domain.com/p/produk-keren</small
                >
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

export default {
  components: {
    GoBack,
  },
  data() {
    return {
      baseUrl: process.env.MIX_APP_URL,
      form: new Form({
        image_banner: "",
        url_banner: "",
        banner_image: "",
        storage_path_banner_image: "",
      }),
    };
  },
  mounted() {
  },
  methods: {
    handleBannerSuccess(res, file) {
      this.form.storage_path_banner_image = res.result;
      this.form.banner_image = URL.createObjectURL(file.raw);
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
    }
  }
};
</script>


