import Vue from 'vue'
import Vuex from 'vuex'
import campaign from './modules/campaign'
import auth from './modules/auth'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    //Assign the modules to the store
    modules: {
        campaign,
        auth,
    },

    // If strict mode should be enabled
    strict: debug,
});
