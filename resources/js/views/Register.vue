<template>
  <div class="text-center banner p-3">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-custom p-5">
            <div class="container">
              <div class="pb-5">
                <div class="text-center pb-3">
                  <h5 class="authBtn">Register</h5>
                  <small class="authBtnInner">
                    Equipped with the cutting edge features
                    that make a 21st Century Investment
                    Platform,
                  </small>

                  <hr />
                </div>
                <ValidationObserver v-slot="{ handleSubmit }">
                  <form @submit.prevent="handleSubmit(register)">
                    <div class="form-group">
                      <ValidationProvider name="Name" rules="required" v-slot="{ errors }">
                        <input
                          type="text"
                          v-model="user.name"
                          class="form-control"
                          placeholder="Name"
                        />
                        <span class="text-danger">
                          {{
                          errors[0]
                          }}
                        </span>
                      </ValidationProvider>
                    </div>
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
                    <div class="form-group">
                      <ValidationProvider
                        name="Password Confirmation"
                        rules="required"
                        v-slot="{ errors }"
                      >
                        <input
                          type="password"
                          v-model="
                                                        user.password_confirmation
                                                    "
                          class="form-control"
                          placeholder="Confirm Password"
                        />
                        <span class="text-danger">
                          {{
                          errors[0]
                          }}
                        </span>
                      </ValidationProvider>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block customBtn">Signup</button>
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
  name: "Register",

  components: { ValidationProvider, ValidationObserver },

  data() {
    return {
      user: {}
    };
  },

  methods: {
    async register() {
      try {
        await this.$store.dispatch("register", this.user);
        this.$router.push("login");
      } catch (err) {
        console.log(err);
      }
    }
  }
};
</script>

<style lang="css" scoped></style>
