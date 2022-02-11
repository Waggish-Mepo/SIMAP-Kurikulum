/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Vue from 'vue'
import router from './router.js'
import store from './store.js'
import App from './App.vue'
import axios from 'axios'

// window.Vue = require('vue').default;
// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

new Vue({
    router,
    store,
    components: { App },
}).$mount("#app");
