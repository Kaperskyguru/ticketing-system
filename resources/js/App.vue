<template>
    <div>
      <headers></headers>
      <router-view></router-view>
    </div>
</template>

<script>
import Headers from "./components/Header.vue";
export default {
  name: "App",
  components: { Headers },

  created() {
    // this.$store.dispatch("getCategories", { self: this });
    this.$store.dispatch("getEvents", { self: this });
    // this.$store.dispatch("getProductCategories", { self: this });
  }
};
</script>

<style>
.banner {
  color: white;
  background-color: black;
}

.card-custom {
  color: blue;
}

.customBtn {
  border-radius: 55px;
}

.authBtn {
  color: #727273;
}
.authBtnInner {
  color: #caa720;
}

p{
  color: red
}
</style>
