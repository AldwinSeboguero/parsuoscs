import Vue from 'vue';
import VueRouter from 'vue-router';
import LoginComponent from './components/login/LoginComponent';
import AdminComponent from './components/AdminComponent';
import StudentComponent from './components/student/StudentComponent';
import CASHIERComponent from './components/cashier/CASHIERComponent'; 
import LIBRARYComponent from './components/library/LIBRARYComponent';
import DEANComponent from './components/dean/DEANComponent';
import REGISTRARSTAFFComponent from './components/registrarstaff/REGISTRARSTAFFComponent';
import REGISTRARComponent from './components/registrar/REGISTRARComponent';
import OSASComponent from './components/osas/OSASComponent';
import OSASOtherCampusComponent from './components/osas/OSASOtherCampusComponent';
import STCOUNCILComponent from './components/stcouncil/STCOUNCILComponent';
import SGSStudentComponent from './components/student/SGSStudentComponent';
import LHSStudentComponent from './components/student/LHSStudentComponent';
import PRINCIPALComponent from './components/principal/PRINCIPALComponent';
import ADVISERComponent from './components/adviser/ADVISERComponent';
//Admin Import Component
import AdminDashboardComponent from './components/admin/DashboardComponent';
import AdminDashboardComponentt from './components/admin/DashboardComponentt';
import AdminStudentListComponent from './components/StudentListComponent';
import CollegesComponent from './components/admin/CollegesComponent';
import CampusesComponent from './components/admin/CampusesComponent';
import GraduationsComponent from './components/admin/GraduationsComponent';
import ProgramsComponent from './components/admin/ProgramsComponent';
import PurposesComponent from './components/admin/PurposesComponent';
import SectionsComponent from './components/admin/SectionsComponent';
import SemestersComponent from './components/admin/SemestersComponent';

import AdminSiasAccountComponent from './components/SIASAccountComponent';
//Signatories Import Component
import AdminClearanceRequestsComponent from './components/ClearanceRequestsComponent';
import AdminClearedClearancesComponent from './components/ClearedClearancesComponent';
import AdminSubmittedClearancesComponent from './components/SubmittedClearancesComponent';
//Students Import Component
import AdminActiveClearanceComponent from './components/ActiveClearanceComponent';
import LHSAdminActiveClearanceComponent from './components/ActiveClearanceComponentLHS';
import SGSAdminActiveClearanceComponent from './components/ActiveClearanceComponentSGS';
import AdminClearanceListComponent from './components/ClearanceListComponent';
import AdminDeficiencyListComponent from './components/DeficiencyListComponent';
import RegistrarDeficiencyListComponent from './components/RegistrarDeficiencyListComponent';

//Settings Import Component
import SettingsComponent from './components/SettingsComponent';
import StudentSettingsComponent from './components/student/SettingsComponent';
import StudentSIASComponent from './components/student/StudentSIASComponent';
//Users Import Component
import AdminUsersComponent from './components/admin/UsersComponent';
import SignatoryUsersComponent from './components/admin/SignatoryUsersComponent';
//Signatories Admin Component
import AdminCashierComponent from './components/admin/signatory/CashierComponent';
import AdminDeanComponent from './components/admin/signatory/DeanComponent';
import AdminLibraryComponent from './components/admin/signatory/LibraryComponent';
import AdminOSASComponent from './components/admin/signatory/OSASComponent';
import AdminProgramDirectorComponent from './components/admin/signatory/ProgramDirectorComponent';
import AdminStudentCouncilComponent from './components/admin/signatory/StudentCouncilComponent';
import AdminRegistrarComponent from './components/admin/signatory/RegistrarComponent';
import AdminStaffComponent from './components/admin/signatory/StaffComponent';

//Program Director Import
import PDComponent from './components/programdirector/PDComponent'; 
import PDDashboardComponent from './components/programdirector/DashboardComponent'; 
import PDStudentListComponent from './components/programdirector/StudentListComponent'; 
import PDClearanceRequestsComponent from './components/programdirector/ClearanceRequestsComponent';
import PDCASClearanceRequestsComponent from './components/programdirector/ClearanceCASRequestsComponent';
import PDCBMClearanceRequestsComponent from './components/programdirector/ClearanceCBMRequestsComponent';
import PDCETClearanceRequestsComponent from './components/programdirector/ClearanceCETRequestsComponent';
import PDCOEDClearanceRequestsComponent from './components/programdirector/ClearanceCOEDRequestsComponent';
import PDSGSClearanceRequestsComponent from './components/programdirector/ClearanceSGSRequestsComponent';
import PDClearedClearancesComponent from './components/programdirector/ClearedClearancesComponent';
import PDSubmittedClearancesComponent from './components/programdirector/SubmittedClearancesComponent';
import PDClearanceListComponent from './components/programdirector/ClearanceListComponent';
import PDDeficiencyListComponent from './components/programdirector/DeficiencyListComponent';  

//student
import STUDENTClearanceListComponent from './components/student/ClearanceListComponent'; 

import ForgotPassword from './components/page/ForgotPassword';
import ResetPasswordForm from './components/page/ResetPasswordForm';
import ResetEmailForm from './components/page/ResetEmailForm';
Vue.use(VueRouter);

const routes =[
   
    { 
        path: '/reset-password', 
        name: 'reset-password', 
        component: ForgotPassword, 
        meta: { 
          auth:false 
        } 
      },
      { 
        path: '/reset-password/:token', 
        name: 'reset-password-form', 
        component: ResetPasswordForm, 
        meta: { 
          auth:false 
        } 
      },
      { 
        path: '/reset-email/:token', 
        name: 'reset-email-form', 
        component: ResetEmailForm, 
        meta: { 
          auth:false 
        } 
      },
    {
        path: '/',
        beforeEnter: checkRoleRoute,
        name: 'login'
            
    },
    {
        path: '/login',
        component: LoginComponent, 
    },
    
    {
        path: '/admin',
        component: AdminComponent,
        name: 'Admin', 
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem('token')) {
                next();
            } else {
    localStorage.removeItem('token');
    localStorage.removeItem('token','user','loggedIn');
    localStorage.removeItem('loggedIn');
    localStorage.removeItem('user');
    next('/login');
            }
        },
        redirect: '/admin/dashboard',
        children: [
            //Admin Routes
            
                {
                    path: 'dashboard', 
                    beforeEnter: isADMIN,
                    component: AdminDashboardComponent, 
                    name: 'Dashboard'

                    
                },
                {
                    path: 'student/list',
                    beforeEnter: isADMIN,
                    component: AdminStudentListComponent,
                    name: 'Student List'
                },
                {
                    path: 'colleges',
                    beforeEnter: isADMIN,
                    component: CollegesComponent,
                    name: 'Colleges'
                },
                {
                    path: 'campuses',
                    beforeEnter: isADMIN,
                    component: CampusesComponent,
                    name: 'Campuses'
                },
                {
                    path: 'programs',
                    beforeEnter: isADMIN,
                    component: ProgramsComponent,
                    name: 'Programs'
                },
                {
                    path: 'sections',
                    beforeEnter: isADMIN,
                    component: SectionsComponent,
                    name: 'Sections'
                },
                {
                    path: 'semesters',
                    beforeEnter: isADMIN,
                    component: SemestersComponent,
                    name: 'Semesters'
                },
                {
                    path: 'graduations',
                    beforeEnter: isADMIN,
                    component: GraduationsComponent,
                    name: 'Graduations'
                },
                {
                    path: 'purposes',
                    beforeEnter: isADMIN,
                    component: PurposesComponent,
                    name: 'Purposes'
                },
                {
                    path: 'stcouncils',
                    beforeEnter: isADMIN,
                    component: AdminStudentCouncilComponent,
                    name: 'Student Council'
                },
                {
                    path: 'deans',
                    beforeEnter: isADMIN,
                    component: AdminDeanComponent,
                    name: 'Dean'
                },
                {
                    path: 'osas',
                    beforeEnter: isADMIN,
                    component: AdminOSASComponent,
                    name: 'OSAS'
                },
                {
                    path: 'library',
                    beforeEnter: isADMIN,
                    component: AdminLibraryComponent,
                    name: 'Library'
                },
                {
                    path: 'cashiers',
                    beforeEnter: isADMIN,
                    component: AdminCashierComponent,
                    name: 'Cashier'
                },
                {
                    path: 'registrar',
                    beforeEnter: isADMIN,
                    component: AdminRegistrarComponent,
                    name: 'Registrar'
                }, 
                {
                    path: 'staff',
                    beforeEnter: isADMIN,
                    component: AdminStaffComponent,
                    name: 'Staff'
                }, 
                {
                    path: 'programdirector',
                    beforeEnter: isADMIN,
                    component: AdminProgramDirectorComponent,
                    name: 'Program Director'
                }, 
                //Signatories Routes
                {
                    path: 'clearance/requests',
                    beforeEnter: isADMIN,
                    component: AdminClearanceRequestsComponent,
                    name: 'Clearance Requests'
                },
                {
                    path: 'cleared/clearances',
                    beforeEnter: isADMIN,
                    component: AdminClearedClearancesComponent,
                    name: 'Cleared Clearances'
                },
                {
                    path: 'submitted/clearances',
                    beforeEnter: isADMIN,
                    component: AdminSubmittedClearancesComponent,
                    name: 'Submitted Clearances'
                },
                {
                    path: 'sias/account',
                    beforeEnter: isADMIN,
                    component: AdminSiasAccountComponent,
                    name: 'Sias Account'
                },
                //Student Routes
                {
                    path: 'active/clearance',
                    beforeEnter: isADMIN,
                    component: AdminActiveClearanceComponent,
                    name: 'Active Clearance'
                },
                {
                    path: 'clearance/list',
                    beforeEnter: isADMIN,
                    component: AdminClearanceListComponent,
                    name: 'Clearance List'
                },
                {
                    path: 'deficiency/list',
                    beforeEnter: isADMIN,
                    component: AdminDeficiencyListComponent,
                    name: 'Deficiency List'
                },
                //Settings Route
                {
                    path: 'settings',
                    beforeEnter: isADMIN,
                    component: SettingsComponent,
                    name: 'Settings'
                },
                //Users Route
                {
                    path: 'users',
                    beforeEnter: isADMIN,
                    component: AdminUsersComponent,
                    name: 'Users'
                },

        ],
      
         
        
    },

     //REGISTRAR Links
     {
        path: '/registrar',
        component: REGISTRARComponent,
        name: 'REGISTRAR', 
        redirect: '/registrar/dashboard',
        children: [
            //PD Routes
            
                {
                    path: 'dashboard', 
                    beforeEnter: isREGISTRAR,
                    component: PDDashboardComponent, 
                    name: 'Dashboard'

                    
                },
                {
                    path: 'student/list',
                    beforeEnter: isREGISTRAR,
                    component: PDStudentListComponent,
                    name: 'Student List'
                },
                
                //Signatories Routes
                {
                    path: 'clearance/requests',
                    beforeEnter: isREGISTRAR,
                    component: PDClearanceRequestsComponent,
                    name: 'Clearance Requests'
                },
                {
                    path: 'cleared/clearances',
                    beforeEnter: isREGISTRAR,
                    component: PDClearedClearancesComponent,
                    name: 'Cleared Clearances'
                },
                {
                    path: 'submitted/clearances',
                    beforeEnter: isREGISTRAR,
                    component: PDSubmittedClearancesComponent,
                    name: 'Submitted Clearances'
                }, 
                {
                    path: 'clearance/list',
                    beforeEnter: isREGISTRAR,
                    component: PDClearanceListComponent,
                    name: 'Clearance List'
                },
                {
                    path: 'deficiency/list',
                    beforeEnter: isREGISTRAR,
                    component: PDDeficiencyListComponent,
                    name: 'Deficiency List'
                },
                {
                    path: 'deficiency/all/list',
                    beforeEnter: isREGISTRAR,
                    component: RegistrarDeficiencyListComponent,
                    name: 'Registrar Deficiency List'
                },
                //Settings Route
                {
                    path: 'settings',
                    beforeEnter: isREGISTRAR,
                    component: SettingsComponent,
                    name: 'Settings'
                },
                {
                    path: 'users',
                    beforeEnter: isREGISTRAR,
                    component: SignatoryUsersComponent,
                    name: 'Users'
                },

        ],
     
    },

    //pd links
    {
        path: '/pd',
        component: PDComponent,
        name: 'pd', 
        redirect: '/pd/dashboard',
        children: [
            //PD Routes
            
                {
                    path: 'dashboard', 
                    beforeEnter: isPD,
                    component: PDDashboardComponent, 
                    name: 'Dashboard'

                    
                },
                {
                    path: 'student/list',
                    beforeEnter: isPD,
                    component: PDStudentListComponent,
                    name: 'Student List'
                },
                
                //Signatories Routes
                {
                    path: 'clearance/requests',
                    beforeEnter: isPD,
                    component: PDClearanceRequestsComponent,
                    name: 'Clearance Requests'
                },
                {
                    path: 'cleared/clearances',
                    beforeEnter: isPD,
                    component: PDClearedClearancesComponent,
                    name: 'Cleared Clearances'
                },
                {
                    path: 'submitted/clearances',
                    beforeEnter: isPD,
                    component: PDSubmittedClearancesComponent,
                    name: 'Submitted Clearances'
                }, 
                {
                    path: 'clearance/list',
                    beforeEnter: isPD,
                    component: PDClearanceListComponent,
                    name: 'Clearance List'
                },
                {
                    path: 'deficiency/list',
                    beforeEnter: isPD,
                    component: PDDeficiencyListComponent,
                    name: 'Deficiency List'
                },
                //Settings Route
                {
                    path: 'settings',
                    beforeEnter: isPD,
                    component: SettingsComponent,
                    name: 'Settings'
                },
                {
                    path: 'users',
                    beforeEnter: isPD,
                    component: SignatoryUsersComponent,
                    name: 'Users'
                },

        ],
       
    },
     //SGS student links
     {
        path: '/sgs/student',
        component: SGSStudentComponent,
        name: 'SGSStudent', 
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem('token')) {
                next();
            } else {
    localStorage.removeItem('token');
    localStorage.removeItem('token','user','loggedIn');
    localStorage.removeItem('loggedIn');
    localStorage.removeItem('user');
    next('/login');
            }
        },
        redirect: '/sgs/student/active/clearance',
        children: [
            //Admin Routes
            
                {
                    path: 'dashboard', 
                    beforeEnter: isSTUDENTSGS,
                    component: AdminDashboardComponent, 
                    name: 'Dashboard'

                    
                },
                //Student Routes
                {
                    path: 'active/clearance',
                    beforeEnter: isSTUDENTSGS,
                    component: SGSAdminActiveClearanceComponent,
                    name: 'Active Clearance'
                },
                {
                    path: 'clearance/list',
                    beforeEnter: isSTUDENTSGS,
                    component: STUDENTClearanceListComponent,
                    name: 'Clearance List'
                },
                {
                    path: 'deficiency/list',
                    beforeEnter: isSTUDENTSGS,
                    component: AdminDeficiencyListComponent,
                    name: 'Deficiency List'
                },
                {
                    path: 'sias/account',
                    beforeEnter: isSTUDENTSGS,
                    component: StudentSIASComponent,
                    name: 'SIAS Account'
                },
                //Settings Route
                {
                    path: 'settings',
                    beforeEnter: isSTUDENTSGS,
                    component: StudentSettingsComponent,
                    name: 'Settings'
                }, 

        ],
       
         
        
    }, 
    //DEAN Links
    {
        path: '/dean',
        component: DEANComponent,
        name: 'DEAN', 
        redirect: '/dean/dashboard',
        children: [
             
            
                {
                    path: 'dashboard', 
                    beforeEnter: isDEAN,
                    component: PDDashboardComponent, 
                    name: 'Dashboard'

                    
                },
                {
                    path: 'student/list',
                    beforeEnter: isDEAN,
                    component: PDStudentListComponent,
                    name: 'Student List'
                },
                
                //Signatories Routes
                {
                    path: 'clearance/requests',
                    beforeEnter: isDEAN,
                    component: PDClearanceRequestsComponent,
                    name: 'Clearance Requests'
                },
                {
                    path: 'cleared/clearances',
                    beforeEnter: isDEAN,
                    component: PDClearedClearancesComponent,
                    name: 'Cleared Clearances'
                },
                {
                    path: 'submitted/clearances',
                    beforeEnter: isDEAN,
                    component: PDSubmittedClearancesComponent,
                    name: 'Submitted Clearances'
                }, 
                {
                    path: 'clearance/list',
                    beforeEnter: isDEAN,
                    component: PDClearanceListComponent,
                    name: 'Clearance List'
                },
                {
                    path: 'deficiency/list',
                    beforeEnter: isDEAN,
                    component: PDDeficiencyListComponent,
                    name: 'Deficiency List'
                },
                //Settings Route
                {
                    path: 'settings',
                    beforeEnter: isDEAN,
                    component: SettingsComponent,
                    name: 'Settings'
                },

        ],
        
    },
        //student links
        {
            path: '/student',
            component: StudentComponent,
            name: 'Student Page', 
            beforeEnter: (to, from, next) => {
                if (localStorage.getItem('token')) {
                    next();
                } else {
        localStorage.removeItem('token');
        localStorage.removeItem('token','user','loggedIn');
        localStorage.removeItem('loggedIn');
        localStorage.removeItem('user');
        next('/login');
                }
            },
            redirect: '/student/active/clearance',
            children: [
                //Admin Routes
                
                    {
                        path: 'dashboard', 
                        beforeEnter: isSTUDENT,
                        component: AdminDashboardComponent, 
                        name: 'Dashboard'
    
                        
                    },
                    //Student Routes
                    {
                        path: 'active/clearance',
                        beforeEnter: isSTUDENT,
                        component: AdminActiveClearanceComponent,
                        name: 'Active Clearance'
                    },
                    {
                        path: 'clearance/list',
                        beforeEnter: isSTUDENT,
                        component: STUDENTClearanceListComponent,
                        name: 'Clearance List'
                    },
                    {
                        path: 'deficiency/list',
                        beforeEnter: isSTUDENT,
                        component: AdminDeficiencyListComponent,
                        name: 'Deficiency List'
                    },
                    {
                        path: 'sias/account',
                        beforeEnter: isSTUDENT,
                        component: StudentSIASComponent,
                        name: 'SIAS Account'
                    },
                    //Settings Route
                    {
                        path: 'settings',
                        beforeEnter: isSTUDENT,
                        component: StudentSettingsComponent,
                        name: 'Settings'
                    }, 
    
            ],
       
             
            
        }, 
       
       
    
    
    
];
function requireLogin(to, from, next) {
    axios.get('/api/v1/verify',{'user' : localStorage.getItem('user')})
    .then(res => next())
    .catch(err => next('/login'))
}
function isADMIN(to, from, next) {
    axios.get('/api/v1/verify')
    .then(res => {
            if(res.data.user_role.role.name=="admin") {next();} 
            else{next('/');}
        }).catch(err => {
    localStorage.removeItem('token');
    localStorage.removeItem('token','user','loggedIn');
    localStorage.removeItem('loggedIn');
    localStorage.removeItem('user');
    next('/login');})
}
function isPD(to, from, next) {
 axios.get('/api/v1/verify')
    .then(res => {
            if(res.data.user_role.role.name=="pd") {next();} 
            else{next('/');}
        }).catch(err => {
    localStorage.removeItem('token');
    localStorage.removeItem('token','user','loggedIn');
    localStorage.removeItem('loggedIn');
    localStorage.removeItem('user');
    next('/login');})
}
function isDEAN(to, from, next) {
 axios.get('/api/v1/verify')
    .then(res => {
            if(res.data.user_role.role.name=="dean") {next();} 
            else{next('/');}
        }).catch(err => {
    localStorage.removeItem('token');
    localStorage.removeItem('token','user','loggedIn');
    localStorage.removeItem('loggedIn');
    localStorage.removeItem('user');
    next('/login');})
}
function isSTCOUNCIL(to, from, next) {
 axios.get('/api/v1/verify')
    .then(res => {
            if(res.data.user_role.role.name=="stcouncil") {next();} 
            else{next('/');}
        }).catch(err => {
            localStorage.removeItem('token');
            localStorage.removeItem('token','user','loggedIn');
            localStorage.removeItem('loggedIn');
            localStorage.removeItem('user');
            next('/login');})
}
function isCASHIER(to, from, next) {
 axios.get('/api/v1/verify')
    .then(res => {
            if(res.data.user_role.role.name=="cashier") {next();} 
            else{next('/');}
        }).catch(err => {
    localStorage.removeItem('token');
    localStorage.removeItem('token','user','loggedIn');
    localStorage.removeItem('loggedIn');
    localStorage.removeItem('user');
    next('/login');})
}
function isLIBRARY(to, from, next) {
 axios.get('/api/v1/verify')
    .then(res => {
            if(res.data.user_role.role.name=="library") {next();} 
            else{next('/');}
        }).catch(err => {
    localStorage.removeItem('token');
    localStorage.removeItem('token','user','loggedIn');
    localStorage.removeItem('loggedIn');
    localStorage.removeItem('user');
    next('/login');})
}
function isOSAS(to, from, next) {
 axios.get('/api/v1/verify')
    .then(res => {
            if(res.data.user_role.role.name=="osas") {next();} 
            else{next('/');}
        }).catch(err => {
    localStorage.removeItem('token');
    localStorage.removeItem('token','user','loggedIn');
    localStorage.removeItem('loggedIn');
    localStorage.removeItem('user');
    next('/login');})
}
function isREGISTRAR(to, from, next) {
 axios.get('/api/v1/verify')
    .then(res => {
            if(res.data.user_role.role.name=="registrar") {next();} 
            else{next('/');}
        }).catch(err => {
    localStorage.removeItem('token');
    localStorage.removeItem('token','user','loggedIn');
    localStorage.removeItem('loggedIn');
    localStorage.removeItem('user');
    next('/login');})
}
function isREGISTRARSTAFF(to, from, next) {
 axios.get('/api/v1/verify')
    .then(res => {
            if(res.data.user_role.role.name=="registrarstaff") {next();} 
            else{next('/');}
        }).catch(err => {
    localStorage.removeItem('token');
    localStorage.removeItem('token','user','loggedIn');
    localStorage.removeItem('loggedIn');
    localStorage.removeItem('user');
    next('/login');})
}
function isADVISER(to, from, next) {
 axios.get('/api/v1/verify')
    .then(res => {
            if(res.data.user_role.role.name=="adviser") {next();} 
            else{next('/');}
        }).catch(err => {
    localStorage.removeItem('token');
    localStorage.removeItem('token','user','loggedIn');
    localStorage.removeItem('loggedIn');
    localStorage.removeItem('user');
    next('/login');})
}
function isPRINCIPAL(to, from, next) {
 axios.get('/api/v1/verify')
    .then(res => {
            if(res.data.user_role.role.name=="principal") {next();} 
            else{next('/');}
        }).catch(err => {
    localStorage.removeItem('token');
    localStorage.removeItem('token','user','loggedIn');
    localStorage.removeItem('loggedIn');
    localStorage.removeItem('user');
    next('/login');})
}
function isSTUDENT(to, from, next) {
 axios.get('/api/v1/verify')
    .then(res => {
        //console.log(to)
        //console.log(from)
        //console.log(next)
        //console.log(res.data.user_role.role.name)
            if(res.data.user_role.role.name=="student"  && res.data.student != "Laboratory High School"  && res.data.student != "School of Graduate Studies and Research") {next();} 
            else{next('/');}
        }).catch(err => {
    localStorage.removeItem('token');
    localStorage.removeItem('token','user','loggedIn');
    localStorage.removeItem('loggedIn');
    localStorage.removeItem('user');
    next('/login');})
}
function isSTUDENTLHS(to, from, next) {
    axios.get('/api/v1/verify')
       .then(res => {
               if(res.data.user_role.role.name=="student" && res.data.student == "Laboratory High School") {next();} 
               else{next('/');}
           }).catch(err => {
       localStorage.removeItem('token');
       localStorage.removeItem('token','user','loggedIn');
       localStorage.removeItem('loggedIn');
       localStorage.removeItem('user');
       next('/login');})
   }
   function isSTUDENTSGS(to, from, next) {
    axios.get('/api/v1/verify')
       .then(res => {
            if(res.data.user_role.role.name=="student" && res.data.student == "School of Graduate Studies and Research") {next();} 
               else{next('/');}
           }).catch(err => {
       localStorage.removeItem('token');
       localStorage.removeItem('token','user','loggedIn');
       localStorage.removeItem('loggedIn');
       localStorage.removeItem('user');
       next('/login');})
   }
function checkRoleRoute(to, from, next) {
    axios.get('/api/v1/verify')
        .then(res => {
                if(res.data.user_role.role.name=="admin") {
                    next('/admin');
                } 
                else if(res.data.user_role.role.name=="pd") {
                    next('/pd');
                } 
                else if(res.data.user_role.role.name=="dean") {
                    next('/dean');
                } 
                else if(res.data.user_role.role.name=="stcouncil") {
                    next('/stcouncil');
                } 
                else if(res.data.user_role.role.name=="cashier") {
                    next('/cashier');
                } 
                else if(res.data.user_role.role.name=="library") {
                    next('/library');
                } 
                else if(res.data.user_role.role.name=="osas") {
                    if (res.data.staff_campus == "Goa Campus") {
                        next('/osas/goa');
                    }
                    else{
                    next('/osas');
                    }
                } 
                else if(res.data.user_role.role.name=="registrar") {
                    next('/registrar');
                } 
                else if(res.data.user_role.role.name=="registrarstaff") {
                    next('/registrarstaff');
                } 
                else if(res.data.user_role.role.name=="adviser") {
                    next('/adviser');
                } 
                else if(res.data.user_role.role.name=="principal") {
                    next('/principal');
                } 
                else if(res.data.user_role.role.name=="student") {
                    if (res.data.student == "School of Graduate Studies and Research") {
                        next('/sgs/student');
                    } 
                    else{
                    next('/student');
                    }
                } 
                else{
                    localStorage.removeItem('token');
                    localStorage.removeItem('token','user','loggedIn');
                    localStorage.removeItem('loggedIn');
                    localStorage.removeItem('user');
                    next('/login');
                }
            }
            )
        .catch(err => {
            {
                localStorage.removeItem('token');
                localStorage.removeItem('token','user','loggedIn');
                localStorage.removeItem('loggedIn');
                localStorage.removeItem('user');
                next('/login');}
        })
}
const router = new VueRouter({routes})
// router.beforeEach((to,from,next) => {
//     const token = localStorage.getItem('token') || null
//     window.axios.defaults.headers['Authorization'] = "Bearer" + token;
//     next();
// })
export default new VueRouter({routes});
