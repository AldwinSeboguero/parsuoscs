<template>
  <v-app-bar app outlined class="white" height="51" elevation="0" 
      style="border-bottom: 1px solid #d2d2d2 !important"
  >
    <v-app-bar-nav-icon
      @click.stop="openDrawer"
      class="hidden-lg-and-up"
    ></v-app-bar-nav-icon>

    <v-img
      src="/images/OSCS_LOGO.jpg"
      max-height="100"
      max-width="100"
      alt="OSCS Logo"
    ></v-img>
    <v-divider class="ml-4" inset vertical></v-divider>
    <v-toolbar-title
      style="width: 300px"
      class="ml-0 pl-4 font-weight-medium blue-grey--text text--darken-4"
    >
      <h6 class="font-weight-medium grey--text text--darken-2 text-overline text-uppercase text-large">{{ currentRouteName }}</h6>
    </v-toolbar-title>

    <v-spacer></v-spacer>
    <v-btn icon small class="mr-2">
      <v-menu
        v-model="menu"
        :close-on-content-click="false"
        :nudge-width="200"
        offset-x
      >
        <template v-slot:activator="{ on, attrs }">
          <v-btn icon large v-bind="attrs" v-on="on">
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
                  <v-icon dark> mdi-account-circle </v-icon>
                </v-avatar>
              </v-list-item-avatar>

              <v-list-item-content>
                <v-list-item-title>{{ user.name }}</v-list-item-title>
                <v-list-item-subtitle>{{
                  role.description
                }}</v-list-item-subtitle>
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
            <v-list-item to="/student/settings" link @click="menu = false">
              <v-list-item-action>
                <v-icon>mdi-cog</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title> Settings </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item @click="logout">
              <v-list-item-action>
                <v-icon>mdi-logout-variant</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title> Logout </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>

          <v-card-actions>
            <v-spacer></v-spacer>
          </v-card-actions>
        </v-card>
      </v-menu>
    </v-btn>
  </v-app-bar>
</template>
<script>
export default {
    props:{
    openDrawer: null,

    },
  data: () => ({
    dialog: false,
    menu: false,
    drawer: null,
    user: "",
    role: "",

  }),
  computed: {
    currentRouteName() {
      return this.$route.name;
    },
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
};
</script>