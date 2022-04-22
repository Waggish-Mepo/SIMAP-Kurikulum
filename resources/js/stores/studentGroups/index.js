import axios from "axios";
import router from "../../router";

const state = () => ({});

const mutations = {

};

const actions = {
    index({ commit }, payload) {
        commit('SET_LOADING', true, { root: true });
        return new Promise((resolve, reject) => {
            axios.get('/student-groups/?page='+payload.page+'&per_page='+payload.per_page+'&batch='+payload.batch+'&search='+payload.search+'&sort='+payload.sort)
                .then((response) => {
                    resolve(response.data);
                    commit('SET_GOOD', null, { root: true });
                })
                .catch((error) => {
                    commit('SET_ERROR', error.response.data, { root: true });
                })
        })
    },
    getAll({ commit }) {
        commit('SET_LOADING', true, { root: true });
        return new Promise((resolve, reject) => {
            axios.get('/student-groups/all')
                .then((response) => {
                    resolve(response.data);
                    commit('SET_GOOD', null, { root: true });
                })
                .catch((error) => {
                    commit('SET_ERROR', error.response.data, { root: true });
                })
        })
    },
    detailStudentGroup({ commit }, payload) {
        commit('SET_LOADING', true, { root: true });
        return new Promise((resolve, reject) => {
            axios.get('/student-groups/'+payload)
                .then((response) => {
                    resolve(response.data);
                    commit('SET_GOOD', null, { root: true });
                })
                .catch((error) => {
                    commit('SET_ERROR', error.response.data, { root: true });
                })
        })
    },
    getByCourse({ commit }, payload) {
        commit('SET_LOADING', true, { root: true });
        return new Promise((resolve, reject) => {
            axios.get('/student-groups/by-course/'+payload)
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
            axios.post('/student-groups', payload)
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
            axios.patch('/student-groups/'+payload.id, payload.data)
                .then((response) => {
                    resolve(response.data);
                    commit('SET_GOOD', null, { root: true });
                })
                .catch((error) => {
                    commit('SET_ERROR_VALIDATE', error.response.data, { root: true });
                })
        })
    },
    deleteStudentGroupCascade({ commit }, payload) {
        commit('SET_LOADING', true, { root: true });
        return new Promise((resolve, reject) => {
            axios.delete('/student-groups/'+payload)
                .then((response) => {
                    resolve(response.data);
                    commit('SET_GOOD', null, { root: true });
                    router.push({ name: 'batches', params: {page: 4} });
                })
                .catch((error) => {
                    console.log(error);
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
