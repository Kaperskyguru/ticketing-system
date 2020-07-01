import Vue from 'vue'
import Vuex from 'vuex'

import Repository from "./repositories/RepositoryFactory";
const EventRepository = Repository.get("events");
const AuthRepository = Repository.get("auth");

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        events: [],
        user:[],
        loggedIn:false,
        insights:[]
    },

    actions: {


        async getEvents({
            commit
        }) {
            commit("STORE_EVENTS", await EventRepository.get())
        },

        async login({
            commit
        }, payload) {
            commit("STORE_LOGGED_IN_USER", await AuthRepository.login(payload))
        },

        async register({
            commit
        }, payload) {
            return await AuthRepository.register(payload)
        },
        // async getProductCategories({
        //     commit
        // }) {
        //     commit("loadProductCategories", await ProductRepository.getProductCategories());
        // },
        // async loadSizeAttributes({
        //     commit,
        // }, payload) {
        //     commit("loadSizeAttributes", await AttributeRepository.find(payload.id));
        // },
        // async loadColorAttributes({
        //     commit,
        // }, payload) {
        //     commit("loadColorAttributes", await AttributeRepository.find(payload.id));
        // },
        // async loadReviews({
        //     commit
        // }, payload) {
        //     commit("loadReviews", await ReviewRepository.getReviewsByProductId(payload.id));
        // }

    },

    mutations: {
        STORE_LOGGED_IN_USER: (state, response) => {
            const { data } = response;

            if(data){
                localStorage.setItem('token', data.access_token)
                localStorage.setItem('user', data.user)
                state.user = data.user;
                state.token = data.access_token;
                state.insights = data.insights;
                state.loggedIn = true;
            }
        },

        STORE_EVENTS: (state, response) => {
            const {
                data
            } = response;
            state.events = data;
        },

        // loadCartProducts: (state) => {
        //     state.carts = Vue.prototype.$cart.getCarts();
        // },

        // removeCartProduct: (state, product) => {
        //     state.carts = Vue.prototype.$cart.remove(product);
        // },

        // loadProductCategories: (state, response) => {
        //     const {
        //         data
        //     } = response;
        //     state.productCategories = data
        // },
        // loadSizeAttributes: (state, response) => {
        //     const {
        //         data
        //     } = response;
        //     state.sizeAttributes = data;
        // },
        // loadColorAttributes: (state, response) => {
        //     const {
        //         data
        //     } = response;
        //     state.colorAttributes = data;
        // },
        // loadReviews: (state, response) => {
        //     const {
        //         data
        //     } = response;
        //     state.reviews = data;
        // }
    },

    getters: {
        getEvent: (state) => (id) => {
            return state.events.data.find(event => event.id === id);
        },

        isAdmin: (state) => {
            return state.user.is_admin;
        },
        isUser: (state) => {
            return !state.user.is_admin;
        }
    }

    // strict: true
});

export default store;