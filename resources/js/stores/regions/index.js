import axios from "axios";

const state = () => ({});

const mutations = {

};

const actions = {
    index({ commit }, payload) {
        commit('SET_LOADING', true, { root: true });
        return new Promise((resolve, reject) => {
            axios.get('/regions/?search='+payload)
                .then((response) => {
                    resolve(response.data);
                    commit('SET_GOOD', null, { root: true });
                })
                .catch((error) => {
                    commit('SET_ERROR', error.response.data, { root: true });
                })
        })
    },
    teacherRegion({ commit }) {
        commit('SET_LOADING', true, { root: true });
        return new Promise((resolve, reject) => {
            axios.get('/regions/pluck-teacher')
                .then((response) => {
                    resolve(response.data);
                    commit('SET_GOOD', null, { root: true });
                })
                .catch((error) => {
                    commit('SET_ERROR', error.response.data, { root: true });
                })
        })
    },
    regionIdByTeacher({ commit }, payload) {
        commit('SET_LOADING', true, { root: true });
        return new Promise((resolve, reject) => {
            axios.get('/regions/check-teacher/'+payload)
                .then((response) => {
                    resolve(response.data);
                    commit('SET_GOOD', null, { root: true });
                })
                .catch((error) => {
                    commit('SET_ERROR', error.response.data, { root: true });
                })
        })
    },
    show({ commit }, payload) {
        commit('SET_LOADING', true, { root: true });
        return new Promise((resolve, reject) => {
            axios.get('/regions/'+payload)
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
            axios.post('/regions', payload)
                .then((response) => {
                    resolve(response.data);
                    commit('SET_GOOD', null, { root: true });
                })
                .catch((error) => {
                    commit('SET_ERROR_VALIDATE', error.response.data, { root: true });
                })
        })
    },
    edit({ commit }, payload) {
        commit('SET_LOADING', true, { root: true });
        return new Promise((resolve, reject) => {
            axios.patch('/regions/'+payload.id, payload.data)
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