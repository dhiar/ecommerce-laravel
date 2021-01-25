<template>
  <div class="list-group">
    <a
      :href="baseURL + '/admin/setting/banner'"
      class="list-group-item list-group-item-action"
      >Banner Slider</a
    >
    <a
      :href="baseURL + '/admin/setting/description'"
      class="list-group-item list-group-item-action"
      >Deskripsi Singkat</a
    >
    <a
      :href="baseURL + '/admin/setting/rekening'"
      class="list-group-item list-group-item-action"
      >Rekening</a
    >
    <a
      :href="baseURL + '/admin/setting/sosmed'"
      class="list-group-item list-group-item-action"
      >Sosial Media</a
    >
    <a
      :href="baseURL + '/admin/setting/address'"
      class="list-group-item list-group-item-action"
      >Alamat</a
    >
    <a
      :href="baseURL + '/admin/setting/delivery'"
      class="list-group-item list-group-item-action"
      >Biaya Antar</a
    >
    <a
      :href="baseURL + '/admin/setting/cod'"
      class="list-group-item list-group-item-action"
      >Cash On Delivery</a
    >
    <a
      :href="baseURL + '/admin/setting/footer'"
      class="list-group-item list-group-item-action"
      >Footer</a
    >
  </div>
</template>

<script>
export default {
  props: {
  },
  data() {
    return {
    };
  },
  mounted() {
  },
};
</script>
