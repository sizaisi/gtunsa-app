/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

import { BootstrapVue, BootstrapVueIcons } from "bootstrap-vue";

import VueRouter from "vue-router";
import Vuesax from "vuesax";

Vue.use(BootstrapVue);
Vue.use(BootstrapVueIcons);
Vue.use(VueRouter);
//Vue.use(Vuesax);
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
});

import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue/dist/bootstrap-vue.css";
import "vuesax/dist/vuesax.css"; //Vuesax styles
import "material-icons/iconfont/material-icons.css";

import config from "./config";
import routes from "./routes";

const router = new VueRouter({
    routes // short for `routes: routes`
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component(
    "graduando-component",
    require("./views/GraduandoComponent.vue").default
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",
    router,
    data: {
        api_url: config.API_URL
    }
});
