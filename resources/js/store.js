import Vue from 'vue'
import Vuex from 'vuex'

//IMPORT MODULE SECTION
import auth from './stores/auth/index.js'

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

const store = new Vuex.Store({
    modules: {
        auth
    },
    state: {
        errors: [],
        errorMessage: '',
        isLoading: false,
        user: {},
    },
    getters: {
        isLogged: state => !!state.user
    },
    mutations: {
        SET_USER(state, payload) {
            state.user = payload;
        },
        SET_LOADING(state, payload) {
            state.isLoading = payload;
        },
        SET_GOOD(state, payload) {
            state.isLoading = false;
            state.errorMessage = '';
            state.errors = '';
        },
        SET_ERROR(state, payload) {
            state.isLoading = false;
            state.errorMessage = Array.isArray(payload.message) ? payload.message[0] : payload.message;
            state.errors = !payload.errors ? [] : payload.errors;
        },
        CLEAR_ERROR(state) {
            state.errors = []
            state.errorMessage = ''
            state.isLoading = false
        }
    },
    strict: debug
});

export default store;