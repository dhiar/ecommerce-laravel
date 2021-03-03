<template>
  <div class="container">
    <go-back></go-back>
    <div class="row mt-5">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-10 align-self-center">
                <h3 class="lead text-dark mb-0">{{ form.relationships.product.name }}</h3>
              </div>
              <div class="col-md-2 float-right text-right">
                <button
                  class="btn btn-info text-gray-100"
                  type="button"
                  @click="prevPage()"
                >
                  <i class="fas fa-chevron-left"></i> Go Back
                </button>
              </div>
            </div>
          </div>

          <div class="card-body" style="padding: 20px">
            <div class="row">
              <div class="col-12 col-md-4 col-sm-4 themed-grid-col">
                <div class="card shadow">
                  <div class="card-header">
                    <h2 class="lead text-dark mb-0">Add Image</h2>
                  </div>
                  <div class="card-body">
                    <el-upload
                      :action="baseURL + '/api/upload'"
                      style="
                        border-style: dashed;
                        border-width: 1px;
                        border-color: gray;
                      "
                      class="img-fluid text-center"
                      :show-file-list="false"
                      :before-upload="beforeImagesUpload"
                      :on-success="handleImagesSuccess"
                    >
                      <i class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-8 col-sm-8 themed-grid-col">
                <form @submit.prevent="save($event, $route.params.id)">
                  <div class="card shadow">
                    <div class="card-header">
                      <h2 class="lead text-dark mb-0">Images</h2>
                    </div>
                    <div class="card-body">
                      <div v-for="(image, idx) in form.images" :key="idx">
                        <div class="row" style="margin-bottom:20px;">
                          <div
                            class="col-9 col-md-9 col-sm-9 themed-grid-col text-center"
                          >
                            <img
                              :src="image"
                              @error="imgErrorCondition"
                              style="width: 300px; height: 300px"
                            />
                          </div>
                          <div
                            class="col-3 col-md-3 col-sm-3 themed-grid-col my-auto"
                          >
                            <a
                              class="btn btn-sm btn-danger"
                              href="#"
                              @click="deleteImage(idx)"
                              ><i class="fa fa-trash-alt text-gray-100"></i
                            ></a>
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
                  </div>
                </form>
              </div>
            </div>
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
      page: "images",
      endpoint: "/api/product_images",
      form: new Form({
        id_product: "",
        images: [],
        storage_path_images: [],
        relationships: {
          product: {},
        },
      }),
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    handleImagesSuccess(res, file) {
      const self = this;

      self.form.storage_path_images.push(res.result);
      let image = URL.createObjectURL(file.raw);
      self.form.images.push(image);
    },
    beforeImagesUpload(file) {
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
    async fetchData() {
      let productId = this.$route.params.id;
      let endpoint_images = "/api/products/" + productId + "/images";

      if (productId) {
        this.form.id_product = productId;
        const result = await axios.get(endpoint_images).catch((error) => {
          let errMsg = "";
          if (typeof error.response.data === "object") {
            errMsg = _.flatten(_.toArray(error.response.data.errors));
          } else {
            errMsg = ["Something went wrong. Please try again."];
          }
          Swal.fire("Failed load data !", errMsg.join(""), "error");
        });
        
        let productImages = result.data.data
        if (productImages.length > 0) {
          this.form.images = []
          this.form.storage_path_images = []
          productImages.forEach(data => {
            this.form.images.push(data.image)
            this.form.storage_path_images.push(data.storage_path_image)
            this.form.relationships = data.relationships
          });
        } else {
          const product = await axios.get("/api/products").catch((error) => {
            let errMsg = "";
            if (typeof error.response.data === "object") {
              errMsg = _.flatten(_.toArray(error.response.data.errors));
            } else {
              errMsg = ["Something went wrong. Please try again."];
            }
            Swal.fire("Failed load data !", errMsg.join(""), "error");
          });

          this.form.relationships.product = product.data.data[0]
        }
      }
    },
    deleteImage(idx) {
      if (this.form.images.length == 1) {
        Swal.fire(
          "Failed delete data image !",
          "Minimum has 1 data.",
          "warning"
        );
      } else {
        this.form.images.splice(idx, 1);
        this.form.storage_path_images.splice(idx, 1);
      }
    },
    async save(e, idProduct = false) {
      const self = this;

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
    },
  },
};
</script>
