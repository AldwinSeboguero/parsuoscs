<template>
  <v-card>
    <v-toolbar flat>
     

      <v-toolbar-title><v-icon left>mdi-cog</v-icon>Settings</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn icon>
        <v-icon>mdi-magnify</v-icon>
      </v-btn>

      <v-btn icon>
        <v-icon>mdi-dots-vertical</v-icon>
      </v-btn>

      <template v-slot:extension>
        <v-tabs
          v-model="tabs"
          fixed-tabs
          VERTICAL
         
        >
          <v-tabs-slider></v-tabs-slider>
         

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
      </template>
    </v-toolbar>

    <v-tabs-items v-model="tabs">
     
      <v-tab-item
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
                       
                        <v-col cols="12" sm="12">
                        <v-text-field
                          v-model="editedItem.email"
                          type="email"
                          :success-messages="success"
                          :error-messages="error"
                          :blur="checkEmail"
                          label="Email"
                          :rules="[rules.required, rules.validEmail]"
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
                      :disabled="!valid"
                      @click.prevent="updateEmail"
                    >
                      Save
                    </v-btn>
     </v-card-actions>
        </v-card>
      </v-tab-item>

       <v-tab-item
       :value="'mobile-tabs-5-3'"
      >
        <v-card flat>
           <v-form
                  v-model="valid"
                  method="post"
                  v-on:submit.stop.prevent="changePassword"
                > 
                    <v-container>
                      <v-row>
                       
                      <v-col cols="12" sm="12" >
                        <v-text-field
                          type="password"
                          color="primary"
                          v-model="editedItem.password"
                          label="Type Password"
                          :rules="[rules.required, rules.min]"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="12" >
                        <v-text-field
                          type="password"
                          color="primary"
                          v-model="editedItem.rpassword"
                          label="Retype Password"
                          :rules="[rules.required, rules.min, passwordmatch]"
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
                      :disabled="!valid"
                      @click.prevent="changePassword"
                    >
                      Save
                    </v-btn>
     </v-card-actions>
        </v-card>
      </v-tab-item>
    </v-tabs-items>
  </v-card>
</template>

<script>
export default {
  data: () => ({
    tabs: null,
    valid: true,
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
    updateEmail(){
      console.log(this.editedItem.email)
    },
    changePassword(){

    },
    readDataFromAPI() {
      this.loading = true;
      const { page, itemsPerPage } = this.options;
      let pageNumber = page;
      axios
        .get(`/api/v1/users?page=` + pageNumber, {
          params: { 'per_page': itemsPerPage },
        })
        .then((response) => {
          //Then injecting the result to datatable parameters.
          this.loading = false;
          //  this.page = response.data.students;
          this.users = response.data.users;
          this.roles = response.data.roles;
          this.totalUsers = response.data.users.total;
          this.numberOfPages = response.data.users.last_page;
        });
    },

    searchIt(d) {
      if (d.length > 3) {
        const { page, itemsPerPage } = this.options;
        axios
          .get(`/api/v1/users/${d}`)
          .then((res) => {
            this.loading = false;
            this.roles = res.data.roles;
            // this.page = response.data.students;
            this.users = res.data.users;
            this.totalUsers = res.data.users.total;
            this.numberOfPages = res.data.users.last_page;
          })
          .catch((err) => {
            console.error(err);
          });
      }
      if (d.length <= 0) {
        axios
          .get(`/api/v1/users?page=${d.page}`, {
            params: { 'per_page': d.itemsPerPage },
          })
          .then((res) => {
            this.loading = false;
            this.roles = res.data.roles;
            // this.page = response.data.students;
            this.users = res.data.users;
            this.totalUsers = res.data.users.total;
            this.numberOfPages = res.data.users.last_page;
          })
          .catch((err) => {
            console.error(err);
          });
      }
    },
    updateRole(item){
      const index = this.users.data.indexOf(item);
      axios.post('/api/v1/user/role',{'role': item.role, 'user' : item.id})
      .then(res =>{
        this.text = res.data.user.name+"'s Role Updated to "+ res.data.user.role
        this.snackbar = true
      })
      .catch(err => {
        console.error(err); 
      })
    },

    // paginate(e) { 
    //   axios
    //     .get(`/api/v1/users?page=${e.page}`, {
    //       params: { 'per_page': e.itemsPerPage},
    //     })
    //     .then(res => {
    //       this.users = res.data.users;
    //       this.roles = res.data.roles;
    //     })
    //     .catch(err => {
    //       if (err.response.status == 401) {
    //         localStorage.removeItem("token");
    //         this.$router.push("/login");
    //       }
    //     });
    // },
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
    },

    editItem(item) {
      this.editedIndex = this.users.data.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      const index = this.users.data.indexOf(item);
      let decide = confirm("Are you sure you want to delete this item?");
      if (decide) {
        axios
          .delete("/api/v1/users/" + item.id)
          .then((res) => {
            this.text = "Record Deleted Successfully!";
            this.snackbar = true;
            this.users.data.splice(index, 1);
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Deleting Record";
            this.snackbar = true;
          });
      }
    },

    close() {
      this.dialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },

    save() {
      if (this.editedIndex > -1) {
        const index = this.editedIndex;
        axios
          .put("/api/v1/users/" + this.editedItem.id, this.editedItem)
          .then((res) => {
            this.text = "Record Updated Successfully!";
            this.snackbar = true;
            Object.assign(this.users.data[index], res.data.user);
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Updating Record";
            this.snackbar = true;
          });
      } else {
        axios
          .post("/api/v1/users", this.editedItem)
          .then((res) => {
            this.text = "Record Added Successfully!";
            this.snackbar = true;
            this.users.data.push(res.data.user);
            console.log(res);
          })
          .catch((err) => {
            console.dir(err);
            this.text = "Error Inserting Record";
            this.snackbar = true;
          });
      }
      this.close();
    },
  },
};
</script>