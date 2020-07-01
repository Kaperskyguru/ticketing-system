<template>
  <div class="text-center banner p-3 pb-5">
    <div class="container pb-5">
      <div class="row pb-5">
        <div class="col-md-8 col-12 pb-5">
          <div class="card card-custom p-5">
            <h5 class="authBtn">{{ event.title }}</h5>
            <hr />

            <div class="container">
              <small class="authBtnInner pb-3">{{ event.description }}</small>
            </div>
          </div>
        </div>

        <div class="col-md-4 col-12">
          <div class="card card-custom p-5">
            <h5 class="authBtn">Event Ticket</h5>
            <hr />
            <span>Event Date:</span>
            <small class="authBtnInner pb-3">
              {{
              event.event_date
              }}
            </small>
            <hr />
            <span>Event Price:</span>
            <small class="authBtnInner pb-3">${{ event.ticket_price }}</small>
            <router-link to="/" class="btn btn-primary customBtn">Buy Ticket</router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetter } from "vuex";
export default {
  name: "Ticket",
  computed: {
    event() {
      return this.$store.getters.getEvent(this.$route.params.id);
    }
  }
};
</script>
