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
mounted(){
  axios.get('/api/v1/verify')
    .then(res => {
            if(res.data.user_role.role.name=="cashier") 
            {
              this.navigationList =[
              {
                icon: "mdi-clipboard-list",
                text: "Clearance Requests",
                link: "/cashier/clearance/requests",
              },
              {
                icon: "mdi-history",
                text: "Student Deficiencies",
                link: "/cashier/deficiency/list",
              }, 
              {
                icon: "mdi-account-check",
                text: "Approved Requests",
                link: "/cashier/cleared/clearances",
              },
              {
                icon: "mdi-send-circle",
                text: "Submitted Clearances",
                link: "/cashier/submitted/clearances",
              }, 
              {
                icon: "mdi-account-multiple",
                text: "Student List",
                link: "/cashier/student/list",
              }, 
              { icon: "mdi-cog", text: "Settings", link: "/cashier/settings" }, 
            ];
            } 
            else if(res.data.user_role.role.name=="pd") {
                this.navigationList =[
                              {
                                icon: "mdi-clipboard-list",
                                text: "Clearance Requests",
                                link: "/pd/clearance/requests",
                              },
                              {
                                icon: "mdi-history",
                                text: "Student Deficiencies",
                                link: "/pd/deficiency/list",
                              }, 
                              {
                                icon: "mdi-account-check",
                                text: "Approved Requests",
                                link: "/pd/cleared/clearances",
                              },
                              {
                                icon: "mdi-send-circle",
                                text: "Submitted Clearances",
                                link: "/pd/submitted/clearances",
                              }, 
                              {
                                icon: "mdi-account-multiple",
                                text: "Student List",
                                link: "/pd/student/list",
                              }, 
                              { icon: "mdi-cog", text: "Settings", link: "/pd/settings" }, 
                            ];              
            }
            else if(res.data.user_role.role.name=="stcouncil") {
                this.navigationList =[
                              {
                                icon: "mdi-clipboard-list",
                                text: "Clearance Requests",
                                link: "/stcouncil/clearance/requests",
                              },
                              {
                                icon: "mdi-history",
                                text: "Student Deficiencies",
                                link: "/stcouncil/deficiency/list",
                              }, 
                              {
                                icon: "mdi-account-check",
                                text: "Approved Requests",
                                link: "/stcouncil/cleared/clearances",
                              },
                              {
                                icon: "mdi-send-circle",
                                text: "Submitted Clearances",
                                link: "/stcouncil/submitted/clearances",
                              }, 
                              {
                                icon: "mdi-account-multiple",
                                text: "Student List",
                                link: "/stcouncil/student/list",
                              }, 
                              { icon: "mdi-cog", text: "Settings", link: "/stcouncil/settings" }, 
                            ];
            }
            else if(res.data.user_role.role.name=="registrarstaff") {
                this.navigationList =[
                              {
                                icon: "mdi-clipboard-list",
                                text: "Clearance Requests",
                                link: "/registrarstaff/clearance/requests",
                              },
                              {
                                icon: "mdi-history",
                                text: "Student Deficiencies",
                                link: "/registrarstaff/deficiency/list",
                              }, 
                              {
                                icon: "mdi-account-check",
                                text: "Approved Requests",
                                link: "/registrarstaff/cleared/clearances",
                              },
                              {
                                icon: "mdi-send-circle",
                                text: "Submitted Clearances",
                                link: "/registrarstaff/submitted/clearances",
                              }, 
                              {
                                icon: "mdi-account-multiple",
                                text: "Student List",
                                link: "/registrarstaff/student/list",
                              }, 
                              { icon: "mdi-cog", text: "Settings", link: "/registrarstaff/settings" }, 
                            ];
            }
            else if(res.data.user_role.role.name=="registrar") {
                this.navigationList =[
                              {
                                icon: "mdi-clipboard-list",
                                text: "Clearance Requests",
                                link: "/registrar/clearance/requests",
                              },
                              {
                                icon: "mdi-history",
                                text: "Student Deficiencies",
                                link: "/registrar/deficiency/list",
                              }, 
                              {
                                icon: "mdi-account-check",
                                text: "Approved Requests",
                                link: "/registrar/cleared/clearances",
                              },
                              {
                                icon: "mdi-send-circle",
                                text: "Submitted Clearances",
                                link: "/registrar/submitted/clearances",
                              }, 
                              {
                                icon: "mdi-account-multiple",
                                text: "Student List",
                                link: "/registrar/student/list",
                              }, 
                              { icon: "mdi-cog", text: "Settings", link: "/registrar/settings" }, 
                            ];
            }
            else if(res.data.user_role.role.name=="cashier") {
                this.navigationList =[
                              {
                                icon: "mdi-clipboard-list",
                                text: "Clearance Requests",
                                link: "/cashier/clearance/requests",
                              },
                              {
                                icon: "mdi-history",
                                text: "Student Deficiencies",
                                link: "/cashier/deficiency/list",
                              }, 
                              {
                                icon: "mdi-account-check",
                                text: "Approved Requests",
                                link: "/cashier/cleared/clearances",
                              },
                              {
                                icon: "mdi-send-circle",
                                text: "Submitted Clearances",
                                link: "/cashier/submitted/clearances",
                              }, 
                              {
                                icon: "mdi-account-multiple",
                                text: "Student List",
                                link: "/cashier/student/list",
                              }, 
                              { icon: "mdi-cog", text: "Settings", link: "/cashier/settings" }, 
                            ];
            }
            else if(res.data.user_role.role.name=="library") {
                this.navigationList =[
                              {
                                icon: "mdi-clipboard-list",
                                text: "Clearance Requests",
                                link: "/library/clearance/requests",
                              },
                              {
                                icon: "mdi-history",
                                text: "Student Deficiencies",
                                link: "/library/deficiency/list",
                              }, 
                              {
                                icon: "mdi-account-check",
                                text: "Approved Requests",
                                link: "/library/cleared/clearances",
                              },
                              {
                                icon: "mdi-send-circle",
                                text: "Submitted Clearances",
                                link: "/library/submitted/clearances",
                              }, 
                              {
                                icon: "mdi-account-multiple",
                                text: "Student List",
                                link: "/library/student/list",
                              }, 
                              { icon: "mdi-cog", text: "Settings", link: "/library/settings" }, 
                            ];
            }
            else if(res.data.user_role.role.name=="osas") {
                this.navigationList =[
                              {
                                icon: "mdi-clipboard-list",
                                text: "Clearance Requests",
                                link: "/osas/clearance/requests",
                              },
                              {
                                icon: "mdi-history",
                                text: "Student Deficiencies",
                                link: "/osas/deficiency/list",
                              }, 
                              {
                                icon: "mdi-account-check",
                                text: "Approved Requests",
                                link: "/osas/cleared/clearances",
                              },
                              {
                                icon: "mdi-send-circle",
                                text: "Submitted Clearances",
                                link: "/osas/submitted/clearances",
                              }, 
                              {
                                icon: "mdi-account-multiple",
                                text: "Student List",
                                link: "/osas/student/list",
                              }, 
                              { icon: "mdi-cog", text: "Settings", link: "/osas/settings" }, 
                            ];
            }
            else if(res.data.user_role.role.name=="dean") {
                this.navigationList =[
                              {
                                icon: "mdi-clipboard-list",
                                text: "Clearance Requests",
                                link: "/dean/clearance/requests",
                              },
                              {
                                icon: "mdi-history",
                                text: "Student Deficiencies",
                                link: "/dean/deficiency/list",
                              }, 
                              {
                                icon: "mdi-account-check",
                                text: "Approved Requests",
                                link: "/dean/cleared/clearances",
                              },
                              {
                                icon: "mdi-send-circle",
                                text: "Submitted Clearances",
                                link: "/dean/submitted/clearances",
                              }, 
                              {
                                icon: "mdi-account-multiple",
                                text: "Student List",
                                link: "/dean/student/list",
                              }, 
                              { icon: "mdi-cog", text: "Settings", link: "/dean/settings" }, 
                            ];
            }
        }).catch(err => {
    
    })
  
},
computed: {
   currentRouteName() {
        return this.$route.name;
    }, 
  
  },
  
 
};

</script>
