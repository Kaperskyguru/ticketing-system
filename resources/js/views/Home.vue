<template>
    <div class="text-center banner">
        <div class="container p-5">
            <div class="p-5">
                <h1>Welcome to Ticketing System</h1>
                <p>
                    Unique, Sustanable and Future Bitcoin is the world’s
                    most-traded cryptocurrency which represents a massive share
                    of the cryptocurrency market. Get started by creating an
                    account today.
                </p>
            </div>
        </div>
        <div class="container pb-5">
            <Events />
        </div>
    </div>
</template>

<script>
import Events from "../components/Events.vue";
export default {
    name: "Home",
    components: { Events },

    data() {
        return {};
    }
};
</script>

<style lang="css" scoped></style>
