<template>
<v-navigation-drawer
      v-model="drawer"
      
      width="220px"
      
      
      app
    >
    
      <template v-slot:prepend>
        <v-list-item dark class="elevation-2" style="max-height: 50px; background: linear-gradient(to right, #1A237E, #1A237E, #0D47A1);">
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
  <v-divider></v-divider>
     
  <div  v-slimscroll="scrollOptions">
      <v-list dense shaped>
        <template v-for="item in navigationList">
          <v-row v-if="item.heading" :key="item.heading" align="center">
            <v-col cols="4">
              <v-subheader v-if="item.heading">
                {{ item.heading }}
              </v-subheader>
            </v-col>
          
          </v-row>
          <v-list-group
            v-else-if="item.children"
            :key="item.text"
            v-model="item.model" 
            append-icon=""
           color="indigo"
          >
            <template v-slot:activator>
              <v-list-item-action  style="margin-right: 10px">
                <v-icon>{{item.icon}}</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title class="caption   text--darken-1 font-weight-light">
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
                <v-list-item-title class="caption   text--darken-1 font-weight-light">
                  {{ child.text }}
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-group>
          <v-list-item v-else :key="item.text" :to="item.link" link class="mb-1"  active-class="blue--text text--accent-4 font-weight-bold">
            <v-list-item-action style="margin-right: 10px">
              <v-icon small>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title class="caption   text--darken-1 font-weight-medium">
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
              <v-list-item-title class="caption   text--darken-2 font-weight-medium">
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
          
          width="100%"
           style="background: transparent"
          class="overline  lighten-1 text-center ">
           <v-card-text class="caption">
            {{ new Date().getFullYear() }} &copy;
            <strong>ICTMO, Partido State University</strong>
          </v-card-text>
        </v-card>
        </div>
      </template>
    </v-navigation-drawer>
   
</template>

<script>
export default {
     props: {
    source: String,
    drawer: null,
    navigationList: '',
  },
  data: () => ({
    dialog: false,
    menu: false,
    user: '',
    role: '',
    scrollOptions:{
                    height:'100%',  
                },

  }),
  created(){
        axios.interceptors.response.use(
      (response) => response,
      (error) => {
        if (error.response.status !== 419) return Promise.reject(error)
        window.location.reload()
      }
    )
    axios.defaults.headers['Authorization'] = "Bearer " + localStorage.getItem("token");
    // this.$store.dispatch('currentUser/getUser');
          axios.get('/api/v1/getUser')
      .then(res => {
        this.user = res.data.user
        this.role = res.data.role.role
        
      })

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
 
}

</script>