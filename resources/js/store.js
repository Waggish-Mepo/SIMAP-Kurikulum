import Vue from 'vue'
import Vuex from 'vuex'

//IMPORT MODULE SECTION
import auth from './stores/auth/index.js'
import subjects from './stores/subjects/index.js'
import teachers from './stores/teachers/index.js'
import subjectTeachers from './stores/subjectTeachers/index.js'
import reportPeriods from './stores/reportPeriods/index.js'
import courses from './stores/courses/index.js'
import majors from './stores/majors/index.js'

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

const store = new Vuex.Store({
    modules: {
        auth,
        subjects,
        teachers,
        subjectTeachers,
        reportPeriods,
        courses,
        majors
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
        SET_ERROR_VALIDATE(state, payload) {
            state.isLoading = false;
            state.errors = !payload ? [] : payload;
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