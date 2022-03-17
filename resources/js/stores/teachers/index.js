import axios from "axios";

const state = () => ({});

const mutations = {

};

const actions = {
    getAll({ commit }) {
        commit('SET_LOADING', true, { root: true });
        return new Promise((resolve, reject) => {
            axios.get('/teachers')
                .then((response) => {
                    resolve(response.data);
                    commit('SET_GOOD', null, { root: true });
                })
                .catch((error) => {
                    commit('SET_ERROR', error.response.data, { root: true });
                })
        })
    },
    withSubject({ commit }) {
        commit('SET_LOADING', true, { root: true });
        return new Promise((resolve, reject) => {
            axios.get('/teachers/subjects')
                .then((response) => {
                    resolve(response.data);
                    commit('SET_GOOD', null, { root: true });
                })
                .catch((error) => {
                    commit('SET_ERROR', error.response.data, { root: true });
                })
        })
    },
    getStatistics({ commit }) {
        commit('SET_LOADING', true, { root: true });
        return new Promise((resolve, reject) => {
            axios.get('/teachers/statistics')
                .then((response) => {
                    resolve(response.data);
                    commit('SET_GOOD', null, { root: true });
                })
                .catch((error) => {
                    commit('SET_ERROR', error.response.data, { root: true });
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