
import './bootstrap';
import Vue from 'vue';
import VueRouter from 'vue-router'; 

import { Form, HasError, AlertError } from 'vform'

window.Vue = require('vue');  
window.Form = Form;  

import VueInternationalization from 'vue-i18n';
import Locale from './vue-i18n-locales.generated';

Vue.use(VueRouter,VueInternationalization);

const lang = document.documentElement.lang.substr(0, 2);  

import {routes} from './routes';

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError) 

const router = new VueRouter({
    mode: 'history',
    routes
});

const i18n = new VueInternationalization({
    locale: lang,
    messages: Locale
});


 
import VueSweetalert2 from 'vue-sweetalert2';

Vue.use(VueSweetalert2);
 
//import 'vue-snotify/styles/material.css'; // or dark.css or simple.css
import "vue-snotify/styles/simple.css";

import Snotify, { SnotifyPosition } from 'vue-snotify'

const options = {
  toast: {
    position: SnotifyPosition.rightBottom,
    timeout: 5000
  }
}

Vue.use(Snotify, options)

//Global components
import UploadPictures from './components/uploads/picturesComponent';

const app = new Vue({
    el: '#app', 
    i18n,
    router,  
    components: {
      "upload-pictures-view": UploadPictures,
      
    }
}); 
