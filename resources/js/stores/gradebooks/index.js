import axios from "axios";

const state = () => ({});

const mutations = {

};

const actions = {
    checkPeriodCourse({ commit }, payload) {
        commit('SET_LOADING', true, { root: true });
        return new Promise((resolve, reject) => {
            axios.get('/gradebooks/check-by-period-course/?report_period='+payload.report_period+'&course='+payload.course)
                .then((response) => {
                    resolve(response.data);
                    commit('SET_GOOD', null, { root: true });
                })
                .catch((error) => {
                    commit('SET_ERROR', error.response.data, { root: true });
                })
        })
    },
    getGradebook({ commit }, payload) {
        commit('SET_LOADING', true, { root: true });
        return new Promise((resolve, reject) => {
            axios.get('/gradebooks/check-gradebook/?report_period_id='+payload.report_period_id+'&course_id='+payload.course_id)
                .then((response) => {
                    resolve(response.data);
                    commit('SET_GOOD', null, { root: true });
                })
                .catch((error) => {
                    commit('SET_ERROR', error.response.data, { root: true });
                })
        })
    },
    gradebook({ commit }, payload) {
        commit('SET_LOADING', true, { root: true });
        return new Promise((resolve, reject) => {
            axios.get('/gradebooks/'+payload)
                .then((response) => {
                    resolve(response.data);
                    commit('SET_GOOD', null, { root: true });
                })
                .catch((error) => {
                    commit('SET_ERROR', error.response.data, { root: true });
                })
        })
    },
    create({ commit }, payload) {
        commit('SET_LOADING', true, { root: true });
        return new Promise((resolve, reject) => {
            axios.post('/gradebooks', payload)
                .then((response) => {
                    resolve(response.data);
                    commit('SET_GOOD', null, { root: true });
                })
                .catch((error) => {
                    commit('SET_ERROR_VALIDATE', error.response.data, { root: true });
                })
        })
    },
    updateGradebook({ commit }, payload) {
        commit('SET_LOADING', true, { root: true });
        return new Promise((resolve, reject) => {
            axios.patch('/gradebooks/'+payload.id, payload.data)
                .then((response) => {
                    resolve(response.data);
                    commit('SET_GOOD', null, { root: true });
                })
                .catch((error) => {
                    commit('SET_ERROR_VALIDATE', error.response.data, { root: true });
                })
        })
    },
};

export default {
    namespaced: true,
    state,
    actions,
    mutations
};