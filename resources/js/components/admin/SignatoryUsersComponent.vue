<template>
  <v-card>
  <v-card-subtitle class="white--text elevation-3 pt-4 pb-6"   style="background: linear-gradient(to left, #1A237E, #1A237E, #0D47A1);">
  <span class="text-h6  text-uppercase"> Users
     </span>
  <v-row>
        <v-col
        class="mb-0 pb-0 mt-0 pt-4 mr-0 pr-0"
        cols="9"
        md="10"
        lg="10"
        
        >
        <v-text-field 
            append-icon="mdi-magnify"
            label="Search"
            v-model="searchItem"
            @input="searchIt"
            solo-inverted
            flat
            dark
            dense
            hide-details
          ></v-text-field>
       </v-col>
       <v-col
        class="mb-0 pb-0 mt-0 pt-4 mr-0 text-right pl-1 ml-0"
        cols="3"
        md="2"
        lg="2">
        <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{ on, attrs }">
              <span></span>
             <v-spacer></v-spacer>
              <span>
              <v-btn color="" dark class="px-3" v-bind="attrs" v-on="on" text flat depressed>
                <v-icon small class="mr-2"> mdi-account-plus</v-icon>
               <span class="hidden-md-and-down">New User</span> 
              </v-btn>
              </span>
              
            </template>
            <v-card>
              <v-card-title class="elevation-2 white--text" style="background: linear-gradient(to left, #1A237E, #1A237E, #0D47A1);">
                <v-icon class="white--text" style="padding-right: 8px">{{
                  formIcon
                }}</v-icon>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>
              <v-form
                v-model="valid"
                method="post"
                v-on:submit.stop.prevent="save"
              >
                <v-card-text>
                  <v-container>
                    <v-row>
                      <v-col cols="12" sm="12">
                        <v-text-field
                          v-model="editedItem.name"
                          label="Name"
                          :rules="[rules.required, rules.min]"
                          filled
                          rounded
                          hide-details
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="12" v-if="editedIndex == -1">
                        <v-text-field
                          type="text"
                          color="primary"
                          v-model="editedItem.password"
                          hint="At least 8 characters"
                          label="Type Password" 
                          filled
                          rounded
                          hide-details
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="12" v-if="editedIndex > -1">
                        <v-text-field
                          type="text"
                          color="primary"
                          v-model="editedItem.password"
                          label="Type Password" 
                          :rules="[rules.required, rules.min]"
                          filled
                          rounded
                          hide-details

                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="12" v-if="editedIndex == -1">
                        <v-text-field
                          type="text"
                          color="primary"
                          v-model="editedItem.rpassword"
                          label="Retype Password"
                          hint="At least 8 characters"
                          :rules="[rules.required, rules.min, passwordmatch]"
                          filled
                          rounded
                          hide-details
                        ></v-text-field>
                      </v-col>

                      <v-col cols="12" sm="12" v-if="editedIndex == -1">
                        <v-text-field
                          v-model="editedItem.email"
                          type="email"
                          :success-messages="success"
                          :error-messages="error"
                          :blur="checkEmail"
                          label="Email"
                          :rules="[rules.required, rules.validEmail]"
                          filled
                          rounded
                          hide-details
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="12" v-if="editedIndex > -1">
                        <v-text-field
                          v-model="editedItem.email"
                          type="email" 
                          label="Email"
                          :rules="[rules.required, rules.validEmail]"
                          filled
                          rounded
                          hide-details
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="12" style="margin: 0">
                        <v-select
                          v-model="editedItem.role_id"
                          :items="roles"
                          label="Select Role"
                          item-text="description"
                          item-value="id"
                          item-key="id" 
                          color="primary"
                          :rules="[rules.required]"
                          rounded
                          filled
                          hide-details
                        ></v-select>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="close">
                    Cancel
                  </v-btn>
                  <v-btn
                    color="blue darken-1"
                    text
                    type="submit"
                    :disabled="!valid"
                    @click.prevent="save"
                  >
                    Save
                  </v-btn>
                </v-card-actions>
              </v-form>
            </v-card>
          </v-dialog>
       </v-col>
  </v-row>
  
         
          
    </v-card-subtitle>
  
    <v-container>
     <v-data-table
        item-key="id"
        class="elevation-0"
        :loading="loading"
        loading-text="Loading... Please wait"
        :headers="headers"
        :page="page + 1"
        :pageCount="numberOfPages"
        :items="users.data"
        :options.sync="options"
        :server-items-length="totalUsers"
        :items-per-page="10" 
        
        :footer-props="{
          itemsPerPageOptions: [5, 10, 15],
          itemsPerPageText: 'Users Per Page',
          'show-current-page': true,
          'show-first-last-page': true,
        }"
      >
  
      <template v-slot:item.role="{ item }">
        <v-edit-dialog
        large
        block
        persistent
        :return-value.sync ="item.role"
        @save="updateRole(item)"
        >
          {{ item.role }}
          <template v-slot:input>
            <h2>Change Role</h2>
          </template>
          <template v-slot:input>
            <!-- <v-select
              v-model="item.role"
              :items="roles"
              label="Select Role"
              value="editedItem.role"
              color="primary"
              :rules="[rules.required]"
            ></v-select> -->
          </template>
        </v-edit-dialog>
      </template>

      <template v-slot:item.actions="{ item }">
        <v-icon small class="mr-2" @click="editItem(item)"> mdi-pencil </v-icon>
        <v-icon small class="mr-2" @click="deleteItem(item)">
          mdi-delete-forever
        </v-icon>
      </template>
      <template v-slot:no-data>
        <v-btn color="primary" @click="initialize"> Reset </v-btn>
      </template>
    </v-data-table>
    <v-snackbar v-model="snackbar" bottom>
      {{ text }}

      <v-btn color="pink" text @click="snackbar = false"> Close </v-btn>
    </v-snackbar>
    </v-container>
  </v-card>
</template>
<script>
import generator from "generate-password";

export default {
  data: () => ({
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
      { text: "Updated Created", value: "updated_at" },
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
      this.editedItem.password =generator.generate({
              length: 10,
              numbers: true
            });
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