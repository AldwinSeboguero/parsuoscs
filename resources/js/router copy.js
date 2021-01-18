import Vue from 'vue';
import VueRouter from 'vue-router';
import LoginComponent from './components/LoginComponent';
import AdminComponent from './components/AdminComponent';
import StudentComponent from './components/student/StudentComponent';
import CASHIERComponent from './components/cashier/CASHIERComponent'; 
import LIBRARYComponent from './components/library/LIBRARYComponent';
import DEANComponent from './components/dean/DEANComponent';
import REGISTRARSTAFFComponent from './components/registrarstaff/REGISTRARSTAFFComponent';
import REGISTRARComponent from './components/registrar/REGISTRARComponent';
import OSASComponent from './components/osas/OSASComponent';
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
//Signatories Import Component
import AdminClearanceRequestsComponent from './components/ClearanceRequestsComponent';
import AdminClearedClearancesComponent from './components/ClearedClearancesComponent';
import AdminSubmittedClearancesComponent from './components/SubmittedClearancesComponent';
//Students Import Component
import AdminActiveClearanceComponent from './components/ActiveClearanceComponent';
import AdminClearanceListComponent from './components/ClearanceListComponent';
import AdminDeficiencyListComponent from './components/DeficiencyListComponent';
//Settings Import Component
import SettingsComponent from './components/SettingsComponent';
import StudentSettingsComponent from './components/student/SettingsComponent';
//Users Import Component
import AdminUsersComponent from './components/admin/UsersComponent';
//Signatories Admin Component
import AdminCashierComponent from './components/admin/signatory/CashierComponent';
import AdminDeanComponent from './components/admin/signatory/DeanComponent';
import AdminLibraryComponent from './components/admin/signatory/LibraryComponent';
import AdminOSASComponent from './components/admin/signatory/OSASComponent';
import AdminProgramDirectorComponent from './components/admin/signatory/ProgramDirectorComponent';
import AdminStudentCouncilComponent from './components/admin/signatory/StudentCouncilComponent';
import AdminRegistrarComponent from './components/admin/signatory/RegistrarComponent';

//Program Director Import
import PDComponent from './components/programdirector/PDComponent'; 
import PDDashboardComponent from './components/programdirector/DashboardComponent'; 
import PDStudentListComponent from './components/programdirector/StudentListComponent'; 
import PDClearanceRequestsComponent from './components/programdirector/ClearanceRequestsComponent';
import PDClearedClearancesComponent from './components/programdirector/ClearedClearancesComponent';
import PDSubmittedClearancesComponent from './components/programdirector/SubmittedClearancesComponent';
import PDClearanceListComponent from './components/programdirector/ClearanceListComponent';
import PDDeficiencyListComponent from './components/programdirector/DeficiencyListComponent';  

Vue.use(VueRouter);

const routes =[
    {
        path: '/',
        beforeEnter: checkRoleRoute,
            
    },
    {
        path: '/login',
        component: LoginComponent
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
        beforeEnter: (to, from, next) =>{
           axios.get('/api/v1/verify',{'user' : localStorage.getItem('user')})
           .then(res => {next();})
           .catch(err => {
           localStorage.removeItem('token');
           localStorage.removeItem('token','user','loggedIn');
           localStorage.removeItem('loggedIn');
           localStorage.removeItem('user');
           next('/login');})
        },
         
        
    },
    //Cashier Links
    {
        path: '/cashier',
        component: CASHIERComponent,
        name: 'CASHIER', 
        redirect: '/cashier/dashboard',
        children: [
            //PD Routes
            
                {
                    path: 'dashboard', 
                    beforeEnter: isCASHIER,
                    component: PDDashboardComponent, 
                    name: 'PDDashboard'

                    
                },
                {
                    path: 'student/list',
                    beforeEnter: isCASHIER,
                    component: PDStudentListComponent,
                    name: 'PDStudent List'
                },
                
                //Signatories Routes
                {
                    path: 'clearance/requests',
                    beforeEnter: isCASHIER,
                    component: PDClearanceRequestsComponent,
                    name: 'PDClearance Requests'
                },
                {
                    path: 'cleared/clearances',
                    beforeEnter: isCASHIER,
                    component: PDClearedClearancesComponent,
                    name: 'PDCleared Clearances'
                },
                {
                    path: 'submitted/clearances',
                    beforeEnter: isCASHIER,
                    component: PDSubmittedClearancesComponent,
                    name: 'PDSubmitted Clearances'
                }, 
                {
                    path: 'clearance/list',
                    beforeEnter: isCASHIER,
                    component: PDClearanceListComponent,
                    name: 'PDClearance List'
                },
                {
                    path: 'deficiency/list',
                    beforeEnter: isCASHIER,
                    component: PDDeficiencyListComponent,
                    name: 'PDDeficiency List'
                },
                //Settings Route
                {
                    path: 'settings',
                    beforeEnter: isCASHIER,
                    component: SettingsComponent,
                    name: 'PDSettings'
                },

        ],
        beforeEnter: (to, from, next) =>{
           axios.get('/api/v1/verify',{'user' : localStorage.getItem('user')})
           .then(res => {next();})
           .catch(err => {
           localStorage.removeItem('token');
           localStorage.removeItem('token','user','loggedIn');
           localStorage.removeItem('loggedIn');
           localStorage.removeItem('user');
           next('/login');})
        },
    },
    //REGISTRARSTAFF Links
    {
        path: '/osas',
        component: OSASComponent,
        name: 'OSAS', 
        redirect: '/osas/dashboard',
        children: [
            //PD Routes
            
                {
                    path: 'dashboard', 
                    beforeEnter: isOSAS,
                    component: PDDashboardComponent, 
                    name: 'PDDashboard'

                    
                },
                {
                    path: 'student/list',
                    beforeEnter: isOSAS,
                    component: PDStudentListComponent,
                    name: 'PDStudent List'
                },
                
                //Signatories Routes
                {
                    path: 'clearance/requests',
                    beforeEnter: isOSAS,
                    component: PDClearanceRequestsComponent,
                    name: 'PDClearance Requests'
                },
                {
                    path: 'cleared/clearances',
                    beforeEnter: isOSAS,
                    component: PDClearedClearancesComponent,
                    name: 'PDCleared Clearances'
                },
                {
                    path: 'submitted/clearances',
                    beforeEnter: isOSAS,
                    component: PDSubmittedClearancesComponent,
                    name: 'PDSubmitted Clearances'
                }, 
                {
                    path: 'clearance/list',
                    beforeEnter: isOSAS,
                    component: PDClearanceListComponent,
                    name: 'PDClearance List'
                },
                {
                    path: 'deficiency/list',
                    beforeEnter: isOSAS,
                    component: PDDeficiencyListComponent,
                    name: 'PDDeficiency List'
                },
                //Settings Route
                {
                    path: 'settings',
                    beforeEnter: isOSAS,
                    component: SettingsComponent,
                    name: 'PDSettings'
                },

        ],
        beforeEnter: (to, from, next) =>{
           axios.get('/api/v1/verify',{'user' : localStorage.getItem('user')})
           .then(res => {next();})
           .catch(err => {
           localStorage.removeItem('token');
           localStorage.removeItem('token','user','loggedIn');
           localStorage.removeItem('loggedIn');
           localStorage.removeItem('user');
           next('/login');})
        },
    },
    //REGISTRARSTAFF Links
    {
        path: '/registrarstaff',
        component: REGISTRARSTAFFComponent,
        name: 'REGISTRARSTAFF', 
        redirect: '/registrarstaff/dashboard',
        children: [
            //PD Routes
            
                {
                    path: 'dashboard', 
                    beforeEnter: isREGISTRARSTAFF,
                    component: PDDashboardComponent, 
                    name: 'PDDashboard'

                    
                },
                {
                    path: 'student/list',
                    beforeEnter: isREGISTRARSTAFF,
                    component: PDStudentListComponent,
                    name: 'PDStudent List'
                },
                
                //Signatories Routes
                {
                    path: 'clearance/requests',
                    beforeEnter: isREGISTRARSTAFF,
                    component: PDClearanceRequestsComponent,
                    name: 'PDClearance Requests'
                },
                {
                    path: 'cleared/clearances',
                    beforeEnter: isREGISTRARSTAFF,
                    component: PDClearedClearancesComponent,
                    name: 'PDCleared Clearances'
                },
                {
                    path: 'submitted/clearances',
                    beforeEnter: isREGISTRARSTAFF,
                    component: PDSubmittedClearancesComponent,
                    name: 'PDSubmitted Clearances'
                }, 
                {
                    path: 'clearance/list',
                    beforeEnter: isREGISTRARSTAFF,
                    component: PDClearanceListComponent,
                    name: 'PDClearance List'
                },
                {
                    path: 'deficiency/list',
                    beforeEnter: isREGISTRARSTAFF,
                    component: PDDeficiencyListComponent,
                    name: 'PDDeficiency List'
                },
                //Settings Route
                {
                    path: 'settings',
                    beforeEnter: isREGISTRARSTAFF,
                    component: SettingsComponent,
                    name: 'PDSettings'
                },

        ],
        beforeEnter: (to, from, next) =>{
           axios.get('/api/v1/verify',{'user' : localStorage.getItem('user')})
           .then(res => {next();})
           .catch(err => {
           localStorage.removeItem('token');
           localStorage.removeItem('token','user','loggedIn');
           localStorage.removeItem('loggedIn');
           localStorage.removeItem('user');
           next('/login');})
        },
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
                    name: 'PDDashboard'

                    
                },
                {
                    path: 'student/list',
                    beforeEnter: isREGISTRAR,
                    component: PDStudentListComponent,
                    name: 'PDStudent List'
                },
                
                //Signatories Routes
                {
                    path: 'clearance/requests',
                    beforeEnter: isREGISTRAR,
                    component: PDClearanceRequestsComponent,
                    name: 'PDClearance Requests'
                },
                {
                    path: 'cleared/clearances',
                    beforeEnter: isREGISTRAR,
                    component: PDClearedClearancesComponent,
                    name: 'PDCleared Clearances'
                },
                {
                    path: 'submitted/clearances',
                    beforeEnter: isREGISTRAR,
                    component: PDSubmittedClearancesComponent,
                    name: 'PDSubmitted Clearances'
                }, 
                {
                    path: 'clearance/list',
                    beforeEnter: isREGISTRAR,
                    component: PDClearanceListComponent,
                    name: 'PDClearance List'
                },
                {
                    path: 'deficiency/list',
                    beforeEnter: isREGISTRAR,
                    component: PDDeficiencyListComponent,
                    name: 'PDDeficiency List'
                },
                //Settings Route
                {
                    path: 'settings',
                    beforeEnter: isREGISTRAR,
                    component: SettingsComponent,
                    name: 'PDSettings'
                },

        ],
        beforeEnter: (to, from, next) =>{
           axios.get('/api/v1/verify',{'user' : localStorage.getItem('user')})
           .then(res => {next();})
           .catch(err => {
           localStorage.removeItem('token');
           localStorage.removeItem('token','user','loggedIn');
           localStorage.removeItem('loggedIn');
           localStorage.removeItem('user');
           next('/login');})
        },
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
                    name: 'PDDashboard'

                    
                },
                {
                    path: 'student/list',
                    beforeEnter: isDEAN,
                    component: PDStudentListComponent,
                    name: 'PDStudent List'
                },
                
                //Signatories Routes
                {
                    path: 'clearance/requests',
                    beforeEnter: isDEAN,
                    component: PDClearanceRequestsComponent,
                    name: 'PDClearance Requests'
                },
                {
                    path: 'cleared/clearances',
                    beforeEnter: isDEAN,
                    component: PDClearedClearancesComponent,
                    name: 'PDCleared Clearances'
                },
                {
                    path: 'submitted/clearances',
                    beforeEnter: isDEAN,
                    component: PDSubmittedClearancesComponent,
                    name: 'PDSubmitted Clearances'
                }, 
                {
                    path: 'clearance/list',
                    beforeEnter: isDEAN,
                    component: PDClearanceListComponent,
                    name: 'PDClearance List'
                },
                {
                    path: 'deficiency/list',
                    beforeEnter: isDEAN,
                    component: PDDeficiencyListComponent,
                    name: 'PDDeficiency List'
                },
                //Settings Route
                {
                    path: 'settings',
                    beforeEnter: isDEAN,
                    component: SettingsComponent,
                    name: 'PDSettings'
                },

        ],
        beforeEnter: (to, from, next) =>{
           axios.get('/api/v1/verify',{'user' : localStorage.getItem('user')})
           .then(res => {next();})
           .catch(err => {
           localStorage.removeItem('token');
           localStorage.removeItem('token','user','loggedIn');
           localStorage.removeItem('loggedIn');
           localStorage.removeItem('user');
           next('/login');})
        },
    },
    //LIBRARY Links
    {
        path: '/library',
        component: LIBRARYComponent,
        name: 'LIBRARY', 
        redirect: '/library/dashboard',
        children: [
             
            
                {
                    path: 'dashboard', 
                    beforeEnter: isLIBRARY,
                    component: PDDashboardComponent, 
                    name: 'PDDashboard'

                    
                },
                {
                    path: 'student/list',
                    beforeEnter: isLIBRARY,
                    component: PDStudentListComponent,
                    name: 'PDStudent List'
                },
                
                //Signatories Routes
                {
                    path: 'clearance/requests',
                    beforeEnter: isLIBRARY,
                    component: PDClearanceRequestsComponent,
                    name: 'PDClearance Requests'
                },
                {
                    path: 'cleared/clearances',
                    beforeEnter: isLIBRARY,
                    component: PDClearedClearancesComponent,
                    name: 'PDCleared Clearances'
                },
                {
                    path: 'submitted/clearances',
                    beforeEnter: isLIBRARY,
                    component: PDSubmittedClearancesComponent,
                    name: 'PDSubmitted Clearances'
                }, 
                {
                    path: 'clearance/list',
                    beforeEnter: isLIBRARY,
                    component: PDClearanceListComponent,
                    name: 'PDClearance List'
                },
                {
                    path: 'deficiency/list',
                    beforeEnter: isLIBRARY,
                    component: PDDeficiencyListComponent,
                    name: 'PDDeficiency List'
                },
                //Settings Route
                {
                    path: 'settings',
                    beforeEnter: isLIBRARY,
                    component: SettingsComponent,
                    name: 'PDSettings'
                },

        ],
        beforeEnter: (to, from, next) =>{
           axios.get('/api/v1/verify',{'user' : localStorage.getItem('user')})
           .then(res => {next();})
           .catch(err => {
           localStorage.removeItem('token');
           localStorage.removeItem('token','user','loggedIn');
           localStorage.removeItem('loggedIn');
           localStorage.removeItem('user');
           next('/login');})
        },
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
                    name: 'PDDashboard'

                    
                },
                {
                    path: 'student/list',
                    beforeEnter: isPD,
                    component: PDStudentListComponent,
                    name: 'PDStudent List'
                },
                
                //Signatories Routes
                {
                    path: 'clearance/requests',
                    beforeEnter: isPD,
                    component: PDClearanceRequestsComponent,
                    name: 'PDClearance Requests'
                },
                {
                    path: 'cleared/clearances',
                    beforeEnter: isPD,
                    component: PDClearedClearancesComponent,
                    name: 'PDCleared Clearances'
                },
                {
                    path: 'submitted/clearances',
                    beforeEnter: isPD,
                    component: PDSubmittedClearancesComponent,
                    name: 'PDSubmitted Clearances'
                }, 
                {
                    path: 'clearance/list',
                    beforeEnter: isPD,
                    component: PDClearanceListComponent,
                    name: 'PDClearance List'
                },
                {
                    path: 'deficiency/list',
                    beforeEnter: isPD,
                    component: PDDeficiencyListComponent,
                    name: 'PDDeficiency List'
                },
                //Settings Route
                {
                    path: 'settings',
                    beforeEnter: isPD,
                    component: SettingsComponent,
                    name: 'PDSettings'
                },

        ],
        beforeEnter: (to, from, next) =>{
           axios.get('/api/v1/verify',{'user' : localStorage.getItem('user')})
           .then(res => {next();})
           .catch(err => {
           localStorage.removeItem('token');
           localStorage.removeItem('token','user','loggedIn');
           localStorage.removeItem('loggedIn');
           localStorage.removeItem('user');
           next('/login');})
        },
    },
        //student links
        {
            path: '/student',
            component: StudentComponent,
            name: 'Student', 
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
            redirect: '/student/dashboard',
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
                        component: AdminClearanceListComponent,
                        name: 'Clearance List'
                    },
                    {
                        path: 'deficiency/list',
                        beforeEnter: isSTUDENT,
                        component: AdminDeficiencyListComponent,
                        name: 'Deficiency List'
                    },
                    //Settings Route
                    {
                        path: 'settings',
                        beforeEnter: isSTUDENT,
                        component: StudentSettingsComponent,
                        name: 'Settings'
                    }, 
    
            ],
            beforeEnter: (to, from, next) =>{
               axios.get('/api/v1/verify',{'user' : localStorage.getItem('user')})
               .then(res => {next();})
               .catch(err => {
               localStorage.removeItem('token');
               localStorage.removeItem('token','user','loggedIn');
               localStorage.removeItem('loggedIn');
               localStorage.removeItem('user');
               next('/login');})
            },
             
            
        }, 
    
    
    
];
function requireLogin(to, from, next) {
    axios.get('/api/v1/verify',{'user' : localStorage.getItem('user')})
    .then(res => next())
    .catch(err => next('/login'))
}
// function backLogin(){
//     localStorage.removeItem('token');
//     localStorage.removeItem('token','user','loggedIn');
//     localStorage.removeItem('loggedIn');
//     localStorage.removeItem('user');
//     next('/login');
// }
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
            if(res.data.user_role.role.name=="pricipal") {next();} 
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
            if(res.data.user_role.role.name=="student") {next();} 
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
                    next('/osas');
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
                    next('/student');
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
