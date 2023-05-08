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
import VueCompositionAPI from '@vue/composition-api'
import VueApexCharts from 'vue-apexcharts'
import VueBreadcrumbs from 'vue-2-breadcrumbs';

// import '@mdi/font/css/materialdesignicons.css';
// import VueConfirmDialog from 'vue-confirm-dialog'

// Vue.use(VueConfirmDialog)
// Vue.component('vue-confirm-dialog', VueConfirmDialog.default)
Vue.use(VueBreadcrumbs,{
  template:
  '<ol class="wizard">\n'+
  '<li v-for="(crumb, key) in $breadcrumbs" v-if="crumb.meta.breadcrumb" :key="key"\n' +
  // '    v-for="crumb in breadcrumbs" \n'+
  '    tabindex="1" \n'+
  '    class="wizard__item"\n'+
  // '    v-on:click="setStep(step.id)"\n'+
  // '    v-bind:title="step.desc"\n'+
  '    v-bind:class="{ \'wizard__item--visited\': false,  \'wizard__item--disabled\': false, \'wizard__item--active\': $route.name == getBreadcrumb(crumb.meta.breadcrumb) }"\n'+
  '     >\n'+
  '    <span class="wizard__item-desc">\n'+
  '         <router-link v-if="$route.name != getBreadcrumb(crumb.meta.breadcrumb)" :to="{ path: getPath(crumb) }">{{ getBreadcrumb(crumb.meta.breadcrumb) }}</router-link>' +
  '         <span v-else >{{ getBreadcrumb(crumb.meta.breadcrumb) }}</span>' +

  // '\n {{getPath(crumb)}} \n'+
  '    </span>\n'+
  '    <span class="wizard__item-arrows"></span>\n'+
  '  </li>\n'+
  '</ol>\n'
    // '        <nav v-if="$breadcrumbs.length" aria-label="breadcrumb">\n' +
    // '            <ol class="breadcrumb">\n' +
    // '                <li v-for="(crumb, key) in $breadcrumbs" v-if="crumb.meta.breadcrumb" :key="key" class="breadcrumb-item active" aria-current="page">\n' +
    // '                    <router-link :to="{ path: getPath(crumb) }">{{ getBreadcrumb(crumb.meta.breadcrumb) }}</router-link>' +
    // '                </li>\n' +
    // '            </ol>\n' +
    // '        </nav>'
});
Vue.use(VueApexCharts)

Vue.component('apexchart', VueApexCharts)
Vue.use(VueCompositionAPI)
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
