
require('./bootstrap');
import router from './routes.js'
import store from './store.js'
import App from './App.vue';
window.Vue = require('vue');


const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App)
});
