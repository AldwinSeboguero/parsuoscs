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
      // {
      //   icon: "mdi-view-dashboard",
      //   text: "Dashboard",
      //   link: "/admin/dashboard",
      // },
      {
        icon: "mdi-account-multiple-plus",
        text: "Student List",
        link: "/admin/student/list",
      },
      //  {
      //   icon: "mdi-clipboard-list",
      //   text: "SIAS Accounts",
      //   link: "/admin/sias/account",
      // },
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
        link: "/admin/signatories",
        // children: [
        //   { text: "Staff", link: "/admin/signatories"},
        //   { text: "Student Council", link: "/admin/stcouncils" },
        //   { text: "Program Director", link: "/admin/programdirector" },
        //   { text: "Dean", link: "/admin/deans" },
        //   { text: "OSAS", link: "/admin/osas" },
        //   { text: "Library", link: "/admin/library" },
        //   { text: "Cashier", link: "/admin/cashiers" },
        //   { text: "Registrar", link: "/admin/registrar" },
        // ],
      },
      {
        icon: "mdi-clipboard-list",
        text: "Clearance Requests",
        link: "/admin/clearance/requests",
      },
      {
        icon: "mdi-account-check",
        text: "Approved Requests",
        link: "/admin/cleared/clearances",
      },
      {
        icon: "mdi-send-circle",
        text: "Submitted Clearances",
        link: "/admin/submitted/clearances",
      },
      // {
      //   icon: "mdi-clipboard-check",
      //   text: "Active Clearance",
      //   link: "/admin/active/clearance",
      // },
      // {
      //   icon: "mdi-clipboard-text",
      //   text: "Clearance List",
      //   link: "/admin/clearance/list",
      // },
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
 
<style>
  .v-application a{
  
  list-style: none;
  text-decoration: none;
}
ul span{list-style: none; margin: 0; padding: 0; 
    text-decoration:none;}
li {text-align: left;}
.wizard {
  list-style: none;
  margin: 0;
  padding: 0;
  counter-reset: bth-wizard-steps;
  display: flex;
  flex-wrap: no-wrap;
  overflow: hidden;
  /* min-width: 30%; */
  /* width:10%; */
  height: 30px;
}
.wizard .wizard__item-arrows:after, .wizard .wizard__item-arrows:before {
  content: " ";
  display: block;
  position: absolute;
  height: 30px;
}
.wizard .wizard__item-arrows:before {
  border-top: 20px solid transparent;
  border-bottom: 20px solid transparent;
  border-left: 20px solid white;
  top: -4px;
  left: -1px;
  z-index: 1;
}
.wizard .wizard__item-arrows:after {
  border-top: 16px solid transparent;
  border-bottom: 16px solid transparent;
  border-left: 18px solid #EBEDF0;
  top: 0;
  right: -17px;
  left: auto;
  z-index: 2;
}
.wizard .wizard__item {
  background-color: #EBEDF0;
  color: #595959;
  counter-increment: bth-wizard-steps;
  flex-grow: 1;
  font-size: 10px;
  font-weight: 600;
  line-height: 30px;
  min-width: 30px;
  max-width: 100%;
  text-transform: uppercase;
  display: flex;
  align-items: center;
  flex-wrap: no-wrap;
  padding-left: 34px;
  padding-right: 8px;
  cursor: pointer;
  position: relative;
  
}
.wizard .wizard__item:before {
  font-family: 'Material Design Icons';
    content: "\F0056";
  font-size: 14px;
  font-weight: 600;
  min-width: 20px;
  display: flex;
  width: 20px;
  height: 20px;
  background-color: white;
  margin-right: 8px;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
}
.wizard .wizard__item:first-child {
  padding-left: 8px;
}
.wizard .wizard__item:first-child .wizard__item-arrows:before {
  display: none;
}
.wizard .wizard__item:last-child .wizard__item-arrows:after {
  display: none;
}
.wizard .wizard__item-desc {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  list-style: none;
  text-decoration:none;
  align-items: center;
  justify-content: center;
  height: 30px;
}
.wizard .wizard__item--active {
  background: linear-gradient(to right, #0d47a1, #0d47a1, #1A237E);
  cursor: default;
  list-style: none;
  text-decoration:none;
  color: #ffffff;
  min-width: auto;
}
.wizard .wizard__item--active .wizard__item-arrows:after {
  border-left-color: #7FAADC;
}
.wizard .wizard__item--active:before {
  color: #295D99;
}
.wizard .wizard__item--visited {
  background-color: #CFDFF2;
  color: #595959;
}
.wizard .wizard__item--visited .wizard__item-arrows:after {
  border-left-color: #CFDFF2;
}
.wizard .wizard__item--visited:before {
  font-family: "Material Icons";
  color: #295D99;
  content: "check";
  font-size: 18px;
  text-transform: none;
  
  list-style: none;
  text-decoration:none;
}
.wizard .wizard__item--disabled, .wizard .wizard__item [disabled] {
  background-color: #F5F7FA;
  color: #c0c0c0;
  cursor: default;
}
.wizard .wizard__item--disabled .wizard__item-arrows:after, .wizard .wizard__item [disabled] .wizard__item-arrows:after {
  border-left-color: #F5F7FA;
}
.wizard .wizard__item--disabled:before, .wizard .wizard__item [disabled]:before {
  color: #c0c0c0;
  content: counter(bth-wizard-steps);
}

.router-link-active{
  color: #000 !important;
  caret-color: #000 !important;

}
.router-link-exact-active {
  color: #fff !important;
  caret-color: #fff !important;
  cursor: default;
}
</style>

<style scoped>

.v-text-field--outlined >>> fieldset {
  border-color: #212121 solid 1px;
  

}
.v-text-field >>> input {
    height: 32px;
    border-color: #212121 solid 1px;


}

.form-control{
    height: 36px;
}
</style>