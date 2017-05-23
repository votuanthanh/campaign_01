import Vue from 'vue'
import Vuex from 'vuex'
import campaign from './modules/campaign'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    //Assign the modules to the store
    modules: {
        campaign,
    },

    // If strict mode should be enabled
    strict: debug,
});
