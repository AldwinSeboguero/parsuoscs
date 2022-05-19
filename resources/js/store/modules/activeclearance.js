import axios from "axios";
const state={
    clearance: {}, 
    error: ""
};
const getters={};
const actions={
    SET_ERROR1: (context, errorMsg) => {
        context.commit("POST_ERROR1", errorMsg)
      },
    getClearance({commit}){
            axios.get("/api/v1/activeClearance")
            .then(response => {
                commit('setActiveClearance',response.data);
                commit('setError1',response.data);
            })
        },
    

};
const mutations ={
    setClearance(state,data){
        state.clearance = data;
       
    }, 
    setError1(state,data){
        state.error =data;
       
    }, 
    POST_ERROR1: (state, payload) => {
        state.error = payload;
      }
    
};

export default {
    namespaced: false,
    state,
    getters,
    actions,
    mutations
}