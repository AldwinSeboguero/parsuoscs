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
        :sort-by="actions"
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
              Clearance Request List
              <span class="font-italic subtitle-2"
                >(2nd Semester A/Y 2020-2021 )</span
              >
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
         </template> 
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
        axios
          .get(`/api/v1/clearancerequests/${d}`)
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