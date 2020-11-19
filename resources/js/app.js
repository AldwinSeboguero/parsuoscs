/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import 'vuetify/dist/vuetify.min.css';
import '@mdi/font/css/materialdesignicons.css';
import vuetify from './vuetify';
import router from './router';

import App from './components/AppComponent';
import Login from './components/LoginComponent';
import store from "./store";

new Vue({
    el: '#app',
    router,
    vuetify,
    store,
    components:{
        'app' : App
    }
});
