
<template>
  <v-app id="inspire"  style="background: transparent" >
      
    <v-card color="transparent">
    
      <v-progress-linear
        :active="loading"
        :indeterminate="loading"
        absolute
        top
        color="blue accent-3"
      ></v-progress-linear>
      <v-container class="fill-height" style="background: transparent" fluid>
        <v-row align="center "   justify="center">
          <v-col cols="12" sm="8" md="8">
            <v-card class="elevation-0 transparent white">
              <v-row>
                <v-col cols="12" lg="6" class="pr-8" >
                  <v-card-text class="mt-12 hidden-md-and-down">
                    <div class="  mt-12"> 
                    
                       <div>
                        <v-btn
                          class="ma-5"
                          text
                          icon
                          color="blue lighten-2"
                        >
                         <v-avatar size="70" class="elevation-3">
                          <img
                             src="images/psu_logo.png"
                              alt="ICTMO"
                          >
                        </v-avatar>
                        </v-btn>

                        <v-btn
                          class="ma-5"
                          text
                          icon
                          color="yellow lighten-2"
                        >
                          <v-avatar size="70" class="elevation-3">
                          <img
                             src="images/ictmo_logo.png"
                              alt="ICTMO"
                          >
                        </v-avatar>
                        </v-btn>
                      </div>
 
                    
                    </div>
                   
                     <h3
                      class=" display-2 primary--text text--accent-4 hidden-sm-and-down font-weight-bold"
                    > ParSU OSCS
                    <v-divider
                    
                    ></v-divider>
                    </h3>
                    
                    <h4  class=" caption primary--text text--accent-4 hidden-sm-and-down">
                      Online Student Clearance System
                    </h4>
                   
                  </v-card-text>

                   <v-card-text class=" hidden-md-and-up" style="padding-bottom:0;">
                    <div class=" pb-1 "> 
                    
                       <div>
                        <v-btn
                          class="ma-3"
                          text
                          icon
                          color="blue lighten-2"
                        >
                         <v-avatar size="60" class="elevation-3">
                          <img
                             src="images/psu_logo.png"
                              alt="ICTMO"
                          >
                        </v-avatar>
                        </v-btn>

                        <v-btn
                          class="ma-3"
                          text
                          icon
                          color="yellow lighten-2"
                        >
                          <v-avatar size="60" class="elevation-3">
                          <img
                             src="images/ictmo_logo.png"
                              alt="ICTMO"
                          >
                        </v-avatar>
                        </v-btn>
                      </div>
 
                    
                    </div>
                  
                    
                    <h3
                      class=" display-1 primary--text text--accent-4 hidden-md-and-up font-weight-bold"
                    > ParSU OSCS
                    <v-divider
                    ></v-divider>
                    </h3>
                    
                    <h4  class=" caption primary--text text--accent-4 hidden-md-and-up">
                      Online Student Clearance System
                    </h4>
                  </v-card-text>
                </v-col>

                <v-col cols="12" lg="6">
                  <v-card
                    class="rounded-xl"
                    style="padding-left: 20px;padding-right: 20px;padding-bottom: 20px"
                    elevation="1"
                    default
                  >
                    <v-card-text class="">
                      <h1
                        class="text-center desplay-2 black--text text--accent3"
                        style="padding-top:20px"
                      >
                        Login
                      </h1>
                    </v-card-text>
                    <v-list-item three-line>
                      <v-list-item-content>
                        <div class="text-center pb-3 mt-4">
                          <v-form
                            v-model="valid"
                            method="post"
                            v-on:submit.stop.prevent="save"
                          >
                            <v-text-field
                              label="Email"
                              name="Email"
                              v-model="user.email"
                              type="text"
                              color="teal accent-4"
                              :rules="[rules.required, rules.validEmail]"
                              dense
                              filled
                              rounded
                              autofocus 
                            />
                            <v-text-field
                              dense
                              filled
                              rounded
                              :type="showPassword ? 'text' : 'password'"
                              label="Password"
                              v-model="user.password"
                              color="teal accent-4"
                              :append-icon="
                                showPassword ? 'mdi-eye' : 'mdi-eye-off'
                              "
                              :rules="[rules.required, rules.min]"
                              @click:append="showPassword = !showPassword"
                            />

                            <div class="text-center mt-3 pb-3">
                              
                             
                              <v-btn
                                block
                                x-large
                                rounded
                                color="blue accent-3"
                                type="submit"
                                :loading="loginloading"
                                @click.prevent="login"
                                class="white--text"
                                :disabled="!valid"
                                >SIGN IN</v-btn
                              >
                               <v-btn
                                block
                                x-large
                                rounded
                                color="red accent-3"
                                class="white--text mt-2"
                                @click="loginG()"
                              > Google Sign In</v-btn>

                              <div class="text-center mt-0 pb-0">
                                <v-btn
                                  text
                                  center
                                  class="text-center mt-3 primary--text"
                                  @click="resetPassword"
                                >
                                  Forgot your password?
                                </v-btn>
                              </div>
                            </div>
                          </v-form>
                          <v-divider />
                        </div>
                      </v-list-item-content>
                    </v-list-item>

                    <v-card-actions>
                      <v-dialog v-model="dialog" persistent max-width="400px">
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn
                            v-bind="attrs"
                            v-on="on"
                            block
                            color="success"
                            x-large
                            >Activate Account</v-btn
                          >
                        </template>
                        <div>
                          <v-form
                            v-model="valid"
                            method="post"
                            v-on:submit.stop.prevent="save"
                          >
                            <v-card>
                              <v-card-title
                                class="headline font-weight-500 justify-space-between"
                              >
                                <span>
                                  <v-list-item-title class="headline">
                                    {{ currentTitle }}
                                  </v-list-item-title>
                                  <v-list-item-subtitle>{{
                                    currentTitleSub
                                  }}</v-list-item-subtitle>
                                </span>

                                <v-btn
                                  icon
                                  @click="(dialog = false), (step = 1)"
                                  ><v-icon dark> mdi-close </v-icon></v-btn
                                >
                              </v-card-title>
                              <v-divider />
                              <v-window v-model="step" touchless>
                                <v-form v-model="validUserInfo">
                                  <v-window-item :value="1">
                                    <v-card-text>
                                      <v-text-field
                                        label="First name"
                                        type="FName"
                                        dense
                                        filled
                                        rounded
                                        v-model="editedItem.fname"
                                        :rules="[rules.required]"
                                        required
                                        @input="$v.fname.$touch()"
                                        @blur="$v.fname.$touch()"
                                      ></v-text-field>
                                      <v-text-field
                                        label="Last name"
                                        v-model="editedItem.lname"
                                        type="LName" 
                                        :rules="[rules.required]"
                                        dense
                                        filled
                                        rounded
                                      ></v-text-field>
                                      <v-text-field
                                        label="Middle name (optional)"
                                        v-model="editedItem.mname"
                                        type="MName"
                                        dense
                                        filled
                                        rounded
                                      ></v-text-field>
                                      <v-dialog
                                        ref="dialog"
                                        v-model="modal"
                                        :return-value.sync="date"
                                        persistent
                                        width="290px"
                                      >
                                        <template
                                          v-slot:activator="{ on, attrs }"
                                        >
                                          <v-text-field
                                            v-model="editedItem.bday"
                                            label="Birthday"
                                            readonly
                                            v-bind="attrs" 
                                           :rules="[rules.required]"
                                           append-icon="mdi-calendar" 
                                           
                                            v-on="on"
                                            filled
                                            rounded
                                          ></v-text-field>
                                        </template>
                                        <v-date-picker
                                          v-model="editedItem.bday"
                                          scrollable
                                        >
                                          <v-spacer></v-spacer>
                                          <v-btn
                                            text
                                            color="primary"
                                            @click="modal = false"
                                          >
                                            Cancel
                                          </v-btn>
                                          <v-btn
                                            text
                                            color="primary"
                                            @click="$refs.dialog.save(date)"
                                          >
                                            OK
                                          </v-btn>
                                        </v-date-picker>
                                      </v-dialog>
                                    </v-card-text>
                                    <v-card-actions
                                      align="center"
                                      justify="center"
                                    >
                                      <v-spacer />
                                      
                                      <v-btn
                                        v-if="step !== 5"
                                        color="primary"
                                        depressed
                                        rounded
                                        @click="step++"
                                        :disabled="!validUserInfo"
                                      >
                                        Next
                                      </v-btn>
                                      <v-spacer />
                                    </v-card-actions>
                                  </v-window-item>
                                </v-form>

                                <v-form v-model="validStudInfo">
                                  <v-window-item :value="2" touchless>
                                    <v-card-text>
                                      <v-text-field
                                        label="Student Number"
                                        type="SNumber"
                                        v-model="editedItem.student_number" 
                                        :rules="[rules.required]"
                                        dense
                                        filled
                                        rounded
                                      ></v-text-field>
                                      <v-select
                                        v-model="editedItem.batch"
                                        :items="batchtypes"
                                        menu-props="auto"
                                        label="Select Batch Type"
                                        hide-details
                                        class="pb-3"
                                        single-line
                                        item-value="id"
                                        item-text="name"
                                        color="primary"
                                        @change="batchTypeListener"
                                         :rules="[rules.required]"
                                        filled
                                        rounded
                                      ></v-select>

                                      <v-select
                                        v-if="batchIndex == 1"
                                        v-model="editedItem.h_year"
                                         :rules="[rules.required]"
                                        :items="h_years"
                                        item-value="id"
                                        item-text="year"
                                        menu-props="auto"
                                        label="Select High School Batch Year"
                                        hide-details
                                        class="pb-3"
                                        single-line
                                        filled
                                        rounded
                                      ></v-select>

                                      <v-select
                                        v-if="batchIndex == 2"
                                        v-model="editedItem.c_year"
                                         :rules="[rules.required]"
                                        :items="c_years"
                                        item-value="id"
                                        item-text="year"
                                        menu-props="auto"
                                        label="Select College Batch Year"
                                        hide-details
                                        class="pb-3"
                                        single-line
                                        filled
                                        rounded
                                      ></v-select>
                                      
                                    </v-card-text>
                                    <v-card-actions
                                      align="center"
                                      justify="center"
                                    >
                                      <v-spacer />
                                      <v-btn
                                        :disabled="step === 1"
                                        text
                                        rounded
                                        large
                                        @click="step--"
                                      >
                                        Back
                                      </v-btn>
                                      <v-btn
                                        v-if="step !== 5"
                                        color="primary"
                                        depressed
                                        rounded
                                        @click="step++"
                                        :disabled="!validStudInfo"
                                      >
                                        Next
                                      </v-btn>
                                      <v-spacer />
                                    </v-card-actions>
                                  </v-window-item>
                                </v-form>

                                <v-form v-model="validEmailAdd">
                                  <v-window-item :value="3" touchless>
                                    <v-card-text>
                                      <v-text-field
                                        label="Email"
                                        v-model="editedItem.email"
                                        :rules="[rules.required, rules.validEmail]"
                                        type="Email"
                                        dense
                                        filled
                                        rounded
                                      ></v-text-field>
                                      <span
                                        class="caption grey--text text--darken-1"
                                      >
                                        This is the email you will use to login
                                        to your OSCS account
                                      </span>
                                    </v-card-text>
                                    <v-card-actions
                                      align="center"
                                      justify="center"
                                    >
                                      <v-spacer />
                                      <v-btn
                                        :disabled="step === 1"
                                        text
                                        rounded
                                        large
                                        @click="step--"
                                      >
                                        Back
                                      </v-btn>
                                      <v-btn
                                        v-if="step !== 5"
                                        color="primary"
                                        depressed
                                        rounded
                                        @click="step++"
                                        :disabled="!validEmailAdd"
                                      >
                                        Next
                                      </v-btn>
                                      <v-spacer />
                                    </v-card-actions>
                                  </v-window-item>
                                </v-form>

                                <v-form v-model="validPassword">
                                  <v-window-item :value="4" touchless>
                                    <v-card-text>
                                      <v-text-field
                                        label="Password"
                                        v-model="editedItem.password"
                                        :rules="[rules.required, rules.min]"
                                        type="password"
                                        dense
                                        filled
                                        rounded
                                      ></v-text-field>
                                      <v-text-field
                                        label="Confirm Password"
                                        v-model="editedItem.confirmPassword"
                                        type="password"
                                        :rules="[
                                  rules.required,
                                  rules.min,
                                  passwordmatch,
                                ]"
                                        dense
                                        filled
                                        rounded
                                      ></v-text-field>
                                      <span
                                        class="caption grey--text text--darken-1"
                                      >
                                        Please enter a password for your account
                                      </span>
                                    </v-card-text>
                                    <v-card-actions
                                      align="center"
                                      justify="center"
                                    >
                                      <v-spacer />
                                      <v-btn
                                        :disabled="step === 1"
                                        text
                                        rounded
                                        large
                                        @click="step--"
                                      >
                                        Back
                                      </v-btn>
                                      <v-btn
                                        v-if="step !== 5"
                                        color="primary"
                                        depressed
                                        rounded
                                        @click="register"
                                        :disabled="!validPassword"
                                        :loading="loadingSubmitRegister"
                                      >
                                        Submit
                                      </v-btn>
                                      <v-spacer />
                                    </v-card-actions>
                                  </v-window-item>
                                </v-form>
                                <v-form v-model="valid">
                                  <v-window-item :value="5" touchless>
                                    <div class="pa-4 text-center">
                                      <v-img
                                        class="mb-4"
                                        contain
                                        height="128"
                                        src="images/psu_logo.png"
                                      ></v-img>
                                      <h3 class="title font-weight-light mb-2">
                                        Welcome to ParsU OSCS
                                      </h3>
                                      <span class="caption grey--text"
                                        >Thanks for signing up!</span
                                      >
                                    </div>
                                    <v-card-actions
                                      align="center"
                                      justify="center"
                                    >
                                      <v-spacer />
                                      <v-btn
                                        text
                                        rounded
                                        large
                                        @click="dialog = false"
                                      >
                                        Login
                                      </v-btn>
                                      <v-spacer />
                                    </v-card-actions>
                                  </v-window-item>
                                </v-form>
                              </v-window>

                              <v-divider></v-divider>
                            </v-card>
                          </v-form>
                        </div>
                      </v-dialog>
                    </v-card-actions>
                    <div class="text-center font-italic">
                      <h5>For Inquiries: oscs.support@parsu.edu.ph</h5>
                    </div>
                  </v-card>
                </v-col>
              </v-row>
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
         <!-- Load Facebook SDK for JavaScript -->
      <div id="fb-root"></div>
        <!-- Your Chat Plugin code -->
      <div class="fb-customerchat"
        attribution="setup_tool"
        page_id="110490564450712">
      </div>
    </v-card>
  </v-app>
  
</template>
 
    
      <script>
        window.fbAsyncInit = function() {
          FB.init({
            xfbml            : true,
            version          : 'v10.0'
          });
        };

        (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));</script>

    

<script> 
export default {
  data: () => ({
    step: 1,
    batchIndex: 0,
    dialog: false,
    scrollOptions: {
      height: "100%",
    },
    date: new Date().toISOString().substr(0, 10),
    menu: false,
    modal: false,
    menu2: false,
    showPassword: false,
    showPasswordc: false,
    email: "",
    password: "",
    loading: false,
    loginloading: false,
    loadingSubmitRegister: false,
    snackbar: false,
    text: "",
    user: {
      email: "",
      password: "",
    },
    valid: true,
    validUserInfo: true,
    validEmailAdd: true,
     validStudInfo: true,
      validPassword: true,
    dialog: false,
    loading: false,
    snackbar: false,
    selected: [],
    text: "",
    success: "",
    error: "",
    headers: [
      {
        text: "No",
        align: "left",
        value: "id",
      },
      { text: "Name", value: "name" },
      { text: "Total Working", value: "working" },
      { text: "Total Not Working", value: "notworking" },
      { text: "Total", value: "total" },
    ],
    page: 0,
    totalOffices: 0,
    numberOfPages: 0,
    options: {},
    batchtypes: {},
    h_years: {},
    c_years: {},
    c_year: "",
    h_year: "",
    student_number: "",
    lname: "",
    mname: "",
    fname: "",
    email: "",
    password: "",
    confirmPassword: "",

    officess: {},
    rules: {
      required: (v) => !!v || "This Field is Required",
      min: (v) => v.length >= 8 || "Minimum 8 Characters Required",
      validEmail: (v) => /.+@.+\..+/.test(v) || "Email must be valid",
    },
    editedIndex: -1,
    editedItem: {
      id: "",
      batch: "",
      c_year: "",
      h_year: "",
      student_number: "",
      lname: "",
      mname: "",
      fname: "",
      email: "",
      password: "",
      bday: "",
      confirmPassword: "",
      created_at: "",
    },
    defaultItem: {
      id: "",
      batch: "",
      c_year: "",
      h_year: "",
      student_number: "",
      lname: "",
      mname: "",
      fname: "",
      email: "",
      password: "",
      bday: "",
      confirmPassword: "",
      created_at: "",
    },
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Office" : "Edit Office";
    },
    formIcon() {
      return this.editedIndex === -1 ? "mdi-plus" : "mdi-pen";
    },
    currentTitle() {
      switch (this.step) {
        case 1:
          return "Sign Up";
        case 3:
          return "Email";
        case 2:
          return "Student Information";
        case 4:
          return "Create a password";
        default:
          return "Account created";
      }
    },
    currentTitleSub() {
      switch (this.step) {
        case 1:
          return "It’s quick and easy.";
        case 2:
          return "Provide your student information.";
        case 3:
          return "Ensure your email address.";
        case 4:
          return "Use a secure password.";
        default:
          return "Account created";
      }
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
     passwordmatch() {
      return this.editedItem.password != this.editedItem.confirmPassword
        ? "Password Does Not Match"
        : true;
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
      loginG() {
      this.loading = true;
      axios.get('/api/v1/auth/google')
      .then( res =>{
          console.log(res.data);
           
            window.location.replace(res.data.url);
            localStorage.setItem("token", res.data.access_token);
            // window.loaction.href =res.data.url;
            this.loading = false;
            
          
      })
      .catch(err => {

      });
    },
    batchTypeListener() {
      this.batchIndex = this.editedItem.batch;
      console.log(this.editedItem.batch);
    },
    initialize() {
      axios
        .get(`/api/v1/users`)
        .then((res) => {
          this.h_years = res.data.h_years;
          this.c_years = res.data.c_years;
          this.batchtypes = res.data.batches;
        })
        .catch((err) => {
          console.error(err);
        });
      axios.interceptors.request.use(
        (config) => {
          this.loading = true;
          return config;
        },
        (error) => {
          this.loading = false;
          return Promise.reject(error);
        }
      );

      axios.interceptors.response.use(
        (response) => {
          this.loading = false;
          return response;
        },
        (error) => {
          this.loading = false;
          return Promise.reject(error);
        }
      );
    },
   
    resetPassword() {
      this.$router.push({ name: "reset-password" });
    },
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
    proceedtologin() {
      this.dialog = false;
      this.$router.go(0);
      this.student = "";
      this.activationCode = "";
      this.editedItem = "";
    },

    login() {
      this.loginloading = true;
      axios
        .post("/api/v1/user/login", {
          email: this.user.email,
          password: this.user.password,
        })
        .then((response) => {
          localStorage.setItem("token", response.data.access_token);

          window.location.replace("/#/");
          this.loginloading = false;
        })
        .catch((err) => {
          if (err.response.status == 419) {
            this.$router.push("/");
          } else {
            this.snackbar = true;
            this.text = err.response.data.message;
            console.log(err.response.data.message);
          }
          this.loginloading = false;
        });
    },
    //  dismiss() {
    //   this.$store.state.error = "";
    // }

    //register user
    register() {
      console.log(this.editedItem);
      this.loadingSubmitRegister = true;
      axios
        .post("/register", this.editedItem)
        .then((response) => {
             this.loadingSubmitRegister = false;
          localStorage.setItem("token", response.data.access_token);
          window.location.replace("/#/");
        })
        .catch((err) => {
           this.loadingSubmitRegister = false;
        });
    },

    save() {
      axios
        .post("/api/v1/register", this.editedItem)
        .then((response) => {
          this.loginloading = true;
          axios
            .post("/api/v1/user/login", {
              email: this.editedItem.email,
              password: this.editedItem.password,
            })
            .then((response) => {
              localStorage.setItem("token", response.data.access_token);

              window.location.replace("/#/");
              this.loginloading = false;
            })
            .catch((err) => {
              if (err.response.status == 419) {
                this.$router.push("/");
              } else {
                this.snackbar = true;
                this.text = err.response.data.message;
                console.log(err.response.data.message);
              }
              this.loginloading = false;
            });
        })
        .catch((err) => {});
    },
  },
  created() {
    this.initialize();
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

<style>
#app {
  font-family: "Avenir", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
</style>