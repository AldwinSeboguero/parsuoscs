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
            <img src="https://scontent.fmnl4-1.fna.fbcdn.net/v/t1.0-9/118419981_3221515724561567_764454760029520824_o.jpg?_nc_cat=103&_nc_sid=09cbfe&_nc_ohc=y3q5ZMUytYcAX_wqcCO&_nc_ht=scontent.fmnl4-1.fna&oh=e850304c57e5455faac02fd5d82218f0&oe=5F85762B">
          </v-list-item-avatar>

          <v-list-item-content>
            <v-list-item-title> Aldwin Seboguero</v-list-item-title>
            <v-list-item-subtitle>Administrator</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </template>

      <v-divider></v-divider>
      
     <v-list dense>
        <template v-for="item in items">
          <v-row
            v-if="item.heading"
            :key="item.heading"
            align="center"
          >
            <v-col cols="6">
              <v-subheader v-if="item.heading">
                {{ item.heading }}
              </v-subheader>
            </v-col>
            <v-col
              cols="6"
              class="text-center"
            >
              <a
                href="#!"
                class="body-2 black--text"
              >EDIT</a>
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
              :to="child.link" link
            >
              <v-list-item-action v-if="child.icon">
                <v-icon>{{ child.icon }}</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>
                  {{ child.text }}
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-group>
          <v-list-item
            v-else
            :key="item.text"
             :to="item.link" link
          >
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
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      :clipped-left="$vuetify.breakpoint.lgAndUp"
      app
      color="primary accent-4"
      dark
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
      <v-toolbar-title
        style="width: 300px"
        class="ml-0 pl-4"
      >
        <span>ParSU OSCS</span>
      </v-toolbar-title>
   
      <v-spacer></v-spacer>
      <v-btn icon>
        <v-icon>mdi-message</v-icon>
      </v-btn>
      <v-btn icon>
        <v-icon>mdi-bell</v-icon>
      </v-btn>
      <v-btn
        icon
        large
      >
        <v-avatar
          size="32px"
          item
        >
          <v-img
            src="http://parsu-oscs.herokuapp.com/img/profile/picture.jpg"
            alt="parsu_logo"
          ></v-img></v-avatar>
      </v-btn>
    </v-app-bar>
    <v-main>
      <v-container fluid> 
       <router-view></router-view>
      
      </v-container>
      <v-footer
        dark
        padless
      >
        <v-card
          flat
          tile
          width="100%"
          class=" white lighten-1 black--text text-center"
        >
          <v-card-text>
            <v-btn
              class="mx-4 black--text"
              icon
            >
              <v-icon size="24px">
                mdi-web
              </v-icon>
            </v-btn>
            <v-btn
              class="mx-4 black--text"
              icon
            >
              <v-icon size="24px">
                mdi-facebook
              </v-icon>
            </v-btn>
            <v-btn
              class="mx-4 black--text"
              icon
            >
              <v-icon size="24px">
                mdi-twitter
              </v-icon>
            </v-btn>
            <v-btn
              class="mx-4 black--text"
              icon
            >
              <v-icon size="24px">
                mdi-youtube
              </v-icon>
            </v-btn>
          </v-card-text>

          <v-card-text class="black--text pt-0">
          </v-card-text>

          <v-divider class="grey"></v-divider>

          <v-card-text class="black--text">
            {{ new Date().getFullYear() }} &copy; <strong>ICTMO, Partido State University</strong>
          </v-card-text>
        </v-card>
      </v-footer>
    </v-main>
    
  </v-app>
  
</template>
<script>
  export default {
    name: 'main-app',
    props: {
      source: String,
    },
    data: () => ({
      dialog: false,
      drawer: null,
      items: [
        { icon: 'mdi-view-dashboard', text: 'Dashboard', link : '/admin/dashboard' },
        { icon: 'mdi-account-multiple', text: 'Student List', link : '/admin/student/list' },
            {
          icon: 'mdi-chevron-up',
          'icon-alt': 'mdi-chevron-down',
          text: 'Admin Setup',
          model: false,
          children: [
            { text: 'Colleges', link : '/admin/colleges' },
            { text: 'Programs', link : '/admin/programs' },
            { text: 'Sections', link : '/admin/sections' },
            { text: 'Semester', link : '/admin/semesters' },
            { text: 'Graduation Schedule', link : '/admin/graduations' },
            { text: 'Purposes', link : '/admin/purposes' },
          ],
        },
        { icon: 'mdi-clipboard-list', text: 'Clearance Requests', link : '/admin/clearance/requests' },
        { icon: 'mdi-account-check', text: 'Cleared Student Clearances', link : '/admin/cleared/clearances' },
        { icon: 'mdi-send-circle', text: 'Submitted Clearances', link : '/admin/submitted/clearances' },
        { icon: 'mdi-clipboard-check', text: 'Active Clearance', link : '/admin/active/clearance' },
        { icon: 'mdi-clipboard-text', text: 'Clearance List', link : '/admin/clearance/list' },
        { icon: 'mdi-history', text: 'List of Deficiency', link : '/admin/deficiency/list'  },
        { icon: 'mdi-cog', text: 'Settings', link : '/admin/settings'  },
        { icon: 'mdi-logout', text: 'Logout' ,link : '/login'},
     
      ], 
    }),
   
    
  }
</script>