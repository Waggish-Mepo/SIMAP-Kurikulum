import Vue from 'vue'
import Vuex from 'vuex'
import router from './router.js'

//IMPORT MODULE SECTION
import auth from './stores/auth/index.js'
import subjects from './stores/subjects/index.js'
import teachers from './stores/teachers/index.js'
import subjectTeachers from './stores/subjectTeachers/index.js'
import reportPeriods from './stores/reportPeriods/index.js'
import courses from './stores/courses/index.js'
import majors from './stores/majors/index.js'
import batches from './stores/batches/index.js'
import studentGroups from './stores/studentGroups/index.js'
import students from './stores/students/index.js'
import studentCourses from './stores/studentCourses/index.js'
import gradebooks from './stores/gradebooks/index.js'
import predicateLetters from './stores/predicateLetters/index.js'
import gradebookComponents from './stores/gradebookComponents/index.js'
import scorecards from './stores/scorecards/index.js'
import scorecardComponents from './stores/scorecardComponents/index.js'
import reportbooks from './stores/reportbooks/index.js'
import studentAbsences from './stores/studentAbsences/index.js'
import attitudes from './stores/attitudes/index.js'
import attitudePredicates from './stores/attitudePredicates/index.js'

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
        studentCourses,
        majors,
        batches,
        studentGroups,
        students,
        gradebooks,
        predicateLetters,
        gradebookComponents,
        scorecards,
        scorecardComponents,
        reportbooks,
        studentAbsences,
        attitudes,
        attitudePredicates
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
            if (payload.message == "Unauthenticated.") {
                localStorage.clear();
                router.push({ name: 'login' });
            }
            state.errorMessage = Array.isArray(payload.message) ? payload.message[0] : payload.message;
            state.errors = !payload.errors ? [] : payload.errors;
        },
        SET_ERROR_VALIDATE(state, payload) {
            state.isLoading = false;
            if (payload.errors) {
                state.errorMessage = Array.isArray(payload.message) ? payload.message[0] : payload.message;
                state.errors = !payload.errors ? [] : payload.errors;
            } else {
                state.errors = !payload ? [] : payload;
            }
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