<template>
  <div class="text-center banner p-3">
    <div class="container p-5">
      <div class="row p-5">
        <div class="col-md-12">
          <div class="card card-custom">
            <div class="container p-5">
              <div class="p-5">
                <div class="text-center pb-3">
                  <h5 class="authBtn">Login</h5>
                  <small class="authBtnInner">
                    Equipped with the cutting edge features
                    that make a 21st Century Investment
                    Platform,
                  </small>

                  <hr />
                </div>
                <ValidationObserver v-slot="{ handleSubmit }" ref="form">
                  <form @submit.prevent="handleSubmit(login)">
                    <div class="form-group">
                      <ValidationProvider name="Email" rules="required|email" v-slot="{ errors }">
                        <input
                          type="email"
                          v-model="user.email"
                          class="form-control"
                          placeholder="Email"
                        />
                        <span class="text-danger">
                          {{
                          errors[0]
                          }}
                        </span>
                      </ValidationProvider>
                    </div>
                    <div class="form-group">
                      <ValidationProvider name="Password" rules="required" v-slot="{ errors }">
                        <input
                          type="password"
                          v-model="user.password"
                          class="form-control"
                          placeholder="Password"
                        />
                        <span class="text-danger">
                          {{
                          errors[0]
                          }}
                        </span>
                      </ValidationProvider>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block customBtn">Login</button>
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
  name: "Login",
  components: { ValidationProvider, ValidationObserver },

  data() {
    return {
      user: {}
    };
  },

  methods: {
    async login() {
      try {
        await this.$store.dispatch("login", this.user);
        if (this.$store.state.loggedIn && this.$store.getters["isAdmin"])
          return this.$router.push("/admin/");
        else {
          return this.$router.push({
            name: "user",
            params: { id: this.$store.state.user.id }
          });
        }
      } catch (err) {
        console.log(err);
      }
    }
  }
};
</script>

<style lang="css" scoped></style>
