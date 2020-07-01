<template>
  <div class="text-center banner p-3">
    <div class="container p-5">
      <div class="row p-5">
        <div class="col-md-12">
          <div class="card card-custom">
            <div class="container p-5">
              <div class="p-5">
                <div class="text-center pb-3">
                  <h5 class="authBtn">{{title}}</h5>
                  <small
                    class="authBtnInner"
                  >Create and sell your events around the world in seconds.</small>
                  <hr />
                </div>
                <ValidationObserver v-slot="{ handleSubmit }" ref="form">
                  <form @submit.prevent="handleSubmit(submit)">
                    <div class="form-group">
                      <ValidationProvider name="Title" rules="required" v-slot="{ errors }">
                        <input
                          type="text"
                          v-model="event.title"
                          class="form-control"
                          placeholder="Title"
                        />
                        <span class="text-danger">{{errors[0]}}</span>
                      </ValidationProvider>
                    </div>
                    <div class="form-group">
                      <ValidationProvider name="Description" rules="required" v-slot="{ errors }">
                        <textarea
                          v-model="event.description"
                          type="text"
                          size="20"
                          class="form-control"
                          placeholder="Description"
                        ></textarea>
                        <span class="text-danger">{{errors[0]}}</span>
                      </ValidationProvider>
                    </div>
                    <div class="form-group">
                      <ValidationProvider name="Ticket Price" rules="required" v-slot="{ errors }">
                        <input
                          v-model="event.ticket_price"
                          type="number"
                          min="0"
                          class="form-control"
                          placeholder="Ticket Price"
                        />
                        <span class="text-danger">{{errors[0]}}</span>
                      </ValidationProvider>
                    </div>
                    <div class="form-group">
                      <ValidationProvider name="Description" rules="required" v-slot="{ errors }">
                        <input
                          type="date"
                          v-model="event.date"
                          class="form-control"
                          placeholder="Event Date"
                        />
                        <span class="text-danger">{{errors[0]}}</span>
                      </ValidationProvider>
                    </div>
                    <button
                      type="submit"
                      class="btn btn-primary btn-lg btn-block customBtn"
                    >Save Events</button>
                  </form>
                </ValidationObserver>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from "vee-validate";
export default {
  name: "Add",
  components: { ValidationProvider, ValidationObserver },
  data() {
    return {
      event: {},
      updating: false
    };
  },

  computed: {
    title() {
      return this.updating ? "Update Event" : "Create New Event";
    }
  },
  methods: {
    async submit() {
      if (this.updating) {
        return this.updateEvent();
      } else {
        return this.createEvent();
      }
    },

    async createEvent() {
      try {
        await this.$store.dispatch("createEvent", this.event);
        return this.$router.push(-1);
      } catch (error) {
        console.log(error);
      }
    },

    async updateEvent() {
      const data = [];
      data.id = this.event.id;
      data.payload = this.event;

      try {
        await this.$store.dispatch("updateEvent", data);
        return this.$router.push(-1);
      } catch (error) {
        console.log(error);
      }
    }
  },
  beforeRouteEnter(to, from, next) {
    if (to.query.event) {
      next(vm => {
        vm.updating = true;
        vm.event = vm.$store.getters.getEvent(to.query.event);
      });
    }
  },

  beforeRouteUpdate(to, from, next) {
    this.event = {};
    if (to.query.event) {
      this.updating = true;
      this.event = this.$store.getters.getEvent(to.query.event);
    }
    next();
  }
};
</script>

<style lang="css" scoped></style>
