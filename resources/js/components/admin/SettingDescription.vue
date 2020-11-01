<template>
  <!-- Begin Page Content -->
  <div class="container-fluid mb-5">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800 mb-4">Pengaturan</h1>
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
                ></textarea>
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
export default {
  data() {
    return {
      description: "",
    };
  },
  methods: {
    async createDescription() {
      await axios
        .post("/api/base/create-description", {
          description: this.description,
        })
        .then(({ data }) => {
          if (data.success) {
            Swal.fire(data.process + "!", data.message, "success");
          } else {
            Swal.fire(data.process + "!", data.message, "error");
          }
        });
    },
    fetchData() {
     axios
        .get("/api/base/show-description")
        .then(({ data }) => {
          if (data.id) {
            this.description = data.description
          }
        });
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>
