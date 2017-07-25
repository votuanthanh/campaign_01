import * as types from './mutation-types';
import { get } from '../../../helpers/api'

export const getUser = ({ commit }, id) => {
    commit(types.SET_LOADING, true);

    return new Promise((resolve, reject) => {
        get(`user/${id}`)
            .then(res => {
                commit(types.SET_CURRENT_PAGE_USER, res.data.data.currentPageUser)
                commit(types.SET_LIST_ACTIVITY, res.data.data.listActivity)
                commit(types.SET_LOADING, false);
                resolve(res.data.data.currentPageUser)
            })
            .catch(err => {
                commit(types.SET_LOADING, false);
                reject(err)
            })
    })
};

export const loadMore = ({ commit, rootState }, data) => {
    if (data.infoPaginate.to < data.infoPaginate.total) {
        commit(types.SET_LOADING, true);

        return new Promise((resolve, reject) => {
            get(`user/${data.id}?page=${data.infoPaginate.current_page + 1}`)
                .then(res => {
                    commit(types.SET_LIST_ACTIVITY, res.data.data.listActivity);

                    for (let activity of res.data.data.listActivity.data) {
                        var modelName = ''

                        switch (activity.activitiable_type) {
                            case 'App\\Models\\Campaign':
                                modelName = 'campaign'
                                break;
                            case 'App\\Models\\Event':
                                modelName = 'event'
                                break;
                            case 'App\\Models\\Action':
                                modelName = 'action'
                                break;
                            default:
                                modelName = ''
                        }
                        rootState.like.like[modelName + activity.activitiable_id] = activity.activitiable.likes
                        rootState.like.checkLiked[modelName + activity.activitiable_id] = activity.activitiable.checkLike

                        if (modelName == 'action' || modelName == 'event') {
                            for (let comment of activity.activitiable.comments.data) {
                                rootState.like.like['comment' + comment.id] = comment.likes
                                rootState.like.checkLiked['comment' + comment.id] = comment.checkLike

                                for (let subComment of comment.sub_comment.data) {
                                    rootState.like.like['comment' + subComment.id] = subComment.likes
                                    rootState.like.checkLiked['comment' + subComment.id] = subComment.checkLike
                                }
                            }
                        }
                    }

                    commit(types.SET_LOADING, false);
                    resolve(res.data.data.currentPageUser)
                })
                .catch(err => {
                    commit(types.SET_LOADING, false);
                    reject(err)
                })
        })
    }
};

export default {
    getUser,
    loadMore,
};
