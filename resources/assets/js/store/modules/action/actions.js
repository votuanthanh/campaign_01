import * as types from './mutation-types';
import { post, get } from '../../../helpers/api'

export const setDetailAction = ({ commit }, data) => {
    commit(types.SET_DETAIL_ACTION, data)
};

export const getDetailAction = ({ commit }, actionId) => {
    return new Promise((resolve, reject) => {
        get(`action/${actionId}`)
            .then(res => {
                resolve(res.data)
            })
            .catch(err => {
                reject(err)
            })
    })
};

export default {
    setDetailAction,
    getDetailAction
};
