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
            <h2 class="lead text-dark mb-0">Alamat</h2>
          </div>
          <div class="card-body"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    baseUrl: {
      type: String,
    },
  },
  data() {
    return {
      propBaseUrl: "",
    };
  },
  mounted() {
    this.propBaseUrl = this.baseUrl;
  },
};
</script>
