<style scoped>
.widget-user-header {
  background-position: "center center";
  background-size: cover;
  height: 250px;
}
</style>

<template>
  <div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800 mb-4">Pengaturan</h1>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="card shadow">
            <div class="card-body">
              <setting-menu :baseUrl="$parent.baseURL"></setting-menu>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {},
  data() {
    return {};
  },
  mounted() {},
  created() {},
  methods: {},
};
</script>
