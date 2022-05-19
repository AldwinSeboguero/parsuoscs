<template>
  <v-app id="inspire" >
    <v-navigation-drawer
      v-model="drawer"
      color="#1d232e"
      width="220px"
      src="images/sidebar/sidebar-2.jpg" 
      dark
      
      app
    >
    <template v-slot:img="props">
      <v-img
        :gradient="`to bottom left, rgba(00,00,00,.7), rgba(00,00,00,.7)`"
        
        v-bind="props"
      />
    </template>
      <template v-slot:prepend>
        <v-list-item class="margin-bottom:0" >
          <v-list-item-avatar class="text-center" style="margin:0; margin-right:10px;" >
        
                  <v-icon dark large style="margin-left: 10px; padding-right:10px" >
                    mdi-account-circle
                  </v-icon>
          </v-list-item-avatar>

          <v-list-item-content>
            <v-list-item-title class="caption text-wrap" > {{user.name}}</v-list-item-title>
            <v-list-item-subtitle class="caption">{{role.description}}</v-list-item-subtitle>
          </v-list-item-content>
          
        </v-list-item>
        
      </template>

     
  <div  v-slimscroll="scrollOptions">
      <v-list dense shaped>
        <template v-for="item in items">
          <v-row v-if="item.heading" :key="item.heading" align="center">
            <v-col cols="4">
              <v-subheader v-if="item.heading">
                {{ item.heading }}
              </v-subheader>
            </v-col>
            <v-col cols="6" class="text-center">
              <a href="#!" class="body-2 black--text">EDIT</a>
            </v-col>
          </v-row>
          <v-list-group
            v-else-if="item.children"
            :key="item.text"
            v-model="item.model" 
            append-icon=""
            color="grey lighten-2"
          >
            <template v-slot:activator>
              <v-list-item-action  style="margin-right: 10px">
                <v-icon>{{item.icon}}</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title class="caption white--text text--darken-4 font-weight-light">
                  {{ item.text }}
                </v-list-item-title>
              </v-list-item-content>
               <v-list-item-action  style="margin-right: 10px">
                <v-icon>{{ item.model ? 'mdi-chevron-up' : item['icon-alt'] }}</v-icon>
              </v-list-item-action>
            </template>
            <v-list-item
              v-for="(child, i) in item.children"
              :key="i"
              :to="child.link"
              link
              
            >
              <v-list-item-action v-if="child.icon" style="margin-right: 10px">
                <v-icon>{{ child.icon }}</v-icon>
              </v-list-item-action>
              <v-list-item-content 
              style="padding-left: 20px"
                 >
                <v-list-item-title class="caption white--text text--darken-2 font-weight-light">
                  {{ child.text }}
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-group>
          <v-list-item v-else :key="item.text" :to="item.link" link  color="grey lighten-2">
            <v-list-item-action style="margin-right: 10px">
              <v-icon small>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title class="caption white--text text--darken-2 font-weight-light">
                {{ item.text }}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </template>
        <v-list-item @click="logout">
            <v-list-item-action style="margin-right: 10px">
              <v-icon small>mdi-logout-variant</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title class="caption white--text text--darken-2 font-weight-light">
                Logout
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
      </v-list>
  </div>
  <template v-slot:append>
        <div class="pa-2">
         <v-card
          flat
          tile
          dark
          width="100%"
           style="background: transparent"
          class="overline  lighten-1 text-center ">
          <!-- <v-card-text class="pb-0 mb-0">
            <v-btn class="mx-1 black--text" icon>
              <v-icon size="24px"> mdi-web </v-icon>
            </v-btn>
            <v-btn class="mx-1 black--text" icon>
              <v-icon size="24px"> mdi-facebook </v-icon>
            </v-btn>
            <v-btn class="mx-1 black--text" icon>
              <v-icon size="24px"> mdi-twitter </v-icon>
            </v-btn>
            <v-btn class="mx-1 black--text" icon>
              <v-icon size="24px"> mdi-youtube </v-icon>
            </v-btn>
          </v-card-text>  -->
        
          <!-- <v-divider class="grey mx-xs-auto mx-lg-auto mx-sm-auto  mx-md-auto hidden-sm-and-down" width="300"></v-divider> -->

           <v-card-text class="caption">
            {{ new Date().getFullYear() }} &copy;
            <strong>ICTMO, Partido State University</strong>
          </v-card-text>
        </v-card>
        </div>
      </template>
    </v-navigation-drawer>
    <v-app-bar
     
      app
      outlined
      class="white"
      height="50"
      elevation="1"
      
    >
    <!-- src="https://cdn.vuetifyjs.com/images/backgrounds/bg-2.jpg" -->
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" class="hidden-lg-and-up"></v-app-bar-nav-icon>
      <!-- <v-avatar
          size="32px"
          item
        >
            <v-img
                src="http://parsu-oscs.herokuapp.com/img/profile/picture.jpg"
                alt="Vuetify"
            ></v-img>
        </v-avatar> -->
      <v-toolbar-title style="width: 300px" class="ml-0 pl-4 font-weight-medium blue-grey--text text--darken-4">
        <span>{{ currentRouteName }}</span>
      </v-toolbar-title>

      <v-spacer></v-spacer>
      
      <v-btn icon small class="mr-2">
        <v-badge
        bordered
        overlap
        content="0"
        small
        dot
      >
        <v-icon small>mdi-email</v-icon>
        
      </v-badge>
      </v-btn>
      <v-btn icon small class="mr-2">
        <v-badge
        bordered
        overlap
        content="0"
        small
        dot
        >
        <v-icon small>mdi-bell</v-icon>
        </v-badge>
      </v-btn>
      <v-btn icon small>
        <v-avatar size="24" item>
          <v-img src="images/psu_logo.png" alt="parsu_logo"></v-img
        ></v-avatar>
      </v-btn>
    </v-app-bar>

    <v-main class="grey lighten-5 elevation-0" >
      <v-container fluid  >
        <router-view></router-view>
      </v-container>
      <!-- <v-footer  app bottom  padless dens>
          <v-card
          flat
          tile
          width="100%"
           style="background: transparent"
          class=" white lighten-1 black--text text-center "
        > -->
          <!-- <v-card-text class="pb-0 mb-0">
            <v-btn class="mx-1 black--text" icon>
              <v-icon size="24px"> mdi-web </v-icon>
            </v-btn>
            <v-btn class="mx-1 black--text" icon>
              <v-icon size="24px"> mdi-facebook </v-icon>
            </v-btn>
            <v-btn class="mx-1 black--text" icon>
              <v-icon size="24px"> mdi-twitter </v-icon>
            </v-btn>
            <v-btn class="mx-1 black--text" icon>
              <v-icon size="24px"> mdi-youtube </v-icon>
            </v-btn>
          </v-card-text>  -->
        
          <!-- <v-divider class="grey mx-xs-auto mx-lg-auto mx-sm-auto  mx-md-auto hidden-sm-and-down" width="300"></v-divider> -->

          <!-- <v-card-text class="black--text">
            {{ new Date().getFullYear() }} &copy;
            <strong>ICTMO, Partido State University</strong>
          </v-card-text>
        </v-card>
      </v-footer> -->
      
    </v-main>
  </v-app>
</template>
<script>
import { ParticlesBg } from "particles-bg-vue";
export default {
  components: {
    ParticlesBg
  },
  name: "main-app",
  props: {
    source: String,
  },
  data: () => ({
    dialog: false,
    drawer: null,
    user: '',
    role: '',
     scrollOptions:{
                    height:'100%',  
                },
    items: [
      {
        icon: "mdi-view-dashboard",
        text: "Dashboard",
        link: "/admin/dashboard",
      },
      {
        icon: "mdi-account-multiple-plus",
        text: "Student List",
        link: "/admin/student/list",
      },
       {
        icon: "mdi-clipboard-list",
        text: "SIAS Accounts",
        link: "/admin/sias/account",
      },
      {
        icon: "mdi-video-input-component",
        "icon-alt": "mdi-chevron-down",
        text: "Admin Setup",
        model: false,
        children: [
          { text: "Campuses", link: "/admin/campuses" },
          { text: "Colleges", link: "/admin/colleges" },
          { text: "Programs", link: "/admin/programs" },
          { text: "Sections", link: "/admin/sections" },
          { text: "Semester", link: "/admin/semesters" },
          { text: "Graduation Schedule", link: "/admin/graduations" },
          { text: "Purposes", link: "/admin/purposes" },
        ],
      },
      {
        icon: "mdi-account-settings",
        "icon-alt": "mdi-chevron-down",
        text: "Signatories",
        model: false,
        children: [
          { text: "Staff", link: "/admin/staff"},
          { text: "Student Council", link: "/admin/stcouncils" },
          { text: "Program Director", link: "/admin/programdirector" },
          { text: "Dean", link: "/admin/deans" },
          { text: "OSAS", link: "/admin/osas" },
          { text: "Library", link: "/admin/library" },
          { text: "Cashier", link: "/admin/cashiers" },
          { text: "Registrar", link: "/admin/registrar" },
        ],
      },
      {
        icon: "mdi-clipboard-list",
        text: "Clearance Requests",
        link: "/admin/clearance/requests",
      },
      {
        icon: "mdi-account-check",
        text: "Cleared Student Clearances",
        link: "/admin/cleared/clearances",
      },
      {
        icon: "mdi-send-circle",
        text: "Submitted Clearances",
        link: "/admin/submitted/clearances",
      },
      {
        icon: "mdi-clipboard-check",
        text: "Active Clearance",
        link: "/admin/active/clearance",
      },
      {
        icon: "mdi-clipboard-text",
        text: "Clearance List",
        link: "/admin/clearance/list",
      },
      {
        icon: "mdi-history",
        text: "List of Deficiency",
        link: "/admin/deficiency/list",
      },
      {
        icon: "mdi-account-group-outline",
        text: "User Accounts",
        link: "/admin/users",
      },
      { icon: "mdi-cog", text: "Settings", link: "/admin/settings" }, 
    ],
  }),
  watch: {
    dialog(val) {
      val || this.close();
    },
  },
  // created() {
  //   this.initialize();
  // },
computed: { 
   currentRouteName() {
        return this.$route.name;
    },
    loggedIn:{
      get(){
        return this.$store.state.currentUser.loggedIn;
      }
    },
    // currentUser:{
    //   get(){
    //     return this.$store.state.currentUser.user;
    //   }
    // }
  },
  methods: {
        logout(){
          axios.post('logout')
          .then(resposnse => {
             localStorage.removeItem('token');
             this.$router.push("/login");
          });
    }
  },
  created(){
        axios.interceptors.response.use(
      (response) => response,
      (error) => {
        if (error.response.status !== 419) return Promise.reject(error)
        window.location.reload()
      }
    )
    axios.defaults.headers['Authorization'] = "Bearer " + localStorage.getItem("token");
    this.$store.dispatch('currentUser/getUser');
          axios.get('/api/v1/getUser')
      .then(res => {
        this.user = res.data.user
        this.role = res.data.role.role
        console.dir(res)
      })
      
    //   axios.interceptors.response.use(null, (error) => {
    // if(error.response && error.response.status == 419) {
    //     // session timed out | not authenticated
    //     // window.location.href = '/login';
    //   localStorage.removeItem('token','user','loggedIn');
    //   localStorage.removeItem('loggedIn');
    //   localStorage.removeItem('user');
    //   this.$router.push("/login");
    // }
    // return Promise.reject(error);
// });
  }, 
  // methods: {
  //   initialize() {
  //     axios.interceptors.request.use(
  //       (config) => {
  //         this.loading = true;
  //         return config;
  //       },
  //       (error) => {
  //         this.loading = false;
  //         return Promise.reject(error);
  //       }
  //     );

  //     axios.interceptors.response.use(
  //       (response) => {
  //         this.loading = false;
  //         return response;
  //       },
  //       (error) => {
  //         this.loading = false;
  //         return Promise.reject(error);
  //       }
  //     ); 
  //     axios.get('/api/authuser')
  //     .then(res => {
  //       this.user = localStorage.getItem('user')
  //       console.dir(res)
  //     })
  //     .catch(err => {
  //       console.error(err); 
  //     })
  //   },
  //   logout(){
  //     localStorage.removeItem('token','user','loggedIn');
  //     localStorage.removeItem('loggedIn');
  //     localStorage.removeItem('user');
  //     this.$router.push("/login");
  //   }
  // },
};

</script>


