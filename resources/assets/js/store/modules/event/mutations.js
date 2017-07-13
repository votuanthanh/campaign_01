import * as types from './mutation-types';

import axios from 'axios'

export default {
    [types.SET_EVENT](state, dataEvent) {
        state.event = dataEvent.event[0]
        state.actions = dataEvent.actions
    },

    [types.LOAD_ACTION](state, dataAction) {
        let old_data = state.actions.data
        state.actions = dataAction.actions
        state.actions.data = $.merge(old_data, state.actions.data)
    },

    [types.SET_ACTION](state, dataAction) {
        state.actions = dataAction
    },

    [types.SET_KEY_SEARCH](state, dataKey) {
        state.key_search = dataKey
    },

    [types.SET_FLAG_SEARCH](state, dataFlag) {
        state.flag_search = dataFlag
    },

    [types.SET_LOAD_SEARCH](state, dataLoad) {
        state.load_search = dataLoad
    },

    [types.SET_LOAD_PAGINATE](state, dataLoad) {
        state.load_paginate = dataLoad
    }
};
