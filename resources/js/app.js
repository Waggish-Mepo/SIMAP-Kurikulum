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

// filter
Vue.filter('groupSubject', function(text) {
    let textWithDot = text.substring(0, 1) + "." + " " + text.substring(1, text.length);
    let updateText = textWithDot.replace('(', '');
    return updateText.replace(')', '');
});

Vue.filter('dateFormat', function(date) {
    if(date) {
        let newDate = new Date(date);
        // day
        let day = newDate.getDate();
        if (day < 10) {
            day = '0'+day;
        };
        // month
        let monthList = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        let month = monthList[newDate.getMonth()];
        // year
        let year = newDate.getFullYear();
        
        return  day + ' ' + month + ' ' + year;
    } else {
        return '-';
    }
});

new Vue({
    router,
    store,
    components: { App },
}).$mount("#app");
