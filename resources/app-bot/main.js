import Vue from "vue";

//В проекте много дублированного кода. Это сделано намеренно из за частых изменений в отдельных таблицах

import router from './router/router'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import App from "./App";
import store from "./store";
import axios from "axios";
import Notifications from 'vue-notification'
import VueNestable from 'vue-nestable'


import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import './assets/scss/app.scss'
import Auth from './assets/js/auth.js';

const eventBus = new Vue()

Vue.config.productionTip = false

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(Notifications)
Vue.use(VueNestable)

window.axios = axios;
window.auth = new Auth();
window.eventBus = eventBus

new Vue({
    router,
    store,
    render: h => h(App),
}).$mount('#app')