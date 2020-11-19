import Vue from 'vue';
import VueRouter from 'vue-router';
import LoginComponent from './components/LoginComponent';
import AdminComponent from './components/AdminComponent';
//Admin Import Component
import AdminDashboardComponent from './components/admin/DashboardComponent';
import AdminDashboardComponentt from './components/admin/DashboardComponentt';
import StudentListComponent from './components/StudentListComponent';
import CollegesComponent from './components/admin/CollegesComponent';
import CampusesComponent from './components/admin/CampusesComponent';
import GraduationsComponent from './components/admin/GraduationsComponent';
import ProgramsComponent from './components/admin/ProgramsComponent';
import PurposesComponent from './components/admin/PurposesComponent';
import SectionsComponent from './components/admin/SectionsComponent';
import SemestersComponent from './components/admin/SemestersComponent';
//Signatories Import Component
import ClearanceRequestsComponent from './components/ClearanceRequestsComponent';
import ClearedClearancesComponent from './components/ClearedClearancesComponent';
import SubmittedClearancesComponent from './components/SubmittedClearancesComponent';
//Students Import Component
import ActiveClearanceComponent from './components/ActiveClearanceComponent';
import ClearanceListComponent from './components/ClearanceListComponent';
import DeficiencyListComponent from './components/DeficiencyListComponent';
//Settings Import Component
import SettingsComponent from './components/SettingsComponent';
//Users Import Component
import UsersComponent from './components/admin/UsersComponent';
//Signatories Admin Component
import CashierComponent from './components/signatory/CashierComponent';
import DeanComponent from './components/signatory/DeanComponent';
import LibraryComponent from './components/signatory/LibraryComponent';
import OSASComponent from './components/signatory/OSASComponent';
import ProgramDirectorComponent from './components/signatory/ProgramDirectorComponent';
import StudentCouncilComponent from './components/signatory/StudentCouncilComponent';
import RegistrarComponent from './components/signatory/RegistrarComponent';

Vue.use(VueRouter);

const routes =[
    {
        path: '/',
        redirect: '/login'
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
            next('/login');
            }
          },
        redirect: '/admin/dashboard',
        children: [
            //Admin Routes
                {
                    path: 'dashboard',
                    component: AdminDashboardComponent,
                    name: 'Dashboard'
                },
                {
                    path: 'student/list',
                    component: StudentListComponent,
                    name: 'Student List'
                },
                {
                    path: 'colleges',
                    component: CollegesComponent,
                    name: 'Colleges'
                },
                {
                    path: 'campuses',
                    component: CampusesComponent,
                    name: 'Campuses'
                },
                {
                    path: 'programs',
                    component: ProgramsComponent,
                    name: 'Programs'
                },
                {
                    path: 'sections',
                    component: SectionsComponent,
                    name: 'Sections'
                },
                {
                    path: 'semesters',
                    component: SemestersComponent,
                    name: 'Semesters'
                },
                {
                    path: 'graduations',
                    component: GraduationsComponent,
                    name: 'Graduations'
                },
                {
                    path: 'purposes',
                    component: PurposesComponent,
                    name: 'Purposes'
                },
                {
                    path: 'stcouncils',
                    component: StudentCouncilComponent,
                    name: 'Student Council'
                },
                {
                    path: 'deans',
                    component: DeanComponent,
                    name: 'Dean'
                },
                {
                    path: 'osas',
                    component: OSASComponent,
                    name: 'OSAS'
                },
                {
                    path: 'library',
                    component: LibraryComponent,
                    name: 'Library'
                },
                {
                    path: 'cashiers',
                    component: CashierComponent,
                    name: 'Cashier'
                },
                {
                    path: 'registrar',
                    component: RegistrarComponent,
                    name: 'Registrar'
                }, 
                {
                    path: 'programdirector',
                    component: ProgramDirectorComponent,
                    name: 'Program Director'
                }, 
                //Signatories Routes
                {
                    path: 'clearance/requests',
                    component: ClearanceRequestsComponent,
                    name: 'Clearance Requests'
                },
                {
                    path: 'cleared/clearances',
                    component: ClearedClearancesComponent,
                    name: 'Cleared Clearances'
                },
                {
                    path: 'submitted/clearances',
                    component: SubmittedClearancesComponent,
                    name: 'Submitted Clearances'
                },
                //Student Routes
                {
                    path: 'active/clearance',
                    component: ActiveClearanceComponent,
                    name: 'Active Clearance'
                },
                {
                    path: 'clearance/list',
                    component: ClearanceListComponent,
                    name: 'Clearance List'
                },
                {
                    path: 'deficiency/list',
                    component: DeficiencyListComponent,
                    name: 'Deficiency List'
                },
                //Settings Route
                {
                    path: 'settings',
                    component: SettingsComponent,
                    name: 'Settings'
                },
                //Users Route
                {
                    path: 'users',
                    component: UsersComponent,
                    name: 'Users'
                },

        ],
        // beforeEnter: (to, from, next) =>{
        //    axios.get('/api/verify',{'user' : localStorage.getItem('user')})
        //    .then(res => next())
        //    .catch(err => next('/login'))
        // }
    },
    
    
];
const router = new VueRouter({routes})
router.beforeEach((to,from,next) => {
    const token = localStorage.getItem('token') || null
    window.axios.defaults.headers['Authorization'] = "Bearer" + token;
    next();
})
export default new VueRouter({routes});
