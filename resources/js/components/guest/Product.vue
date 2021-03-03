<template>
  <div>
    <div class="breacrumb-section">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="breadcrumb-text product-more">
              <a :href="baseURL"><i class="fa fa-home"></i> Home</a>
              <a :href="baseURL + '/products'">Products</a>
              <span>Detail</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <section class="product-shop spad page-details">
      <div class="container">
        <div class="row">
          <div class="col-lg-3">
            <div class="filter-widget">
              <h4 class="fw-title">Brand</h4>
              <div class="fw-brand-check">
                <div class="bc-item">
                  <label for="bc-calvin">
                    Brand 1
                    <input type="checkbox" id="bc-calvin" />
                    <span class="checkmark"></span>
                  </label>
                </div>
                <div class="bc-item">
                  <label for="bc-diesel">
                    Brand 2
                    <input type="checkbox" id="bc-diesel" />
                    <span class="checkmark"></span>
                  </label>
                </div>
              </div>
            </div>
            <div class="filter-widget">
              <h4 class="fw-title">Size</h4>
              <div class="fw-size-choose">
                <div class="sc-item">
                  <input type="radio" id="s-size" />
                  <label for="s-size">s</label>
                </div>
                <div class="sc-item">
                  <input type="radio" id="m-size" />
                  <label for="m-size">m</label>
                </div>
                <div class="sc-item">
                  <input type="radio" id="l-size" />
                  <label for="l-size">l</label>
                </div>
              </div>
            </div>
            <div class="filter-widget">
              <h4 class="fw-title">Tags</h4>
              <div class="fw-tags">
                <a href="#">Tag A</a>
                <a href="#">Tag B</a>
                <a href="#">Tag C</a>
              </div>
            </div>
          </div>
          <div class="col-lg-9">
            <div class="row">
              <div class="col-lg-6">
                <div class="product-pic-zoom">
                  <img
                    class="product-big-img"
                    :src="baseURL + '/img/product-single/product-1.jpg'"
                    alt=""
                  />
                  <div class="zoom-icon">
                    <i class="fa fa-search-plus"></i>
                  </div>
                </div>
                <div id="product-thumbs" class="product-thumbs">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import GoBack from "../admin/GoBack.vue";
export default {
  components: {
    GoBack,
  },
  data() {
    return {
      page: "product",
      endpoint: "/api/products",
      dataImgBigUrl: "",
      images: [],
      product: {},
    };
  },
  async mounted() {
    let productSlug = this.$route.params.slug;
    const self = this;

const result = await axios
      .get(self.endpoint, {
        params: {
          slug: productSlug,
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
  },
};
</script>
