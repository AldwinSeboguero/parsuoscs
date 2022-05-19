import axios from "axios"

export const getActiveClearance = ({commit}) => {
    axios.get("/api/v1/active-clearance")
    .then(response => {
        commit('SET_ACTIVECLEARANCE',response.data);
    })
}