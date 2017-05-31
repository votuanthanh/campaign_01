import { ALL_CAMPAIGN } from './mutation-types';
import { get, post } from 'axios'

export default {
    [ALL_CAMPAIGN](state, campaigns) {
        state.campaigns = campaigns
    },
};
