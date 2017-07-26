import * as types from './mutation-types'

export default {
    [types.SET_LIKE](state, data) {
        state.like[data.model + data.modelId] = data.like
        state.checkLiked[data.model + data.modelId] = data.checkLiked
        state.commentTotal[data.model + data.modelId] = data.commentTotal
    },

    [types.LIKE_ACTIVITY](state, data) {
        var like = state.like
        if (typeof (data.like) == 'boolean') {
            let dataLike = $.grep(like[data.model + data.modelId].data, function (item, index) {
                return item.user_id !== data.userId
            });

            like[data.model + data.modelId].data = dataLike
            like[data.model + data.modelId].total = state.like[data.model + data.modelId].total - 1
            state.like = []
            state.like = like
            state.checkLiked[data.model + data.modelId] = false

        } else {
            like[data.model + data.modelId].data = like[data.model + data.modelId].data.concat(data.like)
            like[data.model + data.modelId].total = state.like[data.model + data.modelId].total + 1
            state.like = []
            state.like = like
            state.checkLiked[data.model + data.modelId] = true
        }
    },
    [types.SHOW_MODAL](state, data) {
        state.showModal = true
        state.listUserliked = data
    },
    [types.HIDE_MODAL](state) {
        state.showModal = false
        state.listUserliked = []
    },
};
