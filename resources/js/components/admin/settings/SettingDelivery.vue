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
            <h2 class="lead text-dark mb-0">Biaya Antar</h2>
          </div>
          <div class="card-body"></div>
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
  props: {
    baseUrl: {
      type: String,
    },
  },
  data() {
    return {
    };
  },
  mounted() {
  },
};
</script>
