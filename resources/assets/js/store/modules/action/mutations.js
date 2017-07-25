import * as types from './mutation-types'

export default {
    [types.SET_DETAIL_ACTION](state, action) {
        state.detailAction[action.id] = action
    },
};
