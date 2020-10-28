<template>
  <div class="list-group">
    <a
      :href="propBaseUrl + '/admin/setting/banner'"
      class="list-group-item list-group-item-action"
      >Banner Slider</a
    >
    <a
      :href="propBaseUrl + '/admin/setting/description'"
      class="list-group-item list-group-item-action"
      >Deskripsi Singkat</a
    >
    <a
      :href="propBaseUrl + '/admin/setting/rekening'"
      class="list-group-item list-group-item-action"
      >Rekening</a
    >
    <a
      :href="propBaseUrl + '/admin/setting/sosmed'"
      class="list-group-item list-group-item-action"
      >Sosial Media</a
    >
    <a
      :href="propBaseUrl + '/admin/setting/address'"
      class="list-group-item list-group-item-action"
      >Alamat</a
    >
    <a
      :href="propBaseUrl + '/admin/setting/delivery'"
      class="list-group-item list-group-item-action"
      >Biaya Antar</a
    >
    <a
      :href="propBaseUrl + '/admin/setting/cod'"
      class="list-group-item list-group-item-action"
      >Cash On Delivery</a
    >
    <a
      :href="propBaseUrl + '/admin/setting/footer'"
      class="list-group-item list-group-item-action"
      >Footer</a
    >
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
