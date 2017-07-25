import * as types from './mutation-types';
import { post, get } from '../../../helpers/api'

export const setDetailAction = ({ commit }, data) => {
    commit(types.SET_DETAIL_ACTION, data)
};

export default {
    setDetailAction,
};
