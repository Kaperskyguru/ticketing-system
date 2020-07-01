import Vue from "vue";
import Vuex from "vuex";

import Repository from "./repositories/RepositoryFactory";
const EventRepository = Repository.get("events");
const AuthRepository = Repository.get("auth");

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        events: [],
        user: [],
        loggedIn: false,
        insights: []
    },

    actions: {
        async getEvents({ commit }) {
            commit("STORE_EVENTS", await EventRepository.get());
        },

        async login({ commit }, payload) {
            commit("STORE_LOGGED_IN_USER", await AuthRepository.login(payload));
        },

        async register({ commit }, payload) {
            return await AuthRepository.register(payload);
        }
    },

    mutations: {
        STORE_LOGGED_IN_USER: (state, response) => {
            const { data } = response;

            if (data) {
                localStorage.setItem("token", data.access_token);
                localStorage.setItem("user", data.user);
                state.user = data.user;
                state.token = data.access_token;
                state.insights = data.insights;
                state.loggedIn = true;
            }
        },

        STORE_EVENTS: (state, response) => {
            const { data } = response;
            state.events = data;
        }
    },

    getters: {
        getEvent: state => id => {
            return state.events.data.find(event => event.id === id);
        },

        isAdmin: state => {
            return state.user.is_admin;
        },
        isUser: state => {
            return !state.user.is_admin;
        }
    }

    // strict: true
});

export default store;
