<template>
  <v-sheet>
    <v-data-table
      item-key="name"
      class="elevation-1"
      :loading="loading"
      loading-text="Loading... Please wait"
      :headers="headers" 
      :items="users"
      sort-by="name" 
      color="error"
      :items-per-page="10"
      show-select
      hide-default-footer
      :footer-props="{
        itemsPerPageOptions: [5, 10, 15, 100],
        itemsPerPageText: 'Users Per Page',
        'show-current-page': true,
        'show-first-last-page': true,
      }"
    >
      <template v-slot:top>
        <v-text-field label="Search...." @input="searchIt"></v-text-field>
        <v-toolbar flat color="white">
          <v-toolbar-title>User Account List</v-toolbar-title>

          <v-spacer></v-spacer>

          <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{ on, attrs }">
              <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
                New User
              </v-btn>
            </template>
            <v-card>
              <v-card-title class="primary white--text">
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
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="12" v-if="editedIndex == -1">
                        <v-text-field
                          type="password"
                          color="primary"
                          v-model="editedItem.password"
                          label="Type Password"
                          :rules="[rules.required, rules.min]"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="12" v-if="editedIndex == -1">
                        <v-text-field
                          type="password"
                          color="primary"
                          v-model="editedItem.rpassword"
                          label="Retype Password"
                          :rules="[rules.required, rules.min, passwordmatch]"
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
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="12" v-if="editedIndex > -1">
                        <v-text-field
                          v-model="editedItem.email"
                          type="email" 
                          label="Email"
                          :rules="[rules.required, rules.validEmail]"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="12" style="margin: 0">
                        <v-select
                          v-model="editedItem.role"
                          :items="roles"
                          label="Select Role"
                          value="editedItem.role"
                          color="primary"
                          :rules="[rules.required]"
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
        </v-toolbar>
      </template>
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
            <v-select
              v-model="item.role"
              :items="roles"
              label="Select Role"
              value="editedItem.role"
              color="primary"
              :rules="[rules.required]"
            ></v-select>
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
    <paginate store="user" collection="users"/>
  </v-sheet>
</template>
<script>
import paginate from "./pagination/userPaginate"
export default {
  components:{
    paginate,
  },
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
      { text: "Action", value: "actions" },
    ], 
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
    users: {
      get(){
        return this.$store.state.user.users.data;
      }
    },
    
  },
 
 async created() {
    this.$store.dispatch('user/getList',0);
    // this.initialize();
  },

  methods: {
    searchIt(d){ 
        if(d.length >3){
          this.$store.dispatch('user/searchIt', d);
        }
         if(d.length <= 0){
          this.$store.dispatch('user/getList',0);
        }
    },
 },
};
</script>