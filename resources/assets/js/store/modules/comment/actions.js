import * as types from './mutation-types';
import { post, patch, del, get } from '../../../helpers/api'

export const changeComment = ({ commit }, comments) => {
    commit(types.CHANGE_COMMENT, comments)
};

export const addComment = ({ commit, rootState }, data) => {
    return new Promise((resolve, reject) => {
        post(`comment/create-comment/${data.modelId}/${data.commentParentId}/${data.flag}`, data.comment)
            .then(res => {
                if (res.data.http_status.status) {

                    if (data.commentParentId == 0) { //when comment is parent
                        commit(types.PARENT_COMMENT, {
                            comments: res.data.createComment,
                            modelId: data.modelId,
                            flag: data.flag,
                            flagAction: data.flagAction
                        })

                        rootState.like.commentTotal['event' + data.modelId] = data.commentTotal + 1
                    } else {
                        commit(types.SUB_COMMENT, {
                            comments: res.data.createComment,
                            modelId: data.modelId,
                            flag: data.flag,
                            flagAction: data.flagAction
                        })
                    }

                    resolve(res.data.http_status.status)
                }
            })
            .catch(err => {
                reject(err)
            })
    })
};

export const editComment = ({ commit }, data) => {
    return new Promise((resolve, reject) => {
        patch(`comment/${data.commentId}`, data.comment)
            .then(res => {
                if (res.data.http_status.status) {
                    if (data.commentParentId == 0) { //when comment is parent
                        commit(types.PARENT_COMMENT, {
                            comments: res.data.updateComment,
                            modelId: data.modelId,
                            flagAction: data.flagAction
                        })
                    } else {
                        commit(types.SUB_COMMENT, {
                            comments: res.data.updateComment,
                            modelId: data.modelId,
                            flagAction: data.flagAction
                        })
                    }

                    resolve(res.data.http_status.status)
                }
            })
            .catch(err => {
                reject(err)
            })
    })
};

export const deleteComment = ({ commit }, data) => {
    return new Promise((resolve, reject) => {
        del(`comment/${data.commentId}`, { modelId: data.modelId })
            .then(res => {
                if (res.data.http_status.status) {
                    if (data.commentParentId == 0) { //when comment is parent
                        commit(types.DELETE_PARENT_COMMENT, data)
                    } else {
                        commit(types.DELETE_SUB_COMMENT, data)
                    }
                }

                resolve(res.data.http_status.status)
            })
            .catch(err => {
                reject(err)
            })
    })
};

export const loadMoreParentComment = ({ commit, rootState }, data) => {
    if (data.lastPage >= (parseInt(data.pageCurrent) + 1)) {
        return new Promise((resolve, reject) => {
            get(`comment/${data.modelId}?page=${(parseInt(data.pageCurrent) + 1)}`)
                .then(res => {
                    commit(types.LOAD_MORE_PARENT_COMMENT, { comments: res.data.loadMore.data, modelId: data.modelId })

                    for (let comment of res.data.loadMore.data) {
                        rootState.like.like['comment' + comment.id] = comment.likes
                        rootState.like.checkLiked['comment' + comment.id] = comment.checkLike
                    }
                    resolve(res.data.http_status.status)
                })
                .catch(err => {
                    reject(err)
                })
        })
    }
};

export const loadMoreSubComment = ({ commit, rootState }, data) => {
    if (data.lastPage >= (parseInt(data.pageCurrent) + 1)) {
        return new Promise((resolve, reject) => {
            get(`comment/sub-comment/${data.commentParentId}?page=${(parseInt(data.pageCurrent) + 1)}`)
                .then(res => {
                    commit(types.LOAD_MORE_SUB_COMMENT, {
                        comments: res.data.subComment,
                        modelId: data.modelId,
                        commentParentId: data.commentParentId
                    })

                    for (let subComment of res.data.subComment.data) {
                        rootState.like.like['comment' + subComment.id] = subComment.likes
                        rootState.like.checkLiked['comment' + subComment.id] = subComment.checkLike
                    }
                    resolve(res.data.http_status.status)
                })
                .catch(err => {
                    reject(err)
                })
        })
    }
};

export default {
    addComment,
    changeComment,
    editComment,
    deleteComment,
    loadMoreParentComment,
    loadMoreSubComment
};
