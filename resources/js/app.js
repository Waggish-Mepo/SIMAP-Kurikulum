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

Vue.prototype.$http = axios;
Vue.prototype.$http.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
Vue.prototype.$http.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.baseURL = window.location.origin + '/api';
axios.defaults.withCredentials = true
axios.interceptors.request.use(function(config) {
    // Do something before request is sent
    return config;
}, function(error) {
    if (error.response.status === 401) {
        router.push({ name: 'login' })
    }
    // Do something with request error
    return Promise.reject(error);
});

if (localStorage.getItem('token_kurikulum')) {
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token_kurikulum');
}

new Vue({
    router,
    store,
    components: { App },
}).$mount("#app");
