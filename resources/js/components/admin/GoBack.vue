<template>
  <div>
    <div class="row">
      <div class="col">
        <el-button
				@click="prevPage()"
          class="g-btn-back-purple"
          icon="el-icon-arrow-left"
          circle
        ></el-button>
      </div>
      <div class="col text-right">
        <el-button
				@click="nextPage()"
          class="g-btn-back-purple"
          icon="el-icon-arrow-right"
          circle
        ></el-button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Goback",
  props: {
  },
  data() {
    return {}
  },
  methods: {
  },
};
</script>