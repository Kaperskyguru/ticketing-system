<template>
    <div class="row pb-5">
        <Event v-for="(event, i) in events.data" :key="i" :event="event" />
    </div>
</template>

<script>
import Event from "./Event.vue";
import { mapState } from "vuex";

export default {
    name: "Events",
    components: { Event },

    data() {
        return {};
    },

    computed: {
        ...mapState(["events"])
    }
};
</script>
