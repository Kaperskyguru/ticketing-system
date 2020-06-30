<template>
    <router-view></router-view>
</template>

<script>
export default {
  name: "App",
  created() {
    // this.$store.dispatch("getCategories", { self: this });
    // this.$store.dispatch("getProducts", { self: this });
    // this.$store.dispatch("getProductCategories", { self: this });
  }
};
</script>

<style>
</style>
