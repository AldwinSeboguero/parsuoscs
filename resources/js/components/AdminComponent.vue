<template>
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      :clipped="$vuetify.breakpoint.lgAndUp"
      app
    >
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
              :to="item.link" link
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
      color="blue ligthen"
      dark
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-avatar
          size="32px"
          item
        >
            <v-img
                src="http://parsu-oscs.herokuapp.com/img/profile/picture.jpg"
                alt="Vuetify"
            ></v-img>
        </v-avatar>
      <v-toolbar-title
        style="width: 300px"
        class="ml-0 pl-4"
      >
        <span>ParSU OSCS</span>
      </v-toolbar-title>
   
      <v-spacer></v-spacer>
      <v-btn icon>
        <v-icon>mdi-apps</v-icon>
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
            src="https://cdn.vuetifyjs.com/images/logos/logo.svg"
            alt="Vuetify"
          ></v-img></v-avatar>
      </v-btn>
    </v-app-bar>
    <v-main>
       
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
        { icon: 'mdi-account-multiple', text: 'Student List', link : '/admin/student-list' },
            {
          icon: 'mdi-chevron-up',
          'icon-alt': 'mdi-chevron-down',
          text: 'Admin Setup',
          model: false,
          children: [
            { text: 'Campus' },
            { text: 'Colleges' },
            { text: 'Programs' },
            { text: 'Sections' },
            { text: 'Semester' },
            { text: 'Graduation Schedule' },
            { text: 'Purposes' },
          ],
        },
        { icon: 'mdi-clipboard-text', text: 'Clearance View', link : '/admin/clearance-view' },
        { icon: 'mdi-history', text: 'List of Deficiency' },
        { icon: 'mdi-cog', text: 'Settings' },
        { icon: 'mdi-logout', text: 'Login' ,link : '/login'},
     
      ],
    }),
  }
</script>