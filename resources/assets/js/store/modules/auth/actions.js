/* ============
 * Actions for the account module
 * ============
 *
 * The actions that are available on the
 * account module.
 */

import * as types from './mutation-types';
import { post, get } from '../../../helpers/api'
import { handleLogin, follow } from '../../../router/router'

export const check = ({ commit }) => {
    commit(types.CHECK);
};

export const login = ({ commit }, data) => {
    commit('SET_LOADING', true, { root: true })

    return new Promise((resolve, reject) => {
        post(handleLogin, data)
            .then(res => {
                commit(types.LOGIN, res.data)
                commit('SET_LOADING', false, { root: true });
                resolve(res.data.http_status.status)
            })
            .catch(err => {
                commit('SET_LOADING', false, { root: true });
                reject(err)
            })
    })
};

export const logout = ({ commit }) => {
    commit(types.LOGOUT);
};

export const setUser = ({ commit }, user) => {
    commit(types.SET_USER, user);
};

export const getListFollow = ({ commit }, list) => {
    get(follow)
        .then(res => {
            commit(types.GET_LIST_FOLLOW, res.data.followings);
            commit(types.GET_GROUPS, res.data.groups)
        })
        .catch(err => {
            commit(types.GET_LIST_FOLLOW, []);
            reject(err)
        })
};

export default {
    check,
    login,
    logout,
    setUser,
    getListFollow
};
