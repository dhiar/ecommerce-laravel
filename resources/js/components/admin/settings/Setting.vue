<style scoped>
.widget-user-header {
  background-position: "center center";
  background-size: cover;
  height: 250px;
}
</style>

<template>
  <div class="container-fluid">
    <go-back></go-back>
    <h1 class="h3 mb-2 text-gray-800 mb-4 mt-3">Pengaturan</h1>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="card shadow">
            <div class="card-body">
              <setting-menu></setting-menu>
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
    return {};
  },
  mounted() {},
  created() {},
  methods: {},
};
</script>
