import Vue from 'vue'
import Vuex from 'vuex'
import campaign from './modules/campaign'
import auth from './modules/auth'
import comment from './modules/comment'
import like from './modules/like'
import user from './modules/user'
import event from './modules/event'
import action from './modules/action'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    //Assign the modules to the store
    modules: {
        campaign,
        auth,
        comment,
        user,
        like,
        event,
        action,
    },
    // #root state
    state: {
        loading: false,
    },
    // #root mutations
    mutations: {
        SET_LOADING(state, loading) {
            state.loading = loading
        },
    },
    // If strict mode should be enabled
    strict: debug,
});
