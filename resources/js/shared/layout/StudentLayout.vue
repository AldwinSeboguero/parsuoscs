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
        icon: "mdi-home-assistant",
        text: "Active Clearance",
        link: "/student/active/clearance",
      },
      {
        icon: "mdi-text-box-outline",
        text: "Clearance List",
        link: "/student/clearance/list",
      },
      {
        icon: "mdi-text-box-remove-outline",
        text: "List of Deficiency",
        link: "/student/deficiency/list",
      }, 
       
      { icon: "mdi-cog", text: "Settings", link: "/student/settings" }, 
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
