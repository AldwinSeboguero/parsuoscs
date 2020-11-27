<template>
  <v-app>
    <v-data-table
      item-key="name"
      class="elevation-1"
      :loading="loading"
      loading-text="Loading... Please wait"
      :headers="headers"
      @pagination="paginate"
      :server-items-length="clearancerequests.total"
      :items="clearancerequests.data"
      sort-by="name" 
      color="error"
      :items-per-page="10"
      show-select
      :footer-props="{
        itemsPerPageOptions: [5, 10, 15, 100],
        itemsPerPageText: 'Request Per Page',
        'show-current-page': true,
        'show-first-last-page': true,
      }"
    >
      <template v-slot:top>
        <v-toolbar flat color="white">
          <v-toolbar-title>Clearance Request List</v-toolbar-title>

          <v-spacer></v-spacer> 
          
        </v-toolbar>
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
  </v-app>
</template>
<script>
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
        value: "",
      },
      { text: "Request Id", value: "token" },
      { text: "Name", value: "name" },
      { text: "Student Number", value: "student_number" },
      { text: "Program", value: "program" },
       { text: "Purpose", value: "purpose" },
       { text: "Signatory", value: "staff" },
      { text: "Action", value: "actions" },
    ],
    clearancerequests: [], 
    editedIndex: -1,
    editedItem: {
      id: "",
      token: "",
      name: "",
      student_number: "",
      program: "",
      purpose: "", 
      staff: "",
    },
    defaultItem: {
      id: "",
      token: "",
      name: "",
      student_number: "",
      program: "",
      purpose: "", 
      staff:"",
    },
  }),

  computed: { 
      
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
  },

  created() {
    this.initialize();
  },

  methods: {
    
    paginate(e) { 
      axios
        .get(`/api/v1/clearancerequests?page=${e.page}`, {
          params: { 'per_page': e.itemsPerPage},
        })
        .then(res => {
          this.clearancerequests = res.data.clearancerequests; 
        })
        .catch(err => {
          if (err.response.status == 401) {
            localStorage.removeItem("token");
            this.$router.push("/login");
          }
        });
    },
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
      this.editedIndex = this.clearancerequests.data.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      const index = this.clearancerequests.data.indexOf(item);
      let decide = confirm("Are you sure you want to delete this item?");
      if (decide) {
        axios
          .delete("/api/v1/clearancerequests/" + item.id)
          .then((res) => {
            this.text = "Record Deleted Successfully!";
            this.snackbar = true;
            this.clearancerequests.data.splice(index, 1);
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
          .put("/api/v1/clearancerequests/" + this.editedItem.id, this.editedItem)
          .then((res) => {
            this.text = "Record Updated Successfully!";
            this.snackbar = true;
            Object.assign(this.clearancerequests.data[index], res.data.clearancerequests);
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Updating Record";
            this.snackbar = true;
          });
      } 
      this.close();
    },
  },
};
</script>