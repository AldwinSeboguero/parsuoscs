<template>
  <v-app id="inspire">
    <v-main>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="8">
            <v-card class="elevation-12">
              <v-window v-model="step">
                <v-window-item :value="1">
                  <v-row>
                    <v-progress-linear
                      :active="loading"
                      :indeterminate="loading"
                      absolute
                      top
                      color="blue accent-3"
                    ></v-progress-linear>
                    <v-col cols="12" md="8">
                      <v-card-text class="mt-12">
                        <div class="text-center pb-5 mt-4">
                          <v-btn class="mx-2 pb-5" icon>
                            <v-img
                              src="http://oscs.parsu.edu.ph/img/psulogo.png"
                              alt="Parsu"
                              width="100px"
                            ></v-img>
                          </v-btn>
                        </div>
                        <h1
                          class="text-center display-2 primary--text text--accent-4"
                        >
                          Sign in to OSCS
                        </h1>
                        <!-- <div class="text-center pb-2 mt-4">
                              <v-btn class="mx-2" fab  >
                                <v-icon>mdi mdi-facebook</v-icon>
                              </v-btn>
                              <v-btn class="mx-2" fab  >
                                <v-icon>mdi mdi-google-plus</v-icon>
                              </v-btn>
                              <v-btn class="mx-2" fab  >
                                <v-icon>mdi mdi-linkedin</v-icon>
                              </v-btn> 
                            </div> -->

                        <h4 class="text-center mt-4">
                          Ensure your email for sign in
                        </h4>
                        <v-form>
                          <v-text-field
                            label="Email"
                            name="Email"
                            v-model="user.email"
                            prepend-icon="mdi-email"
                            type="text"
                            color="teal accent-4"
                          />
                          <v-text-field
                            :type="showPassword ? 'text' : 'password'"
                            label="Password"
                            v-model="user.password"
                            color="teal accent-4"
                            prepend-icon="mdi-account-lock-outline"
                            :append-icon="
                              showPassword ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            @click:append="showPassword = !showPassword"
                          />
                        </v-form>
                        <h3 class="text-center mt-3">Forgot your password?</h3>
                      </v-card-text>
                      <div class="text-center mt-3 pb-3">
                        <v-btn rounded color="blue accent-3" @click="login" dark
                          >SIGN IN</v-btn
                        >
                      </div>
                    </v-col>
                    <v-col cols="12" md="4" class="primary accent-4">
                      <v-card-text class="white--text mt-12">
                        <h1 class="text-center display-1">Hello, Friends !</h1>
                        <h5 class="text-center">
                          Enter your activation code and start journey with us
                        </h5>
                      </v-card-text>
                      <div class="text-center">
                        <v-btn rounded outlined="" dark @click="step++"
                          >ACTIVATE ACCOUNT</v-btn
                        >
                      </div>
                    </v-col>
                  </v-row>
                </v-window-item>
                <v-window-item :value="2">
                  <v-row class="fill-height">
                    <v-col cols="12" md="4" class="primary accent-4">
                      <v-card-text class="white--text mt-12">
                        <h1 class="text-center display-1">Welcome Back!</h1>
                        <h5 class="text-center">
                          To Keep connected with us please please login with
                          your personal info
                        </h5>
                      </v-card-text>
                      <div class="text-center">
                        <v-btn rounded outlined dark @click="step--"
                          >BACK</v-btn
                        >
                      </div>
                    </v-col>
                    <v-col cols="12" md="8">
                      <v-card-text class="mt-12">
                        <h1
                          class="text-center desplay-2 primary--text text--accent4"
                        >
                          Activate Account
                        </h1>

                        <h4 class="text-center mt-4">
                          Ensure your activation code for activation
                        </h4>
                        <v-form>
                          <v-text-field
                            label="Activation Code"
                            name="ActivationCode"
                            prepend-icon="mdi-account-details"
                            type="text"
                            color="teal accent-4"
                          />
                        </v-form>
                      </v-card-text>
                      <div class="text-center mt-3 pb-3">
                        <v-btn
                          rounded
                          color="blue accent-3"
                          dark
                          @click="step++"
                          >ACTIVATE</v-btn
                        >
                      </div>
                    </v-col>
                  </v-row>
                </v-window-item>
                <v-window-item :value="3">
                  <v-row class="fill-height">
                    <v-col cols="12" md="4" class="primary accent-4">
                      <v-card-text class="white--text mt-12">
                        <h1 class="text-center display-1">Welcome Back!</h1>
                        <h5 class="text-center">
                          To Keep connected with us please please login with
                          your personal info
                        </h5>
                      </v-card-text>
                      <div class="text-center">
                        <v-btn rounded outlined dark @click="step = 1">
                          SIGN IN</v-btn
                        >
                      </div>
                    </v-col>
                    <v-col cols="12" md="8">
                      <v-card-text class="mt-12">
                        <h1
                          class="text-center desplay-2 primary--text text--accent4"
                        >
                          Register Account
                        </h1>

                        <h4 class="text-center mt-4">
                          Ensure your email for registration
                        </h4>
                        <v-form>
                          <v-text-field
                            label="Student ID Number"
                            name="ActivationCode"
                            prepend-icon="mdi-ticket-account"
                            type="text"
                            color="teal accent-4"
                            disabled
                          />
                          <v-text-field
                            label="Name"
                            name="name"
                            prepend-icon="mdi-account"
                            type="text"
                            color="teal accent-4"
                            disabled
                          />
                          <v-text-field
                            label="Program"
                            name="program"
                            prepend-icon="mdi-clipboard-list-outline"
                            type="text"
                            color="teal accent-4"
                            disabled
                          />
                          <v-text-field
                            label="Email"
                            name="Email"
                            prepend-icon="mdi-email"
                            type="text"
                            color="teal accent-4"
                          />
                          <v-text-field
                            :type="showPassword ? 'text' : 'password'"
                            label="Password"
                            color="teal accent-4"
                            prepend-icon="mdi-account-lock-outline"
                            :append-icon="
                              showPassword ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            @click:append="showPassword = !showPassword"
                          />
                          <v-text-field
                            :type="showPasswordc ? 'text' : 'password'"
                            label="Confirm Password"
                            color="teal accent-4"
                            prepend-icon="mdi-account-lock-outline"
                            :append-icon="
                              showPasswordc ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            @click:append="showPasswordc = !showPasswordc"
                          />
                        </v-form>
                      </v-card-text>
                      <div class="text-center mt-3 pb-3">
                        <v-btn rounded color="blue accent-3" dark
                          >REGISTER</v-btn
                        >
                      </div>
                    </v-col>
                  </v-row>
                </v-window-item>
              </v-window>
            </v-card>
            <v-snackbar v-model="snackbar" top>
              {{ text }}

              <template v-slot:action="{ attrs }">
                <v-btn
                  color="pink"
                  text
                  v-bind="attrs"
                  @click="snackbar = false"
                >
                  Close
                </v-btn>
              </template>
            </v-snackbar>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  data: () => ({
    step: 1,
    showPassword: false,
    showPasswordc: false,
    email: "",
    password: "",
    loading: false,
    snackbar: false,
    text: "",
    user: {
      email: "",
      password: "",
    }
  }),
  // computed: { 
  //   loggedIn:{
  //     get(){
  //       return this.$store.state.currentUser.loggedIn;
  //     }
  //   },
  //   currentUser:{
  //     get(){
  //       return this.$store.state.currentUser.user;
  //     }
  //   }
  // },
  methods: {
    login(){
      this.$store.dispatch('currentUser/loginUser',this.user) 
       .catch(err => console.log("err"));
   
    }
  },
  created(){
    axios.defaults.headers['Authorization'] = "Bearer " + localStorage.getItem("token");
    // this.$store.dispatch('currentUser/getUser'); 
    if(localStorage.getItem("token")){
      this.$router
              .push("/admin")
              .then((res) => console.log("Already login!"))
              .catch((err) => console.log(err));
    }
  },
  props: {
    source: String,
  },
  name: "App",
};
</script>

// <v-main>
//       <v-container
//         class="fill-height"
//         fluid
//       >
//         <v-row
//           align="center"
//           justify="center"
//         >
//           <v-col
//             cols="12"
//             sm="8"
//             md="4"
//           >
//            <v-card >
//               <v-card-title class="pb-6 align-center">
//                 <h1>Login</h1>
//               </v-card-title>
//               <v-card-text>
//                 <v-divider></v-divider>
//                 <v-form>
//                   <v-text-field 
//                     label="Username" 
//                     prepend-icon="mdi-account-circle-outline"
//                   />
//                   <v-text-field 
//                     :type="showPassword ? 'text' : 'password'" 
//                     label="Password"
//                     prepend-icon="mdi-account-lock-outline"
//                     :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
//                     @click:append="showPassword = !showPassword"
//                   />
//                 </v-form>
//               </v-card-text>
//               <v-divider></v-divider>
//               <v-card-actions>
//                 <v-btn color="success">Register</v-btn>
//                 <v-btn color="info">Login</v-btn>
//               </v-card-actions>
//             </v-card>
//           </v-col>
//         </v-row>
//       </v-container>
//     </v-main>
//   </v-app>