
<template>
  <v-app id="inspire"  style="background: transparent" class="elavation-0">
      
    <v-card color="transparent" class="fill-height" >
    
      <v-progress-linear
        :active="loading"
        :indeterminate="loading"
        absolute
        top
        color="blue accent-3"
      ></v-progress-linear>
      <v-container id="logo" class="fill-height" style="background: transparent" fluid>
        
        <v-row align="center"   justify="center">
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
                  <v-alert
                        dense
                        text
                        type="success"
                        class="overline"
                        v-if="regsSuc"
                      >
                        {{message}}
                      </v-alert>
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
                              :rules="[rules.required]"
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
                      <v-dialog v-model="dialog" persistent width="400" max-width="800px">
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
                            v-model="validRegs"
                            method="post"
                            
                          >
                            <v-card>
                              <v-card-title
                                class="headline font-weight-500 justify-space-between"
                  
                              >
                                <span>
                                 <h1
                                    class="text-center overline primary--text text--accent4"
                                  >
                                    Activate Account
                                  </h1>
                                  
                                </span>

                                <v-btn
                                  icon
                                  @click="(dialog = false), (step = 1),(activationCode='')"
                                  ><v-icon dark> mdi-close </v-icon></v-btn
                                >
                              </v-card-title>
                              <v-divider />
                              <v-window v-model="step" touchless>
                                <v-form v-model="validUserInfo">
                                <v-window-item :value="2">
                                      <v-row class="fill-height">
                                       
                                        <v-col cols="12" md="12">
                                          <v-card-text class="">
                                            

                                            <h4 class="text-center">
                                              Ensure your activation code for activation
                                            </h4>
                                            <v-form v-model="validRegs" @click="checkUUID">
                                              <v-text-field
                                                v-model="activationCode"
                                                label="Activation Code"
                                                name="ActivationCode"
                                                prepend-icon="mdi-account-details"
                                                type="text"
                                                color="teal accent-4"
                                                autofocus
                                                class="overline"
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
                                </v-form>

                                <v-form v-model="validRegs">
                                   <v-window-item :value="3">
                                    <v-row class="fill-height">
                                     
                                      <v-col cols="12" md="12">
                                        <v-card-text>
                                          <h1
                                            class="text-center overline primary--text text--accent4"
                                          >
                                            Register Account
                                          </h1>

                                          <h4 class="text-center sub-title">
                                            Ensure your email for registration
                                          </h4>
                                          <v-form
                                            v-model="validRegs"
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
                                              @keyup="lowercase"
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
                                                color="blue white--text accent-3"
                                                type="submit"
                                                :disabled="!validRegs" 
                                                >REGISTER</v-btn
                                              >
                                            </div>
                                          </v-form>
                                        </v-card-text>
                                      </v-col>
                                    </v-row>
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
    message:"",
    activationCode: "",
    student: {
      student_number: "",
    },
    regsSuc: false,
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
    validRegs: true,
    
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
    program:"",
    lname: "",
    mname: "",
    fname: "",
    email: "",
    password: "",
    rpassword: "",

    officess: {},
    rules: {
        required: (v) => !!v || "This Field is Required",
      // min: (v) => v.length >= 5|| "Minimum 5 Characters Required",
      min(min, v) {
        return (v || "").length >= min || `Minimum 5 Characters Required`;
      },
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
      rpassword: "",
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
      rpassword: "",
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
      return this.editedItem.password != this.editedItem.rpassword
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
              // console.log(err);
              this.success = "";
              this.error = "Activation Code is Invalid or Already Used";
            } else if (this.activationCode == "") {
              // console.log(err);
              this.success = "";
              this.error = "This Field is Required";
            }
          });
      }
    },
  },
  methods: {
    lowercase() {
      this.editedItem.email = this.editedItem.email.toLowerCase();
    },
      loginG() {
      this.loading = true;
      axios.get('/api/v1/auth/google')
      .then( res =>{
          // console.log(res.data);
           
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
      // console.log(this.editedItem.batch);
    },
    initialize() {
      // axios
      //   .get(`/api/v1/users`)
      //   .then((res) => {
      //     this.h_years = res.data.h_years;
      //     this.c_years = res.data.c_years;
      //     this.batchtypes = res.data.batches;
      //   })
      //   .catch((err) => {
      //     // console.error("err");
      //   });
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
          this.user.email = this.editedItem.email;
          // console.log(this.user.email)
          this.dialog = false;
          this.regsSuc = true;
          this.step=1;
          this.editedItem = Object.assign({}, this.defaultItem)
          this.activationCode = "";
          this.message = response.data.message;
        })
        .catch((err) => {
          this.snackbar = true;
          this.text = err.response.data.message;
          // console.log(err.response.data.message);
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
            // console.log(err.response.data.message);
          }
          this.loginloading = false;
        });
    },
    //  dismiss() {
    //   this.$store.state.error = "";
    // }

    //register user
    register() {
      // console.log(this.editedItem);
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
                // console.log(err.response.data.message);
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
  name: "AApp",
};
</script>
<style>
#logo {
  font-family: "Avenir", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
</style>