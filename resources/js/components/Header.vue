<template>
  <nav class="navbar navbar-light bg-light">
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
      <li class="nav-item">
        <router-link
          class="nav-link authBtn"
          id="pills-home-tab"
          data-toggle="pill"
          to="/"
          role="tab"
          aria-controls="pills-home"
          aria-selected="false"
        >Home</router-link>
      </li>
      <li class="nav-item" v-if="loggedIn">
        <router-link
          class="nav-link authBtn"
          id="pills-contact-tab"
          data-toggle="pill"
          to
          @click.native="redirect"
          role="tab"
          aria-controls="pills-contact"
          aria-selected="false"
        >Dashboard</router-link>
      </li>
      <li class="nav-item" v-if="!loggedIn">
        <router-link
          class="nav-link authBtn"
          id="pills-profile-tab"
          data-toggle="pill"
          to="/login"
          role="tab"
          aria-controls="pills-profile"
          aria-selected="false"
        >Login</router-link>
      </li>
      <li class="nav-item" v-if="!loggedIn">
        <router-link
          class="nav-link authBtn"
          id="pills-contact-tab"
          data-toggle="pill"
          to="/register"
          role="tab"
          aria-controls="pills-contact"
          aria-selected="false"
        >Register</router-link>
      </li>
      <li class="nav-item" v-if="isAdmin">
        <router-link
          class="nav-link authBtn"
          id="pills-contact-tab"
          data-toggle="pill"
          to="/admin/add"
          role="tab"
          aria-controls="pills-contact"
          aria-selected="false"
        >Add Event</router-link>
      </li>
      <li class="nav-item" v-if="loggedIn">
        <router-link
          class="nav-link authBtn"
          id="pills-contact-tab"
          data-toggle="pill"
          to
          @click.native="logout"
          role="tab"
          aria-controls="pills-contact"
          aria-selected="false"
        >Logout</router-link>
      </li>
    </ul>
    <form class="form-inline" v-if="!loggedIn">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" />
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </nav>
</template>
<script>
export default {
  computed: {
    loggedIn() {
      return this.$store.state.loggedIn;
    },

    isAdmin() {
      return this.loggedIn && this.$store.getters["isAdmin"];
    }
  },

  methods: {
    redirect() {
      if (this.isAdmin) {
        return this.$router.push("/admin");
      }
      return this.$router.push({
        name: "user",
        params: { id: this.$store.state.user.id }
      });
    },
    async logout() {
      try {
        const result = await this.$store.dispatch("logout");
        if (result) {
          return this.$router.push("/");
        }
        return this.$router.push("login");
      } catch (error) {
        console.log(error);
      }
    }
  }
};
</script>
