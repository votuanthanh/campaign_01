/* ============
 * Actions for the account module
 * ============
 *
 * The actions that are available on the
 * account module.
 */

import * as types from './mutation-types';

export const getAllCampaign = ({ commit }) => {
    commit(types.ALL_CAMPAIGN);
};

export default {
    getAllCampaign,
};
