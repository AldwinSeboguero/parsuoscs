<template>
  <v-sheet>
   <v-card>
    <v-container>
     <v-data-table
        item-key="id"
        class="elevation-0"
        :loading="loading"
        loading-text="Loading... Please wait"
        :headers="headers"
        :page="page + 1"
        :pageCount="numberOfPages"
        :items="clearancerequests.data"
        :options.sync="options"
        :server-items-length="totalclearancerequests"
        :items-per-page="10"  
        :sort-by.sync="sortBy"
      :sort-desc.sync="sortDesc"
        show-select 
        :footer-props="{
          itemsPerPageOptions: [5, 10, 15],
          itemsPerPageText: 'Clearance Request Per Page',
          'show-current-page': true,
          'show-first-last-page': true,
        }"
      >
      <template v-slot:top>
        <v-text-field 
            append-icon="mdi-magnify"
            label="Search"
            @input="searchIt"
          ></v-text-field>
        <v-toolbar flat color="white">
          <div class="overline text-h6">
             
               <h1
                        class="title desplay-2 black--text text--accent3"
                       
                      >
                      <!-- <v-icon class="ma-1 ">mdi-account-plus-outline</v-icon> -->
                         Clearance Request List
                        <v-dialog
                         v-model="copyDialog"
                         
                          width="390"
                      
                        >
                      <template v-slot:activator="{ on, attrs }">
                        <v-btn
                          dark
                          v-bind="attrs"
                          small
                          v-on="on"
                          icon class="float-right success white--text ml-2">
                                        <v-icon small>mdi-file-swap</v-icon>

                                      </v-btn>
                      </template>
                     <v-card >
                      <v-card-title class="overline pa-4">
                        Transfer Requests
                           
                 <v-autocomplete
                  v-model="editedItem.new_semester_id"
                  :items="semesters"
                  :loading="isLoading"
                  :search-input.sync="search"
                  chips
                  clearable
                  item-text="semester"
                  item-value="id"
                  item-key="id" 
                  label="Search New Semester..."
 
                  
                >
                  <template v-slot:no-data>
                    <v-list-item>
                      <v-list-item-title>
                        Search Semester
                      </v-list-item-title>
                    </v-list-item>
                  </template>
                  <template v-slot:selection="{ attr, on, item, selected }">
                    <v-chip
                      v-bind="attr"
                      :input-value="selected"
                      color="purple"
                      class="white--text"
                      v-on="on"
                    >
                       
                      <span v-text="item.semester"></span>
                    </v-chip>
                  </template>
                  <template v-slot:item="{ item }">
                    
                    <v-list-item-content>
                      <v-list-item-title v-text="item.semester"></v-list-item-title> 
                    </v-list-item-content> 
                  </template>
                </v-autocomplete>
                        <v-btn :disabled="editedItem.new_semester_id == null" block class="success" rounded @click="copyPrevStaff">Copy</v-btn>
                      </v-card-title>
                     </v-card>
           
                    </v-dialog>
                      </h1> 
            </div>
          <v-spacer></v-spacer> 

          
        <v-dialog
      v-model="dialog"
      persistent
      max-width="300"
      v-if="editedIndex > -1"
    >
     
      <v-card>
        
        <v-card-title class="headline">
          Approve this Clearance Request?
        </v-card-title>
        <v-card-text>This will certifiy that {{studentName}} is cleared from any property and money responsibility as of this date.</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="green darken-1"
            text
            @click="dialog = false"
          >
            Cancel
          </v-btn>
          <v-btn
            color="green darken-1"
            text
            @click="approve()"
          >
            Approve
          </v-btn>
        </v-card-actions>
        
      </v-card>
    </v-dialog>
    <v-dialog v-model="deferDialog" persistent max-width="600px">
     
      <v-card>
        <v-card-title>
          <span class="headline">Add Deficiency</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="12" md="12">
                <v-text-field v-model="deficiency.title" label="Item of Deficiency*" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="12">
                <v-textarea v-model="deficiency.note"  label="Additional Information" hint="Notes or Instructions for student"></v-textarea>
              </v-col>
          
              <!-- <v-col cols="12" sm="12">
                <v-autocomplete
                  :items="['Skiing', 'Ice hockey', 'Soccer', 'Basketball', 'Hockey', 'Reading', 'Writing', 'Coding', 'Basejump']"
                  label="Interests"
                  multiple
                ></v-autocomplete>
              </v-col> -->
            </v-row>
          </v-container>
          <small>*indicates required field</small>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="deferDialog = false">Close</v-btn>
          <v-btn color="blue darken-1" text @click="deferItem()">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>  

        </v-toolbar>
      </template>
      <template v-slot:item.id="{ item }">
      <td>{{clearancerequests.data.indexOf(item)+1}}</td> 
    </template>
       
      <template v-slot:item.actions="{ item }">
          <template>
        <v-btn class="ma-2" color="success" depressed x-small  @click="editItem(item)"
         v-if="!item.deficiencies.deficiencies_count" ><v-icon
          dark
          x-small
        >
          mdi-check-circle-outline
        </v-icon></v-btn
        > 
         </template> 
            <template>
        <v-btn class="ma-2" color="error" depressed x-small @click="defer(item)"
          ><v-icon
          dark
          x-small
        >
          mdi-close-circle-outline
        </v-icon></v-btn
        > 
        <v-btn class="ma-2" color="error" depressed x-small @click="deleteItem(item)"
          ><v-icon
          dark
          x-small
        >
          mdi-delete
        </v-icon></v-btn
        > 
         </template> 
      </template> 
	       <template v-slot:item.request_at="{ item }" >
        <v-chip text-color="white" color="success" small >
           
            {{ item.request_at }}
        </v-chip>
         </template>
    </v-data-table>
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
    </v-container>
    </v-card>
  </v-sheet>
</template>
<script>
export default {
  data: () => ({
    valid: true,
    dialog: false,
    deferDialog: false,
    loading: false,
    snackbar: false,
    selected: [],
    text: "",
    success: "",
    error: "", 
    snackbarColor:"",
      headers: [
      {
        text: "No",
        align: "left",
        value: "id",
      }, 
      
      { text: "Student Number", value: "student_number" },
      { text: "Name", value: "name" },
      { text: "Program", value: "program" },
       { text: "Purpose", value: "purpose" },
       { text: "Signatory", value: "staff" },
	   
       { text: "Date Requested", value: "request_at" },
      { text: "Action", value: "actions" },
    ], 
    page: 0,
    totalclearancerequests: 0,
    numberOfPages: 0,
    options: {},
     clearancerequests: [], 
    editedIndex: -1,
    itemIndex: 0,
    deficiency:{
    title:'',
    note:'',
    },
    studentName:'',
    clearanceRequest:{
       id: "",
      token: "",
      name: "",
      student_number: "",
      program: "",
      purpose: "", 
      staff: "",
    },
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
        .get(`/api/v1/clearancerequests?page=` + pageNumber, {
          params: { 'per_page': itemsPerPage },
        })
        .then((response) => {
          //Then injecting the result to datatable parameters.
          this.loading = false;
         this.clearancerequests = response.data.clearancerequests; 
          this.totalclearancerequests = response.data.clearancerequests.total;
          this.numberOfPages = response.data.clearancerequests.last_page;
        });
    },

    searchIt(d) {
      if (d.length > 2) {
        const { page, itemsPerPage } = this.options;
           let pageNumber = page;
        axios
          .get(`/api/v1/clearancerequests/${d}?page=` + pageNumber, {
          params: { 'per_page': itemsPerPage },
        })
          .then((res) => {
            this.loading = false;  
            this.clearancerequests = res.data.clearancerequests; 
            this.totalclearancerequests = res.data.clearancerequests.total;
            this.numberOfPages = res.data.clearancerequests.last_page;
          })
          .catch((err) => {
            console.error(err);
          });
      }
      if (d.length <= 0) {
        axios
          .get(`/api/v1/clearancerequests?page=${d.page}`, {
            params: { 'per_page': d.itemsPerPage },
          })
          .then((res) => {
            this.loading = false;  
            this.clearancerequests = res.data.clearancerequests;
            this.totalclearancerequests = res.data.clearancerequests.total;
            this.numberOfPages = res.data.clearancerequests.last_page;
          })
          .catch((err) => {
            console.error(err);
          });
      }
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
      this.clearanceRequest = Object.assign({}, item); 
      this.studentName = this.clearanceRequest.name;
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
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            this.clearancerequests.data.splice(index, 1);
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Deleting Record";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;

          });
      }
    },
    approve() {
      // const index = this.clearancerequests.data.indexOf(item);
      // let decide = confirm("Are you sure you want to approve this request?");
      // if (decide) {
        axios
          .post("/api/v1/approveclearancerequest", this.clearanceRequest)
          .then((res) => {
            this.text = "Request Approved Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
           this.clearancerequests = res.data.clearancerequests; 
          this.totalclearancerequests = res.data.clearancerequests.total;
          this.numberOfPages = res.data.clearancerequests.last_page;
          this.dialog = false;
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Approving Request";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;
          });
      // }
      close();
    },
    defer(item){
      this.editedIndex = this.clearancerequests.data.indexOf(item);
      this.itemIndex = this.clearancerequests.data.indexOf(item);
      this.clearanceRequest = Object.assign({}, item); 
      this.deferDialog = true;
    },
    deferItem(){
      const index = this.editedIndex;
        axios
          .put("/api/v1/clearancerequests/" + this.clearanceRequest.id, this.deficiency)
          .then((res) => {
            this.text = "Record Updated Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true; 
            this.clearancerequests = res.data.clearancerequests; 
          this.totalclearancerequests = res.data.clearancerequests.total;
          this.numberOfPages = res.data.clearancerequests.last_page;
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Updating Record";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;
          });
          this.deferDialog =false;
    },

    close() {
      this.dialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    }, 
    save() {
      console.log(this.editedItem);
      if (this.editedIndex > -1) {
        const index = this.editedIndex;
        axios
          .put("/api/v1/clearancerequests/" + this.editedItem.id, this.editedItem)
          .then((res) => {
            this.text = "Record Updated Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            Object.assign(this.clearancerequests.data[index], res.data.clearancerequest); 
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Updating Record";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;
          });
      } else {
        axios
          .post("/api/v1/clearancerequests", this.editedItem)
          .then((res) => {
            this.text = "Record Added Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            // this.students.data.push(res.data.student); 
            this.clearancerequests = res.data.clearancerequests
          })
          .catch((err) => {
            console.dir(err);
            this.text = "Error Inserting Record";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;
          });
          
      } 
        this.close();
     
    },
  },
};
</script>