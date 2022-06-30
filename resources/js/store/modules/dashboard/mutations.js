export const  SET_DASHBOARD = (state, 
    {completed,
    totalStudent,
    approvedRequest,
    pendingRequest,
    totalClearanceRequest,
    totalActivatedAccount,
    semester}) => {
        
    state.completed = completed;
    state.totalStudent = totalStudent;
    state.approvedRequest =approvedRequest;
    state.pendingRequest =pendingRequest;
    state.totalClearanceRequest =totalClearanceRequest;
    state.totalActivatedAccount =totalActivatedAccount;
    state.semester = semester;
}