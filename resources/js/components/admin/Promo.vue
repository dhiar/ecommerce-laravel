<template>
  <!-- Begin Page Content -->
  <div class="container-fluid mb-5">
    <go-back></go-back><br />
    <!-- Page Heading -->
    <div class="card shadow">
      <div class="card-header">
        <div class="row">
          <div class="col-md-4 align-self-center">
            <h2 class="lead text-dark mb-0">Promo of Products</h2>
          </div>
          <div class="col-md-8 align-self-center float-right text-right">
            <small class="form-text text-muted"
              >Batas Promo: <b>20-20-2020 20:20:20 </b> &bull;
              <span class="text-danger">Promo Time is Over </span> &bull;
              <span class="text-success">Promo Time is Active </span>
            </small>
            <br />

            <span class="text-dark mb-0">Promo Time &nbsp;&nbsp;</span>
            <span>
              <el-date-picker
                v-model="formDate.end_at"
                type="datetime"
                :picker-options="endDatePickerOptions"
                default-time="09:00:00"
                value-format="yyyy-MM-dd HH:mm:ss"
                placeholder="Select date and time"
              >
              </el-date-picker>
            </span>

            <span>
              <button @click="setTime()" class="btn btn-primary btn-sm">
                <i class="fa fa-clock"></i> Set Time
              </button>
              <button
                @click="setActivation()"
                class="btn btn-primary btn-sm btn-setting-promo"
              >
                <i class="fa fa-check-circle"></i> Aktifkan
              </button>
            </span>
          </div>
        </div>
      </div>
      <div class="card-body table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th class="text-center" style="width: 5% !important">No</th>
              <th class="text-center" style="width: 10% !important">Image</th>
              <th style="width: 20% !important">Name</th>
              <th style="width: 15% !important" class="text-right">
                Normal Price
              </th>
              <th style="width: 15% !important" class="text-right">
                Promo Price
              </th>
              <th style="width: 10% !important">Stock</th>
              <th style="width: 15% !important">Category</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, idx) in resultsPromo.data" :key="item.id">
              <td class="text-center">
                {{ getNumber(currentPagePromo, idx) }}
              </td>
              <td class="text-center">
                <img
                  :src="item.image"
                  @error="imgErrorCondition"
                  class="img-fluid"
                  style="max-height: 100px !important"
                />
              </td>
              <td>{{ item.name }}</td>
              <td class="text-right">
                {{ formatCurrency(item.price) }}
              </td>
              <td class="text-right">
                {{ formatCurrency(item.promo_price) }}
              </td>
              <td>{{ item.stock }}</td>
              <td>{{ item.relationships.category.name }}</td>
              <td class="text-center">
                <a
                  class="btn btn-sm btn-success"
                  href="#"
                  @click="pageDetailProduct(item.id)"
                  ><i class="fa fa-eye text-gray-100"></i
                ></a>
                <a
                  class="btn btn-sm btn-danger"
                  href="#"
                  @click="deleteProductPromo(item.id)"
                  ><i class="fa fa-minus-circle text-gray-100"></i
                ></a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="card-footer">
        <!-- pagination -->
        <div class="overflow-auto">
          <b-pagination
            size="md"
            first-text="First"
            prev-text="Prev"
            next-text="Next"
            last-text="Last"
            :total-rows="totalItemsPromo"
            v-model="currentPagePromo"
            :per-page="perPagePromo"
            align="center"
          ></b-pagination>
        </div>
      </div>

      <hr />
      <div class="card-header">
        <h2 class="lead text-dark mb-0">Select Product as Promo</h2>
      </div>
      <div class="card-body table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th class="text-center" style="width: 5% !important">No</th>
              <th class="text-center" style="width: 10% !important">Image</th>
              <th style="width: 20% !important">Name</th>
              <th style="width: 15% !important" class="text-right">
                Normal Price
              </th>
              <th style="width: 15% !important" class="text-right">
                Promo Price
              </th>
              <th style="width: 10% !important">Stock</th>
              <th style="width: 15% !important">Category</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
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
              <td class="text-right">
                {{ formatCurrency(item.price) }}
              </td>
              <td class="text-right">
                {{ formatCurrency(item.promo_price) }}
              </td>
              <td>{{ item.stock }}</td>
              <td>{{ item.relationships.category.name }}</td>
              <td class="text-center">
                <a
                  class="btn btn-sm btn-success"
                  href="#"
                  @click="pageDetailProduct(item.id)"
                  ><i class="fa fa-eye text-gray-100"></i
                ></a>

                <a
                  class="btn btn-sm btn-primary"
                  href="#"
                  @click="addProductPromo(item.id)"
                  ><i class="fa fa-plus-circle text-gray-100"></i
                ></a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="card-footer">
        <div class="overflow-auto">
          <b-pagination
            size="md"
            first-text="First"
            prev-text="Prev"
            next-text="Next"
            last-text="Last"
            :total-rows="totalItems"
            v-model="currentPage"
            :per-page="perPage"
            align="center"
          ></b-pagination>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import GoBack from "./GoBack.vue";
import { BPagination } from "bootstrap-vue";
export default {
  components: {
    GoBack,
    "b-pagination": BPagination,
  },
  data() {
    return {
      currentPage: 1,
      currentPagePromo: 1,
      perPage: 10,
      perPagePromo: 10,
      totalItems: 50,
      totalItemsPromo: 50,
      results: {},
      resultsPromo: {},
      page: "product",
      endpoint: "/api/products",
      formDate: new Form({
        end_at: "",
      }),

      pickerOptions: {
        shortcuts: [
          {
            text: "Today",
            onClick(picker) {
              picker.$emit("pick", new Date());
            },
          },
          {
            text: "Yesterday",
            onClick(picker) {
              const date = new Date();
              date.setTime(date.getTime() - 3600 * 1000 * 24);
              picker.$emit("pick", date);
            },
          },
          {
            text: "A week ago",
            onClick(picker) {
              const date = new Date();
              date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
              picker.$emit("pick", date);
            },
          },
        ],
      },
      endDatePickerOptions: {
        disabledDate(date) {
          return date < new Date(new Date().toDateString());
        },
      },
    };
  },
  mounted() {
    this.fetchData(1);
    this.fetchDataPromo(1);
  },
  methods: {
    pageEditProduct(id) {
      this.$router.push({ path: `/admin/product/detail/` + id });
    },
    setActivation() {
      alert("setActivation");
    },
    setTime() {
      
    },
    pageDetailProduct(id) {
      this.$router.push({ path: `/admin/product/detail/` + id });
    },
    deleteProductPromo(id) {
      axios
        .put(this.endpoint + "/" + id, {
          is_promo: "0",
        })
        .then(({ data }) => {
          if (data.success) {
            Swal.fire(
              "Success !",
              data.message + " " + data.data.name,
              "success"
            );
          } else {
            Swal.fire("Failed !", data.message, "error");
          }
          this.fetchData();
          this.fetchDataPromo();
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
    addProductPromo(id) {
      axios
        .put(this.endpoint + "/" + id, {
          is_promo: "1",
        })
        .then(({ data }) => {
          if (data.success) {
            Swal.fire(
              "Success !",
              data.message + " " + data.data.name,
              "success"
            );
          } else {
            Swal.fire("Failed !", data.message, "error");
          }
          this.fetchData();
          this.fetchDataPromo();
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
    async fetchData(page = 1) {
      const self = this;
      await axios
        .get(self.endpoint + "?page=" + page, {
          params: {
            is_promo: "0",
          },
        })
        .then(({ data }) => {
          this.currentPage = data.current_page;
          this.perPage = data.per_page;
          this.totalItems = data.total;
          this.results = data;
        });
    },
    async fetchDataPromo(page = 1) {
      const self = this;
      await axios
        .get(self.endpoint + "?page=" + page, {
          params: {
            is_promo: "1",
          },
        })
        .then(({ data }) => {
          this.currentPagePromo = data.current_page;
          this.perPagePromo = data.per_page;
          this.totalItemsPromo = data.total;
          this.resultsPromo = data;
        });
    },
  },
  created() {
    const self = this;
    Fire.$on("searching", () => {
      let query = this.$parent.search;
      axios
        .get(self.endpoint + "?q=" + query, {
          params: {
            is_promo: "0",
          },
        })
        .then(({ data }) => {
          self.currentPagePromo = data.current_page;
          self.perPagePromo = data.per_page;
          self.totalItemsPromo = data.total;
          self.resultsPromo = data;
        })
        .catch(() => {
          this.$Progress.fail();
          Toast.fire({
            icon: "error",
            title: "Product promo price not found.",
          });
        });

      // normal price
      axios
        .get(self.endpoint + "?q=" + query, {
          params: {
            is_promo: "0",
          },
        })
        .then(({ data }) => {
          self.currentPage = data.current_page;
          self.perPage = data.per_page;
          self.totalItems = data.total;
          self.results = data;
        })
        .catch(() => {
          this.$Progress.fail();
          Toast.fire({
            icon: "error",
            title: "Product normal price not found.",
          });
        });
    });
  },
  watch: {
    currentPage: {
      handler: function (value) {
        this.fetchData(value);
      },
    },
    currentPagePromo: {
      handler: function (value) {
        this.fetchDataPromo(value);
      },
    },
  },
};
</script>