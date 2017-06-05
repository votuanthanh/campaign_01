import {
    CHECK,
    LOGIN,
    LOGOUT
} from './mutation-types';
import axios from 'axios'

export default {
    [CHECK](state) {
        state.authenticated = !!localStorage.getItem('access_token')
        axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`
    },

    [LOGIN](state, token) {
        state.authenticated = true;
        localStorage.setItem('access_token', token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    },

    [LOGOUT](state) {
        state.authenticated = false;
        localStorage.removeItem('id_token');
        axios.defaults.headers.common['Authorization']
    },
};
