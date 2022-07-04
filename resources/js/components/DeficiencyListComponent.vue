<template>
  <v-container>
   <v-card>
   <v-card-subtitle class="white--text text-uppercase elevation-2 mb-0 pb-1"   style="background: linear-gradient(to left, #1A237E, #1A237E, #0D47A1);">
          <span class="text-h6"> Student Deficiencies </span>

    </v-card-subtitle>
     <v-card-title class="white--text elevation-2 mb-0 pb-0 mt-0 pt-0"  style="background: linear-gradient(to left, #1A237E, #1A237E, #0D47A1);">
         <v-text-field 
            append-icon="mdi-magnify"
            label="Search"
            class="mb-0 pb-0 mt-2 pt-0"
             v-model="searchItem"
            @input="searchIt"
            solo-inverted
            flat
            dark
            dense
          ></v-text-field>
         
           
    </v-card-title>
    <v-container>
     <v-data-table
        item-key="id"
        class="px-6 pb-6  mt-4"
        :loading="loading"
        loading-text="Loading... Please wait"
        :headers="headers"
        :page="page + 1"
        :pageCount="numberOfPages"
        :items="deficiencies.data"
        :options.sync="options"
        :server-items-length="totaldeficiencies"
        :items-per-page="10" 
        :footer-props="{
          itemsPerPageOptions: [5, 10, 15],
          itemsPerPageText: 'Deficiency Per Page',
          'show-current-page': true,
          'show-first-last-page': true,
        }"
      >
      <template v-slot:top>

<v-dialog v-model="editdialog" persistent max-width="500">
     
      <v-card>
      <v-card-title class="white--text text-uppercase elevation-2 "   style="background: linear-gradient(to left, #1A237E, #1A237E, #0D47A1);">
          <span class="text-h6">Edit Deficiency </span>

    </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="12" md="12">
                <v-text-field filled label="Item of Deficiency*" v-model="editedItem.deficiency" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="12">
                <v-textarea filled label="Additional Information" v-model="editedItem.note" hint="Notes or Instructions for student"></v-textarea>
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
          <v-btn color="blue darken-1" text @click="editdialog = false">Close</v-btn>
          <v-btn color="blue darken-1" text @click="save()">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>  


          <v-dialog
      v-model="dialog"
      persistent
      max-width="320" 
    >
     
      <v-card>
        
        <v-card-title class="headline">
          Approve this Deficiency?
        </v-card-title>
        <v-card-text>This will certifiy that {{studentName}} has completed the deficiency ({{deficiencyName}}).</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary darken-1"
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

       <v-dialog
      v-model="deletedialog"
      persistent
      max-width="320" 
    >
     
      <v-card>
        
        <v-card-title class="headline">
          Delete this Deficiency?
        </v-card-title>
        <v-card-text>This will delete the deficiency ({{deficiencyName}}) of {{studentName}}.</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary darken-1"
            text
            @click="deletedialog = false"
          >
            Cancel
          </v-btn>
          <v-btn
            color="error darken-1"
            text
            @click="deleteDeficiency()"
          >
            Delete
          </v-btn>
        </v-card-actions>
        
      </v-card>
    </v-dialog>



      
      </template>
     

    <template v-slot:item.completed="{ item }">
        <v-chip text-color="white" color="success" small  v-if="item.completed == 1">
           
           Completed
        </v-chip>
         <v-chip text-color="white" color="warning" small  v-if="item.completed != 1">
           
           Pending
        </v-chip>
         </template>
     <template v-slot:item.actions="{ item }" >
          <template v-if="item.completed != 1">
        <v-btn class="ma-1" color="success" depressed x-small  @click="approveItem(item)"
        ><v-icon
          dark
          x-small
        >
          mdi-check-circle
        </v-icon>APPROVE</v-btn
        > 
         </template> 
           <template v-if="item.completed != 1">
        <v-btn class="ma-1 px-6" color="primary" depressed x-small   @click="editItem(item)"
          ><v-icon
          dark
          x-small
        >
          mdi-circle-edit-outline
        </v-icon>EDIT</v-btn
        > 
         </template> 
            <!-- <template v-if="item.completed != 1"> 
        <v-btn class="ma-1" color="error" depressed x-small @click="deleteItem(item)"
          ><v-icon
          dark
          x-small
        >
          mdi-delete-circle
        </v-icon>DELETE</v-btn
        > 
         </template>  -->
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
  </v-container>
</template>
<script>
export default {
  data: () => ({
    valid: true,
    dialog: false,
    deletedialog: false,
    editdialog: false,
    loading: false,
    snackbar: false,
    selected: [],
    studentName:"",
    deficiencyName:"",
    text: "",
    success: "",
    error: "", 
    snackbarColor:"",
      headers: [
      {
        text: "#",
        align: "left",
        value: "id",
      },   
      { text: "Student Number", value: "student_number" },
      { text: "Name", value: "student_name" },
      { text: "Completed", value: "completed" }, 
       { text: "Deficiency", value: "deficiency" }, 
      { text: "Staff", value: "staff" },   
      { text: "Action", value: "actions" },
    ], 
    page: 0,
    totaldeficiencies: 0,
    numberOfPages: 0,
    options: {},
     deficiencies: [], 
    editedIndex: -1,
    editedItem: {
      id: "", 
      name: "",
      student_number: "",
      program: "", 
      semester:"", 
      deficiency:"",
      note:'',
    },
    defaultItem: {
       id: "", 
      name: "",
      student_number: "",
      program: "", 
      semester:"", 
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
        .get(`/api/v1/deficiencies?page=` + pageNumber, {
          params: { 'per_page': itemsPerPage },
        })
        .then((response) => {
          //Then injecting the result to datatable parameters.
          this.loading = false;
         this.deficiencies = response.data.deficiencies; 
          this.totaldeficiencies = response.data.deficiencies.total;
          this.numberOfPages = response.data.deficiencies.last_page;
        });
    },

    searchIt(d) {
      if (d.length > 2) {
        const { page, itemsPerPage } = this.options;
        axios
          .get(`/api/v1/deficiencies/${d}`)
          .then((res) => {
            this.loading = false;  
            this.deficiencies = res.data.deficiencies; 
            this.totaldeficiencies = res.data.deficiencies.total;
            this.numberOfPages = res.data.deficiencies.last_page;
          })
          .catch((err) => {
            console.error(err);
          });
      }
      if (d.length <= 0) {
        axios
          .get(`/api/v1/deficiencies?page=${d.page}`, {
            params: { 'per_page': d.itemsPerPage },
          })
          .then((res) => {
            this.loading = false;  
            this.deficiencies = res.data.deficiencies;
            this.totaldeficiencies = res.data.deficiencies.total;
            this.numberOfPages = res.data.deficiencies.last_page;
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
      this.editedIndex = this.deficiencies.data.indexOf(item);
      this.editedItem = Object.assign({}, item); 
     this.editdialog = true;
    },  
    approveItem(item) {
      this.editedIndex = this.deficiencies.data.indexOf(item);
      this.editedItem = Object.assign({}, item); 
      this.studentName = this.editedItem.student_name;
      this.deficiencyName = this.editedItem.deficiency;
     this.dialog = true;
    },    
    deleteItem(item) {
      this.editedIndex = this.deficiencies.data.indexOf(item);
      this.editedItem = Object.assign({}, item); 
      this.studentName = this.editedItem.student_name;
      this.deficiencyName = this.editedItem.deficiency;
      this.deletedialog=true;
      
    },
    deleteDeficiency(){
      const index = this.editedIndex;  
        axios
          .delete("/api/v1/deficiencies/" + this.edited.id)
          .then((res) => {
            this.text = "Record Deleted Successfully!"; 
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            this.deficiencies.data.splice(index, 1);
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Deleting Record";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;

          }); 
          this.deletedialog = false;
    },
    approve(){
       const index = this.editedIndex;
        axios
          .post("/api/v1/deficiencies/approve", this.editedItem)
          .then(res => {
            this.text = "Record Updated Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            this.loading = false;
            this.deficiencies = res.data.deficiencies; 
              this.totaldeficiencies = res.data.deficiencies.total;
              this.numberOfPages = res.data.deficiencies.last_page; 
          })
          .catch((err) => {
           
            this.text = "Error Updating Record";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;
       });
       this.dialog = false;
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
          .post("/api/v1/deficeincy/edit", this.editedItem)
          .then((res) => {
            this.text = "Record Updated Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            // Object.assign(this.deficiencies.data[index], res.data.deficiency); 
            this.loading = false;
            this.deficiencies = res.data.deficiencies; 
              this.totaldeficiencies = res.data.deficiencies.total;
              this.numberOfPages = res.data.deficiencies.last_page; 
            this.editdialog= false;
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Updating Record";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;
            this.editdialog= false;
          });
          this.editdialog= false;
      } else {
        axios
          .post("/api/v1/deficiencies", this.editedItem)
          .then((res) => {
            this.text = "Record Added Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            // this.students.data.push(res.data.student); 
            this.deficiencies = res.data.deficiencies
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