<template>
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      :clipped="$vuetify.breakpoint.lgAndUp"
      app
    >
      <template v-slot:prepend>
        <v-list-item two-line>
          <v-list-item-avatar>
             <v-avatar color="indigo">
                  <v-icon dark>
                    mdi-account-circle
                  </v-icon>
                </v-avatar>
          </v-list-item-avatar>

          <v-list-item-content>
            <v-list-item-title> {{user.name}}</v-list-item-title>
            <v-list-item-subtitle>{{role.description}}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </template>

      <v-divider></v-divider>
  <div  v-slimscroll="scrollOptions">
      <v-list dense>
        <template v-for="item in items">
          <v-row v-if="item.heading" :key="item.heading" align="center">
            <v-col cols="6">
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
            :prepend-icon="item.model ? item.icon : item['icon-alt']"
            append-icon=""
          >
            <template v-slot:activator>
              <v-list-item-content>
                <v-list-item-title>
                  {{ item.text }}
                </v-list-item-title>
              </v-list-item-content>
            </template>
            <v-list-item
              v-for="(child, i) in item.children"
              :key="i"
              :to="child.link"
              link
            >
              <v-list-item-action v-if="child.icon">
                <v-icon>{{ child.icon }}</v-icon>
              </v-list-item-action>
              <v-list-item-content 
              style="padding-left: 20px">
                <v-list-item-title>
                  {{ child.text }}
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-group>
          <v-list-item v-else :key="item.text" :to="item.link" link>
            <v-list-item-action>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>
                {{ item.text }}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </template>
        <v-list-item @click="logout">
            <v-list-item-action>
              <v-icon>mdi-logout-variant</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>
                Logout
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
      </v-list>
  </div>
    </v-navigation-drawer>
    <v-app-bar
      :clipped-left="$vuetify.breakpoint.lgAndUp"
      app
      color="primary accent-4"
      dark
      flat
      src="https://cdn.vuetifyjs.com/images/backgrounds/bg-2.jpg"
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <!-- <v-avatar
          size="32px"
          item
        >
            <v-img
                src="http://parsu-oscs.herokuapp.com/img/profile/picture.jpg"
                alt="Vuetify"
            ></v-img>
        </v-avatar> -->
      <v-toolbar-title style="width: 300px" class="ml-0 pl-4">
        <span>ParSU OSCS</span>
      </v-toolbar-title>

      <v-spacer></v-spacer>
      <v-btn icon>
        <v-icon>mdi-email</v-icon>
      </v-btn>
      <v-btn icon>
        <v-icon>mdi-bell</v-icon>
      </v-btn>
       <v-menu
      v-model="menu"
      :close-on-content-click="false"
      :nudge-width="200"
      offset-x
    >
      <template v-slot:activator="{ on, attrs }">
       
        <v-btn icon large v-bind="attrs"
          v-on="on">
        <v-avatar size="32px" item>
          <v-img src="images/psu_logo.png" alt="parsu_logo"></v-img
        ></v-avatar>
      </v-btn>
      </template>

      <v-card>
        <v-list>
           <v-list-item>
            <v-list-item-avatar>
                 <v-avatar color="indigo">
                  <v-icon dark>
                    mdi-account-circle
                  </v-icon>
                </v-avatar>
            </v-list-item-avatar>

            <v-list-item-content>
              <v-list-item-title>{{user.name}}</v-list-item-title>
              <v-list-item-subtitle>{{role.description}}</v-list-item-subtitle>
            </v-list-item-content>

            <v-list-item-action>
              <!-- <v-btn
                :class="fav ? 'red--text' : ''"
                icon
                @click="fav = !fav"
              >
                <v-icon>mdi-heart</v-icon>
              </v-btn> -->
            </v-list-item-action>
          </v-list-item>
        </v-list>

        <v-divider></v-divider>

        <v-list>
              <v-list-item :key="Settings" to="/library/settings" link @click="menu=false">
            <v-list-item-action>
              <v-icon>mdi-cog</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>
                Settings
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
           <v-list-item @click="logout">
            <v-list-item-action>
              <v-icon>mdi-logout-variant</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>
                Logout
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>

        <v-card-actions>
          <v-spacer></v-spacer>

        
        </v-card-actions>
      </v-card>
    </v-menu>
    
    </v-app-bar>

    <v-main>
      <v-container fluid>
        <router-view></router-view>
      </v-container>
      <v-footer dark padless>
        <v-card
          flat
          tile
          width="100%"
          class="white lighten-1 black--text text-center"
        >
          <v-card-text>
            <v-btn class="mx-4 black--text" icon>
              <v-icon size="24px"> mdi-web </v-icon>
            </v-btn>
            <v-btn class="mx-4 black--text" icon>
              <v-icon size="24px"> mdi-facebook </v-icon>
            </v-btn>
            <v-btn class="mx-4 black--text" icon>
              <v-icon size="24px"> mdi-twitter </v-icon>
            </v-btn>
            <v-btn class="mx-4 black--text" icon>
              <v-icon size="24px"> mdi-youtube </v-icon>
            </v-btn>
          </v-card-text>

          <v-card-text class="black--text pt-0"> </v-card-text>

          <v-divider class="grey"></v-divider>

          <v-card-text class="black--text">
            {{ new Date().getFullYear() }} &copy;
            <strong>ICTMO, Partido State University</strong>
          </v-card-text>
        </v-card>
      </v-footer>
      
    </v-main>
  </v-app>
</template>

<script>
export default {
  name: "main-app",
  props: {
    source: String,
  },
  data: () => ({
    dialog: false,
    drawer: null,
    user: '',
    role: '',
    menu: false,
     scrollOptions:{
                    height:'100%',  
                },
    items: [
      {
        icon: "mdi-view-dashboard",
        text: "Dashboard",
        link: "/library/dashboard",
      },
      {
        icon: "mdi-account-multiple",
        text: "Student List",
        link: "/library/student/list",
      }, 
      {
        icon: "mdi-clipboard-list",
        text: "Clearance Requests",
        link: "/library/clearance/requests",
      },
      {
        icon: "mdi-account-check",
        text: "Cleared Student Clearances",
        link: "/library/cleared/clearances",
      },
      {
        icon: "mdi-send-circle",
        text: "Submitted Clearances",
        link: "/library/submitted/clearances",
      }, 
      {
        icon: "mdi-clipboard-text",
        text: "Clearance List",
        link: "/library/clearance/list",
      },
      {
        icon: "mdi-history",
        text: "List of Deficiency",
        link: "/library/deficiency/list",
      }, 
      { icon: "mdi-cog", text: "Settings", link: "/library/settings" }, 
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
    loggedIn:{
      get(){
        return this.$store.state.currentUser.loggedIn;
      }
    },
    currentUser:{
      get(){
        return this.$store.state.currentUser.user;
      }
    }
  },
  methods: {
        logout(){
          this.menu = false;
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
