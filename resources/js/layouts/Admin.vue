<template>
    <div>
        <router-view></router-view>
        <router-view name="helper"></router-view>
    </div>
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

<style></style>
