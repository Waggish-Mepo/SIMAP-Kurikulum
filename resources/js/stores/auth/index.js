import axios from "axios";
import router from '../../router'

const state = () => ({});

const mutations = {

};

const actions = {
    login({ commit }, payload) {
        commit('SET_LOADING', true, { root: true });
        return new Promise((resolve, reject) => {
            axios.get('auth/sanctum/csrf-cookie').then(response => {
                axios.post('/login', payload).then((res) => {
                    let data = res.data;
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + data.access_token;
                    localStorage.setItem('token_kurikulum', data.access_token);
                    // localStorage.setItem('user', JSON.stringify(data.user));
                    // commit('SET_USER', data.user, { root: true });
                    commit('SET_GOOD', null, { root: true });
                    router.push({ name: 'dashboard' });
                    resolve(res.data);
                }).catch((error) => {
                    commit('SET_ERROR', error.response.data, { root: true });
                });
            });
        })
    },
};

export default {
    namespaced: true,
    state,
    actions,
    mutations
};