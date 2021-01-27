require("./bootstrap");

window.Vue = require("vue");

import { BootstrapVue, BootstrapVueIcons } from "bootstrap-vue";
import VueRouter from "vue-router";

Vue.use(BootstrapVue);
Vue.use(BootstrapVueIcons);
Vue.use(VueRouter);

import vSelect from 'vue-select'
Vue.component('v-select', vSelect)

import Vuesax from 'vuesax'

import 'vuesax/dist/vuesax.css' //Vuesax styles
Vue.use(Vuesax, {
    theme: {
        colors: {
            primary: "#5b3cc4",
            success: "rgb(23, 201, 100)",
            danger: "rgb(242, 19, 93)",
            warning: "rgb(255, 130, 0)",
            dark: "rgb(36, 33, 69)"
        }
    }
})

import 'material-icons/iconfont/material-icons.css';


import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue/dist/bootstrap-vue.css";
import "material-icons/iconfont/material-icons.css";
import 'vue-select/dist/vue-select.css';

import config from "./config";
import routes from "./routes";
import store from './store'

const router = new VueRouter({
    routes // short for `routes: routes`
});

Vue.component(
    "graduando-component",
    require("./views/GraduandoComponent.vue").default
);

const app = new Vue({
    el: "#app",
    router,
    store,
    data: {
        api_url: config.API_URL
    }
});
