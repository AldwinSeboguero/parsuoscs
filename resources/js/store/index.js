import Vue from "vue"
import Vuex from "vuex"
// import currentUser from "./modules/currentUser"
// import user from "./modules/user"
// import activeclearance from "./modules/activeclearance"
Vue.use(Vuex);

import state from  "./state"
import *  as getters from "./getters"
import * as mutations from "./mutations"
import *  as actions from "./actions"


export default new Vuex.Store({
    // modules: {
    //     activeclearance,
    //     currentUser,
    //     user,
        
    // },
    // state: {
    //     error: "",
       
    // },
    // actions:{
        
    // }
    state,
    getters,
    mutations,
    actions
})