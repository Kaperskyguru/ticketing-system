<template>
    <div class="text-center banner">
        <div class="container p-5">
            <div class="p-5">
                <h1>User DashBoard</h1>
            </div>
        </div>
        <div class="container pb-5">
            <div class="row pb-5">
                <div class="col-md-6">
                    <counter
                        title="Total Events"
                        :value="insights.totalEventJoined"
                    ></counter>
                    <br />
                </div>

                <div class="col-md-6">
                    <counter
                        title="Total Amount Spent"
                        :value="insights.totalAmountSpent"
                        symbol="$"
                    ></counter>
                </div>
                <br />
            </div>
        </div>
        <br /><br /><br /><br />

        <div class="container pt-5 pb-5">
            <h5 class="pb-5">All Events</h5>
            <div class="row pb-5">
                <div class="col-md-12">
                    <div class="card">
                        <event-table
                            :events="userevents.data"
                            :isAdmin="false"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Counter from "../../components/Counter";
import EventTable from "../../components/EventTable";
import { mapState } from "vuex";

export default {
    name: "User",
    components: { Counter, EventTable },

    data() {
        return {};
    },

    computed: {
        ...mapState(["insights", "userevents"])
    },

    beforeCreate() {
        this.$store.dispatch("getUserEvents", this.$store.state.user.id);
    }
};
</script>

<style lang="css" scoped></style>
