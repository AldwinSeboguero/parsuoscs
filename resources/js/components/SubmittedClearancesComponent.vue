<template>
  <v-container>
   <v-card>
     <v-data-table
        item-key="id"
        class="elevation-0"
        :loading="loading"
        loading-text="Loading... Please wait"
        :headers="headers"
        :page="page" 
        :pageCount="numberOfPages"
        :items="submittedclearances.data"
        :options.sync="options"
        :server-items-length="totalsubmittedclearances"
        :items-per-page="10"  
        :footer-props="{
          itemsPerPageOptions: [5, 10, 15],
          itemsPerPageText: 'Clearance Request Per Page',
          'show-current-page': true,
          'show-first-last-page': true,
        }"
      >
      <template v-slot:top>
        <v-row>
           <v-col cols="12" sm="2" style="margin: 0">
                          <v-select 
                            label="Select Semester"
                             item-value="id"
                              item-text="semester" 
                            color="primary" 
                            :items="semesters"
                            v-model="semester_id"
                            style="margin-left:15px"
                            @change="semesterChange(searchItem)"
          outlined
                          ></v-select>
                        </v-col>
         <v-col cols="12" sm="4">
        <v-text-field 
            append-icon="mdi-magnify"
            label="Search"
            
            v-model="searchItem"
            @input="searchIt"
          ></v-text-field>
          </v-col>
         
     </v-row>
        <v-toolbar flat color="white">
          <div class="overline text-h6">
              Submitted Clearance Request List
              <!-- <span class="font-italic subtitle-2"
                >(2nd Semester A/Y 2020-2021 )</span
              > -->
            </div>
          <v-spacer></v-spacer> 
        </v-toolbar>
      </template>
      <template v-slot:item.id="{ item }">
      <td>{{submittedclearances.data.indexOf(item)+1}}</td> 
    </template>
       <template v-slot:item.datesubmitted="{ item }" >
        <v-chip text-color="white" color="success" small >
           
            {{ item.datesubmitted }}
        </v-chip>
         </template>
      <template v-slot:item.actions="{ item }">
          <template>
        <v-btn class="ma-2" color="error" text depressed small @click="generatePDF(item)"
          ><v-icon>mdi-file-pdf</v-icon></v-btn
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
    </v-card>
  </v-container>
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
    
    searchItem: '',
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
      { text: "Date Submitted", value: "datesubmitted" }, 
      { text: "Action", value: "actions" },
    ], 
    page: 0,
    totalsubmittedclearances: 0,
    numberOfPages: 0,
    options: {},
     submittedclearances: [],
     semesters:{
         id: "", 
      semester: "",
     },
     semester_id:0, 
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
     options: {
      handler() {
        this.searchIt(this.searchItem);
      },
    },
    deep: true,
  },

  created() {
    this.initialize();
  },

  methods: {
    generatePDF(item) { 
      this.editedIndex = this.submittedclearances.data.indexOf(item);
      this.editedItem = Object.assign({}, item); 
    if(item.college == "School of Graduate Studies and Research")
    {
       axios.get('/api/v1/pdf-createSGS',{responseType: 'blob'
     ,params: { 'clearance': this.editedItem.clearance_id }

     }).then((response) => {
     var fileURL = window.URL.createObjectURL(new Blob([response.data], {type: 'application/pdf'}));
     var fileLink = document.createElement('a');
     fileLink.href = fileURL;
     fileLink.setAttribute('download', this.editedItem.name+this.editedItem.clearance_id+'.pdf');
     document.body.appendChild(fileLink);
     fileLink.click();
    

    });}
    
    else{
       axios.get('/api/v1/pdf-create',{responseType: 'blob'
     ,params: { 'clearance': this.editedItem.clearance_id }

     }).then((response) => {
     var fileURL = window.URL.createObjectURL(new Blob([response.data], {type: 'application/pdf'}));
     var fileLink = document.createElement('a');
     fileLink.href = fileURL;
     fileLink.setAttribute('download', this.editedItem.name+this.editedItem.clearance_id+'.pdf');
     document.body.appendChild(fileLink);
     fileLink.click();
    });}

    },
     readDataFromAPI() {
      this.loading = true;
      const { page, itemsPerPage } = this.options;
      let pageNumber = page;
      axios
        .get(`/api/v1/submittedclearances?page=` + pageNumber, {
          params: { 'per_page': itemsPerPage },
        })
        .then((response) => {
          //Then injecting the result to datatable parameters.
          this.loading = false;
         this.submittedclearances = response.data.submittedclearances; 
          this.totalsubmittedclearances = response.data.submittedclearances.total;
          this.numberOfPages = response.data.submittedclearances.last_page;
        });
    },


    semesterChange(d) {
      
      if (d.length > 2) {
     
        axios
          .get(`/api/v1/submittedclearances/${d}?page=` + 1, {
          params: { 'per_page': 10,
          'id' : d,
          'semester_id' : this.semester_id,
          },
        })
          .then((res) => {
            this.loading = false;   
            this.page = res.data.submittedclearances.current_page;
            this.submittedclearances = res.data.submittedclearances; 
            this.totalsubmittedclearances = res.data.submittedclearances.total;
            this.numberOfPages = res.data.submittedclearances.total_pages;
          })
          .catch((err) => {
            console.error(err);
          });
      }
      if (d.length <= 0) {
     
        axios
          .get(`/api/v1/submittedclearances?page=0`, {
            params: { 'per_page': 10,
          'semester_id' : this.semester_id, },
          })
          .then((res) => {
            this.loading = false;  
          this.page = res.data.submittedclearances.current_page;
            this.submittedclearances = res.data.submittedclearances;
            this.totalsubmittedclearances = res.data.submittedclearances.total;
            this.numberOfPages = res.data.submittedclearances.total_pages;
          })
          .catch((err) => {
            console.error(err);
          });
      }
    }, 
    searchIt(d) {
      
      if (d.length > 2) {
        const { page, itemsPerPage } = this.options;
         let pageNumber = page;
        axios
          .get(`/api/v1/submittedclearances/${d}?page=` + pageNumber, {
          params: { 'per_page': itemsPerPage,
          'id' : d,
          'semester_id' : this.semester_id,
          },
        })
          .then((res) => {
            this.loading = false;   
            this.page = res.data.submittedclearances.current_page;
            this.submittedclearances = res.data.submittedclearances; 
            this.totalsubmittedclearances = res.data.submittedclearances.total;
            this.numberOfPages = res.data.submittedclearances.total_pages;
          })
          .catch((err) => {
            console.error(err);
          });
      }
      if (d.length <= 0) {
         const { page, itemsPerPage } = this.options;
         let pageNumber = page;
        axios
          .get(`/api/v1/submittedclearances?page=` + pageNumber, {
            params: { 'per_page': itemsPerPage,
          'semester_id' : this.semester_id, },
          })
          .then((res) => {
            this.loading = false;  
            this.page = res.data.submittedclearances.current_page;
            this.submittedclearances = res.data.submittedclearances;
            this.totalsubmittedclearances = res.data.submittedclearances.total;
            this.numberOfPages = res.data.submittedclearances.total_pages;
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
       axios.get('/api/v1/semesters',{})
      .then(res => {
        this.semesters = res.data.semesters
      })
      .catch(err => {
        console.error(err); 
      });
    },

    editItem(item) {
      this.editedIndex = this.submittedclearances.data.indexOf(item);
      this.editedItem = Object.assign({}, item); 
      this.dialog = true;
    },    
    deleteItem(item) {
      const index = this.submittedclearances.data.indexOf(item);
      let decide = confirm("Are you sure you want to delete this item?");
      if (decide) {
        axios
          .delete("/api/v1/submittedclearances/" + item.id)
          .then((res) => {
            this.text = "Record Deleted Successfully!"; 
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            this.submittedclearances.data.splice(index, 1);
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Deleting Record";
            this.snackbarColor ="error darken-1";
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
      console.log(this.editedItem);
      if (this.editedIndex > -1) {
        const index = this.editedIndex;
        axios
          .put("/api/v1/submittedclearances/" + this.editedItem.id, this.editedItem)
          .then((res) => {
            this.text = "Record Updated Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            Object.assign(this.submittedclearances.data[index], res.data.submittedclearance); 
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Updating Record";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;
          });
      } else {
        axios
          .post("/api/v1/submittedclearances", this.editedItem)
          .then((res) => {
            this.text = "Record Added Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            // this.students.data.push(res.data.student); 
            this.submittedclearances = res.data.submittedclearances
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