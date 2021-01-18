<template>
  <v-app id="inspire">
    <v-main>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="8">
            <v-card class="elevation-2">
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
                             <v-img src="images/psu_logo.png" alt="parsu_logo" width="100px"></v-img
        >
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
                            :rules="[rules.required, rules.validEmail]"
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
                            :rules="[rules.required]"
                          />

                          <h3 class="text-center mt-3">
                            Forgot your password?
                          </h3>
                          <div class="text-center mt-3 pb-3">
                            <v-btn
                              rounded
                              color="blue accent-3"
                              type="submit"
                              :disabled="!valid"
                              @click.prevent="login"
                              dark
                              >SIGN IN</v-btn
                            >
                          </div>
                        </v-form>
                      </v-card-text>
                      <v-card-actions>
                        <v-card-text class="text-center font-italic"
                          >For Inquiries: oscs.support@parsu.edu.ph</v-card-text
                        >
                      </v-card-actions>
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
                        <v-form v-model="valid" method="post">
                          <v-text-field
                            v-model="activationCode"
                            label="Activation Code"
                            name="ActivationCode"
                            prepend-icon="mdi-account-details"
                            type="text"
                            color="teal accent-4"
                            :success-messages="success"
                            :error-messages="error"
                            :blur="checkUUID"
                            :rules="[rules.required]"
                          />
                        </v-form>
                      </v-card-text>
                      <!-- <div class="text-center mt-3 pb-3">
                        <v-btn
                          rounded
                          color="blue accent-3"
                          dark
                          type="submit" 
                          :disabled="!valid" @click.prevent="activate" 
                          >ACTIVATE</v-btn
                        >
                      </div> -->
                      <v-card-actions>
                        <div class="text-center mt-3 pb-3">
                          <!-- <v-btn
                        rounded
                          color="blue accent-3"
                          dark 
                      type="submit"
                      :disabled="!valid"
                      @click.prevent="activate"
                    >
                      Save
                    </v-btn> -->
                        </div>
                      </v-card-actions>
                    </v-col>
                  </v-row>
                </v-window-item>
                <v-window-item :value="3">
                  <v-row class="fill-height">
                    <v-col cols="12" md="4" class="primary accent-4">
                      <v-card-text class="white--text mt-12">
                        <h1 class="text-center display-1">Welcome!</h1>
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
                          class="text-center display-2 primary--text text--accent4"
                        >
                          Register Account
                        </h1>

                        <h4 class="text-center mt-4">
                          Ensure your email for registration
                        </h4>
                        <v-form
                          v-model="valid"
                          method="post"
                          v-on:submit.stop.prevent="activate"
                        >
                          <v-text-field
                            label="Student ID Number"
                            name="ActivationCode"
                            v-model="student.student_number"
                            prepend-icon="mdi-ticket-account"
                            type="text"
                            color="teal accent-4"
                            disabled
                          />
                          <v-text-field
                            label="Name"
                            name="name"
                            v-model="student.name"
                            prepend-icon="mdi-account"
                            type="text"
                            color="teal accent-4"
                            disabled
                          />
                          <v-text-field
                            label="Program"
                            name="program"
                            v-model="program"
                            prepend-icon="mdi-clipboard-list-outline"
                            type="text"
                            color="teal accent-4"
                            disabled
                          />
                          <v-text-field
                            v-model="editedItem.email"
                            type="email"
                            :success-messages="success"
                            :error-messages="error"
                            :blur="checkEmail"
                            label="Email"
                            prepend-icon="mdi-email"
                            color="teal accent-4"
                            required
                            :rules="[rules.required, rules.validEmail]"
                          />
                          <v-text-field
                            v-model="editedItem.password"
                            :type="showPassword ? 'text' : 'password'"
                            label="Password"
                            color="teal accent-4"
                            :rules="[
                              rules.required,
                              rules.min(5, editedItem.password),
                            ]"
                            prepend-icon="mdi-account-lock-outline"
                            :append-icon="
                              showPassword ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            required
                            @click:append="showPassword = !showPassword"
                          />
                          <v-text-field
                            v-model="editedItem.rpassword"
                            :type="showPasswordc ? 'text' : 'password'"
                            label="Confirm Password"
                            color="teal accent-4"
                            prepend-icon="mdi-account-lock-outline"
                            :append-icon="
                              showPasswordc ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            required
                            :rules="[rules.required, passwordmatch]"
                            @click:append="showPasswordc = !showPasswordc"
                          />
                          <div class="text-center mt-3 pb-3">
                            <v-btn
                              rounded
                              color="blue accent-3"
                              dark
                              @click="activate"
                              >REGISTER</v-btn
                            >
                          </div>
                        </v-form>
                      </v-card-text>
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
              <v-dialog
      v-model="dialog"
      persistent
      max-width="350" 
    >
     
      <v-card>
        
        <v-card-title class="headline">
          Successfully Registered!
        </v-card-title>
        <v-card-text>Please proceed to the login page.</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
         
          <v-btn
            color="green darken-1"
            text
            @click="proceedtologin"
          >
            Ok
          </v-btn>
        </v-card-actions>
        
      </v-card>
    </v-dialog>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  data: () => ({
    step: 1,
    valid: true,
    dialog: false,
    showPassword: false,
    showPasswordc: false,
    email: "",
    password: "",
    student: {
      student_number: "",
    },
    rules: {
      required: (v) => !!v || "This Field is Required",
      // min: (v) => v.length >= 5|| "Minimum 5 Characters Required",
      min(min, v) {
        return (v || "").length >= min || `Minimum 5 Characters Required`;
      },
      validEmail: (v) => /.+@.+\..+/.test(v) || "Email must be valid",
    },
    loading: false,
    snackbar: false,
    success: "",
    error: "",
    activationCode: "",
    program: "",
    text: "",
    user: {
      email: "",
      password: "",
    },
    editedItem: {
      id: "",
      name: "",
      email: "",
      role: "",
      password: "",
      rpassword: "",
      created_at: "",
    },
    defaultItem: {
      id: "",
      name: "",
      email: "",
      password: "",
      rpassword: "",
      role: "",
      created_at: "",
    },
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
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New User" : "Edit User";
    },
    formIcon() {
      return this.editedIndex === -1 ? "mdi-account-plus" : "mdi-account-edit";
    },
    passwordmatch() {
      return this.editedItem.password != this.editedItem.rpassword
        ? "Password Does Not Match"
        : true;
    },
    checkEmail() {
      if (/.+@.+\..+/.test(this.editedItem.email)) {
        axios
          .post("/api/v1/email/verify", { email: this.editedItem.email })
          .then((res) => {
            this.success = res.data.message;
            this.error = "";
          })
          .catch((err) => {
            this.success = "";
            this.error = "Email Already Exist";
          });
      }
    },
    checkUUID() {
      if (this.activationCode != "") {
        axios
          .post("/api/v1/activation/verify", { code: this.activationCode })
          .then((res) => {
            if (this.activationCode != "") {
              this.success = res.data.message;
              this.error = "";

              this.step++;
              this.success = "";
              this.student = res.data.student;
              this.program = res.data.program;
            } else if (this.activationCode == "") {
              this.success = "";
              this.error = "This Field is Required";
            }
          })
          .catch((err) => {
            if (this.activationCode != "") {
              console.log(err);
              this.success = "";
              this.error = "Invalid Code";
            } else if (this.activationCode == "") {
              console.log(err);
              this.success = "";
              this.error = "This Field is Required";
            }
          });
      }
    },
  },
  methods: {
    activate() {
      axios
        .post("/api/v1/user/register", {
          email: this.editedItem.email,
          password: this.editedItem.password,
          rpassword: this.editedItem.rpassword,
          student: this.student.id,
        })
        .then((response) => {
          this.dialog = true;
        })
        .catch((err) => {
          this.snackbar = true;
          this.text = err.response.data.message;
          console.log(err.response.data.message);
        });
    },
    proceedtologin(){
      
        this.dialog=false;
        this.$router.go(0);
          this.student='';
        this.activationCode='';
        this.editedItem ='';
    },

    login() {
      // this.$store.dispatch('currentUser/loginUser',this.user)
      // .then(res =>{
      //   this.snackbar = true;
      //    this.text = $store.state.error;
      //    console.log($store.error);
      // })
      //  .catch(err => {
      //   // this.snackbar = true;
      //   //  this.text = $store.state.error;
      //  });

      axios
        .post("/api/v1/user/login", {
          email: this.user.email,
          password: this.user.password,
        })
        .then((response) => {
          localStorage.setItem("token", response.data.access_token);

          window.location.replace("/#/");
        })
        .catch((err) => {
          if (err.response.status == 419) {
            this.$router.push("/");
          } else {
            this.snackbar = true;
            this.text = err.response.data.message;
            console.log(err.response.data.message);
          }
        });
    },
    //  dismiss() {
    //   this.$store.state.error = "";
    // }
  },
  created() {
    axios.defaults.headers["Authorization"] =
      "Bearer " + localStorage.getItem("token");
    // this.$store.dispatch('currentUser/getUser');
    if (localStorage.getItem("token")) {
      this.$router
        .push("/")
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