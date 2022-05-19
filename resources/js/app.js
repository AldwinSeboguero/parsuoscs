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
import store from "./store";
import VueSlimScroll from 'vue-slimscroll';  
import axios from 'axios';
import VueAxios from 'vue-axios'
import VueSocialauth from 'vue-social-auth'
import Clipboard from 'v-clipboard'
import VueParticlesBg from "particles-bg-vue";

Vue.use(Clipboard)
Vue.use(VueAxios, axios)
Vue.use(VueParticlesBg);
Vue.use(VueSocialauth, {

  providers: {
    google: {
      clientId: '1076053778325-rnovinsnkj7eo40hn1jqa8qu8spg3khq.apps.googleusercontent.com',
      redirectUri: 'http://oscs.parsu.edu.ph' // Your client app URL
    }
  }})
Vue.use(VueSlimScroll);
Vue.use(require("vue-moment")); 
new Vue({
    el: '#app',
    router,
    vuetify,
    store,
    components:{
        'app' : App,
    }
});
