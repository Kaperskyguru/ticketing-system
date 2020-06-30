
require('./bootstrap');
import router from './routes.js'
import App from './App.vue';
window.Vue = require('vue');


const app = new Vue({
    el: '#app',
    router,
    render: h => h(App)
});
