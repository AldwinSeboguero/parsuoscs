<template>
<v-container>
  <v-card>
  
     <v-card-title class="white--text elevation-2"  style="background: linear-gradient(to left, #1A237E, #1A237E, #0D47A1);">
       <span class="text-h6 white--text"><v-icon class="white--text" left>mdi-cog</v-icon>Settings</span>

           
    </v-card-title>

        <v-tabs
          v-model="tabs"
        >
          <!-- <v-tabs-slider></v-tabs-slider> -->
          <v-tab
            href="#mobile-tabs-5-1"
            class="primary--text"
          >
            <v-icon left>mdi-account-edit</v-icon> Purpose
          </v-tab>

          <v-tab
            href="#mobile-tabs-5-2"
            class="primary--text"
          >
            <v-icon left>mdi-email</v-icon> Change Email
          </v-tab>
           <v-tab
            href="#mobile-tabs-5-3"
            class="primary--text"
          >
            <v-icon left>mdi-lock</v-icon> Change Password
          </v-tab>

     
        </v-tabs>

    <v-tabs-items v-model="tabs">
      <v-tab-item class="ml-6 mr-6"
      :value="'mobile-tabs-5-1'"
      >
        <v-card flat>
          <v-form
                  v-model="valid"
                  method="post"
                  v-on:submit.stop.prevent="save"
                > 
                    <v-container>
                      <v-row>
                       
                        <v-col cols="12" sm="12" style="margin: 0">
                          <v-select
                            v-model="setup.purpose_id"
                            :items="purposes"
                            label="Select Purpose"
                             item-value="id"
                             :rules="[rules.required]"
                          item-text="purpose"
                            color="primary"
                          ></v-select>
                        </v-col>
                        <v-col cols="12" sm="12" style="margin: 0" v-if="setup.purpose_id == 1">
                          <v-select
                          v-model="setup.semester_id"
                            :items="semesters"
                            label="Select Semester"
                             item-value="id"
                          item-text="semester"
                            color="primary"
                            :rules="[rules.required]"
                          ></v-select>
                        </v-col>
                        <v-col cols="12" sm="12" style="margin: 0" v-else-if="setup.purpose_id == 2">
                          <v-select
                          v-model="setup.graduation_id"
                            :items="graduations"
                            label="Select Graduation Period"
                             item-value="id"
                          item-text="graduation"
                            color="primary"
                            :rules="[rules.required]"
                          ></v-select>
                        </v-col>
                         <v-col cols="12" sm="12" v-else-if="setup.purpose_id == 3">
                          <v-text-field
                            v-model="setup.credential" 
                            label="Specify" 
                            :rules="[rules.required]"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container> 

                  
          </v-form>
           <v-divider></v-divider>
       <v-card-actions>
                    <v-spacer></v-spacer>
                 
                    <v-btn
                      color="blue darken-1"
                      text
                      type="submit" 
                      @click.prevent="save"
                    >
                      Save
                    </v-btn>
     </v-card-actions>
        </v-card>
      </v-tab-item>
      <v-tab-item class="ml-6 mr-6"
       :value="'mobile-tabs-5-2'"
      >
        <v-card flat >
           <v-form
                  v-model="valid"
                  method="post"
                  v-on:submit.stop.prevent="updateEmail"
                > 
                    <v-container>
                      <v-row>
                       
                        <v-col cols="12" sm="12" >
                        <v-text-field
                          v-model="editedItem.email"
                          type="email"
                          :success-messages="success"
                          :error-messages="error"
                          :blur="checkEmail"
                          label="Email"
                          :disabled="!email_disabled"
                          :rules="[rules.required, rules.validEmail]"
                        ></v-text-field>
                      </v-col>
                      
                      </v-row>
                    </v-container> 

                  
          </v-form>
           <v-divider></v-divider>
       <v-card-actions>
        
         <v-checkbox
                      v-model="email_disabled"
                      label="Change Email"
                    ></v-checkbox>
                    <v-spacer></v-spacer>
                    
                    <v-btn
                      color="blue darken-1"
                      text
                      :disabled="!valid"
                      type="submit"
                      :loading="loading"
                      @click.prevent="updateEmail"
                    >
                      Save
                    </v-btn>
     </v-card-actions>
        </v-card>
      </v-tab-item>

       <v-tab-item class="ml-6 mr-6"
       :value="'mobile-tabs-5-3'"
      >
        <v-card flat>
           <v-form
                  v-model="valid"
                  method="post"
                  v-on:submit.stop.prevent="updatePassword"
                > 
                    <v-container>
                      <v-row>
                       
                      <v-col cols="12" sm="12" >
                        <v-text-field
                          type="password"
                          color="primary"
                          v-model="editedItem.password"
                          label="Type Password"
                          :disabled = "!password_disabled"
                          :rules="[rules.required, rules.min]"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="12" >
                        <v-text-field
                          type="password"
                          color="primary"
                          v-model="editedItem.rpassword"
                          :disabled = "!password_disabled"
                          label="Retype Password"
                          :rules="[rules.required, rules.min, passwordmatch]"
                        ></v-text-field>
                      </v-col>
                      </v-row>
                    </v-container> 

                  
          </v-form>
           <v-divider></v-divider>
       <v-card-actions>
                     <v-checkbox
                      v-model="password_disabled"
                      label="Change Password"
                    ></v-checkbox>
                    <v-spacer></v-spacer>
                    
                    <v-btn
                      color="blue darken-1"
                      text
                      type="submit"
                      :disabled="!valid"
                      @click.prevent="updatePassword"
                    >
                      Save
                    </v-btn>
     </v-card-actions>
        </v-card>
      </v-tab-item>
    </v-tabs-items>
    <v-snackbar
      v-model="snackbar" 
      :color="snackbarColor" 
      right
      timeout="5000" 
      outlined
     top
     width="50" 
    >
       <v-icon 
          left
        >
          mdi-error
        </v-icon>{{ text }}

      <template v-slot:action="{ attrs }">
        
          <v-btn
        :color="snackbarColor"
          text
          v-bind="attrs"
          @click="snackbar = false"
      >
        <v-icon
          dark
          left
        >
          mdi-close
        </v-icon>close
      </v-btn>
      </template>
    </v-snackbar>
  </v-card>
  
</v-container>
</template>

<script>
export default {
  data: () => ({
    tabs: null,
    valid: true,
    dialog: false,
    loading: true,
    snackbar: false,
    password_disabled :false,
    email_disabled :false,
    passwordCheckbox: false,
    snackbarColor:"",
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
      { text: "Email", value: "email" },
      { text: "Role", value: "role" },
      { text: "Date Created", value: "created_at" },
      { text: "Action", value: "actions" },
    ],
    page: 0,
    totalUsers: 0,
    numberOfPages: 0,
    options: {},
    users: {},
    roles: [],
    purposes:[],
    semesters:[],
    graduations:[],
    setup:{
    graduation_id:"",
    credential:"",
    purpose_id:"",
    semester_id:"",
    
    g_id: "",
    
    },
    rules: {
      required: (v) => !!v || "This Field is Required",
      min: (v) => v.length >= 5 || "Minimum 5 Characters Required",
      validEmail: (v) => /.+@.+\..+/.test(v) || "Email must be valid",
    },
    editedIndex: -1,
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
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
     options: {
      handler() {
        this.readDataFromAPI();
      },
    },
    deep: true,
  },

  created() {
    this.initialize();
  },

  methods: {
   
    initialize() {
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
      axios.get("/api/v1/purposesetup").then(
        res => {
          
          this.setup.credential = res.data.credential;
          this.purposes = res.data.purposes;
          this.graduations = res.data.graduations;
          this.semesters = res.data.semesters;
         
          if (res.data.purpose_id) {
           this.setup.purpose_id = res.data.purpose_id.id;
          }
          if (res.data.semester_id) {
          this.setup.semester_id = res.data.semester_id.id;
          }
          if (res.data.graduation_id) { 
          this.setup.graduation_id = res.data.graduation_id.id;
          }
          
        }
      ).catch(
        err => {console.log(err)}
      );
      
    },

  


    save() { 
        axios
          .post("/api/v1/purposesetup", this.setup)
          .then((res) => {
            this.text = "Purpose Saved Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            this.$router.push({ path: "/student/active/clearance" });
          })
          .catch((err) => {
            console.dir(err);
            this.text = "Error Saving Purpose!"; 
            this.snackbarColor ="error darken-1";
            this.snackbar = true;
          });
          
      
  },
  updateEmail(){
       this.loading = true;
            axios.post("/api/v1/emailChangeCreate", {email: this.editedItem.email}).then(result => {
              this.email =null;
              this.email_disabled = false;
                this.response = result.data; 
                this.snackbar = true;
                this.text = result.data.message;
                this.snackbarColor ="success darken-1";
                this.loading = false;
                this.email_disabled = false;
                this.valid = false;
            }, error => {
              this.loading = false;
                console.error(error);
                this.success = "";
                this.snackbar = true;
                this.text = error.response.data.message;
            });
  },
  updatePassword(){
      axios
          .post("/api/v1/changePassword", { password : this.editedItem.password})
          .then((res) => {
            this.editedItem.password = "";
              this.editedItem.rpassword = "";
              this.password_disabled = false;
            this.text = "Password Updated Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            
          })
          .catch((err) => {
            console.dir(err);
            this.text = "Error Updating Password!"; 
            this.snackbarColor ="error darken-1";
            this.snackbar = true;
          });

  },
  },
   props: {
    source: String,
  },
  name: "App",
};
</script>