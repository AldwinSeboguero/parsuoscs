import axios from "axios";
const state={
    user: {}, 
    error: ""
};
const getters={};
const actions={
    SET_ERROR: (context, errorMsg) => {
        context.commit("POST_ERROR", errorMsg)
      },
 getUser({commit}){
        axios.get("/api/v1/user/current")
        .then(response => {
            commit('setUser',response.data);
            commit('setError',response.data);
        })
    },
    loginUser({commit},user){
        axios.post("/api/v1/user/login",{
            email: user.email,
            password: user.password,
        })
        .then( response => {
            resolve(response);
            if(response.data.access_token) {
                localStorage.setItem('token', response.data.access_token);
            } 
            else{
                commit('POST_ERROR',response.data);
                state.error = response.data.message;
                // console.dir(response.data.message); 
            } 
            
            window.location.replace("/#/"); 
        })
        .catch(err => { 
            // reject(err.response.data.errors)
           commit('POST_ERROR',err.response.data);
            console.log("sas")
          });
          
    
    }

};
const mutations ={
    setUser(state,data){
        state.user =data;
       
    }, 
    setError(state,data){
        state.error =data;
       
    }, 
    POST_ERROR: (state, payload) => {
        state.error = payload;
      }
    
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}