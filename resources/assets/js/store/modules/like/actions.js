import * as types from './mutation-types';
import { post } from '../../../helpers/api'

export const setLike = ({ commit }, data) => {
    commit(types.SET_LIKE, data)
};

export const likeActivity = ({ commit }, data) => {
    return new Promise((resolve, reject) => {
        post(`like/${data.modelId}/${data.model}`)
            .then(res => {
                if (res.data.http_status.status) {
                    commit(types.LIKE_ACTIVITY, {
                        like: res.data.like,
                        modelId: data.modelId,
                        model: data.model,
                        userId: data.userId
                    })
                    resolve(res.data.http_status.status)
                }
            })
            .catch(err => {
                reject(err)
            })
    })
};

export const showUsersLiked = ({ commit }, data) => {
    commit(types.SHOW_MODAL, data)
};

export const hideUsersLiked = ({ commit }) => {
    commit(types.HIDE_MODAL)
};

export default {
    setLike,
    likeActivity,
    showUsersLiked,
    hideUsersLiked
};
