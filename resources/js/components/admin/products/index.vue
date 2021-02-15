<template>
  <!-- Begin Page Content -->
  <div class="container-fluid mb-5">
    <go-back></go-back><br />
    <!-- Page Heading -->
    <div class="card shadow">
      <div class="card-header">
        <h2 class="lead text-dark mb-0">Products</h2>
      </div>
      <div class="card-body table-responsive">
        <button @click="pageNewProduct()" class="btn btn-primary">
          Tambah Product
        </button>
      </div>
      <table class="table table-hover">
        <thead>
          <tr>
            <th class="text-center" style="width: 5% !important">No</th>
            <th class="text-center" style="width: 10% !important">Image</th>
            <th style="width: 20% !important">Name</th>
            <th style="width: 10% !important">Price</th>
            <th style="width: 10% !important">Stock</th>
            <th style="width: 15% !important">Category</th>
            <!-- <th style="width: 10% !important">Publish</th> -->
            <th style="width: 12% !important">Created By</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <!-- {{results}} -->
          <tr v-for="(item, idx) in results.data" :key="item.id">
            <td class="text-center">{{ getNumber(currentPage, idx) }}</td>
            <td class="text-center">
              <img
                :src="item.image"
                @error="imgErrorCondition"
                class="img-fluid"
                style="max-height: 100px !important"
              />
            </td>
            <td>{{ item.name }}</td>
            <td>{{ formatCurrency(item.price) }}</td>
            <td>{{ item.stock }}</td>
            <td>{{ item.relationships.category.name }}</td>
            <!-- <td>{{ item.is_published | isPublished }}</td> -->
            <td>{{ item.relationships.admin.name | upText }}</td>
            <td class="text-center">
              <a
                class="btn btn-sm btn-success"
                href="#"
                @click="pageEditProduct(item.id)"
                ><i class="fa fa-eye text-gray-100"></i
              ></a>
              <a
                class="btn btn-sm btn-info"
                href="#"
                @click="pageNewProduct(item.id)"
                ><i class="fa fa-pen text-gray-100"></i
              ></a>

              <a
                class="btn btn-sm btn-danger"
                href="#"
                @click="deleteProduct(item.id, item.name)"
                ><i class="fa fa-trash-alt text-gray-100"></i
              ></a>
            </td>
          </tr>
        </tbody>
      </table>
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
      currentPage: 1,
      perPage: 10,
      totalItems: 50,
      results: {},
      page: "product",
      endpoint: "/api/products",
    };
  },
  mounted() {
    this.fetchData(1);
  },
  methods: {
    pageEditProduct(id) {
      this.$router.push({ path: `/admin/product/detail/` + id });
    },
    pageNewProduct(id) {
      if (id) {
        this.$router.push({ path: `/admin/product/` + id });
      } else {
        this.$router.push({ path: `/admin/product/create` });
      }
    },
    deleteProduct(id, name) {
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