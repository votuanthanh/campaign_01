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
        user: {
            namespaced: true,
            state: { user: { name: 'Vo Tuan Thanh', isAdmin: true } },
            getters: {
                isUser(state) {
                    return state.user.isAdmin
                }
            },
            mutations: {
                changeName(state) {
                    state.user.name = "Vo Thanh Nhan"
                }
            },
            actions: {
                changeNameOfUser({ dispatch, commit }) {
                    return new Promise((resolve, reject) => {
                        setTimeout(() => {
                            dispatch('campaign/getAllCampaign', null, { root: true })
                            resolve()
                        }, 1000)
                    })
                }
            }
        }
    },

    // If strict mode should be enabled
    strict: debug,
});
