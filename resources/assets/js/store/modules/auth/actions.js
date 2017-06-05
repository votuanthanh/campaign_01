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

export const login = ({ commit }, token) => {
    commit(types.LOGIN, token);
};

export const logout = ({ commit }) => {
    commit(types.LOGOUT);
};

export default {
    check,
    login,
    logout
};
