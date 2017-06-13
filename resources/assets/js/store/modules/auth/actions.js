/* ============
 * Actions for the account module
 * ============
 *
 * The actions that are available on the
 * account module.
 */

import * as types from './mutation-types';

export const check = ({ commit }) => {
    commit(types.CHECK);
};

export const login = ({ commit }, data) => {
    commit(types.LOGIN, data)
};

export const logout = ({ commit }) => {
    commit(types.LOGOUT);
};

export const setUser = ({ commit }, user) => {
    commit(types.SET_USER, user);
};

export default {
    check,
    login,
    logout,
    setUser,
};
