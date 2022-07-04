<template>
  <v-app id="inspire" >
 
   <OSCSNavigationDrawer :drawer="drawer" :navigationList="navigationList"/>
   <OSCSAppBar :openDrawer="openDrawer" />
   

    <v-main class="grey lighten-5">
      <v-container fluid >
        <router-view></router-view>
      </v-container>
     
      
    </v-main>
  </v-app>
</template>
<script>
import OSCSNavigationDrawer from './OSCSNavigationDrawer.vue'
import OSCSAppBar from './OSCSAppBar.vue'
export default {

  name: "main-app",
  props: {
    source: String,
  },
  components:{
    OSCSNavigationDrawer,
    OSCSAppBar,
  },
  data: () => ({
    dialog: false,
    menu: false,
    drawer: null,
    user: '',
    role: '',
      scrollOptions:{
                      height:'100%',  
                  },
    navigationList: [
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
  methods: {
    openDrawer(){
      this.drawer = !this.drawer;
    },
  },
computed: {
   currentRouteName() {
        return this.$route.name;
    }, 
  
  },
  
 
};

</script>
