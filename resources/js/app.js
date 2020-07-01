
require('./bootstrap');
import router from './routes.js'
import store from './store.js'
import App from './App.vue';
import './vee-validate'

window.Vue = require('vue');


const app = new Vue({
    el: '#app',
    store,
    router,
    render: h => h(App)
});
