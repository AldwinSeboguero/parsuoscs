<template>
  <v-sheet>
   <v-card>
    <v-container>
     
   <div class="row">
      <div class="form-row">
        <div class="col-md-12">
          <label class="form-control-label"  for="input-file-import">Upload Excel File</label>
          <input type="file" class="form-control" :class="{ ' is-invalid' : error.message }" id="input-file-import" name="file_import" ref="import_file"  @change="onFileChange">
          <div v-if="error.message" class="invalid-feedback">
            
          </div>
          <v-btn @click="proceedAction">Import</v-btn>
        </div>
      </div>
   </div> 
     <v-data-table
        item-key="id"
        class="elevation-0"
        :loading="loading"
        loading-text="Loading... Please wait"
        :headers="headers"
        :page="page + 1"
        :pageCount="numberOfPages"
        :items="siasaccounts.data"
        :options.sync="options"
        :server-items-length="totalStudents"
        :items-per-page="10"  
        :footer-props="{
          itemsPerPageOptions: [5, 10, 15],
          itemsPerPageText: 'Student Per Page',
          'show-current-page': true,
          'show-first-last-page': true,
        }"
      >
      <template v-slot:top>
        <v-text-field 
            append-icon="mdi-magnify"
            label="Search"
            v-model="searchItem"
            @input="searchIt"
          ></v-text-field>
        <v-toolbar flat color="white">
          <div class="overline text-h6">
              SIAS Account
            </div>
          <v-spacer></v-spacer>

        </v-toolbar>
      </template>
      <template v-slot:item.id="{ item }">
      <td>{{siasaccounts.data.indexOf(item)+1}}</td> 
    </template>
       
      <template v-slot:item.actions="{ item }">
        <v-icon small class="mr-2" @click="editItem(item)"> mdi-pencil </v-icon>
        <v-icon small class="mr-2" @click="deleteItem(item)">
          mdi-delete-forever
        </v-icon>
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
    error: {},
    import_file: '',
    valid: true,
    dialog: false,
    loading: false,
    snackbar: false,
    selected: [],
    text: "",
    success: "",
    error: "", 
    snackbarColor:"",
    searchItem: '',
    headers: [
      {
        text: "No",
        align: "left",
        value: "id",
      },
      { text: "Name", value: "name" },
      { text: "User ID", value: "user_id" },
      { text: "Password", value: "password" },
    ],
    page: 0,
    totalStudents: 0,
    numberOfPages: 0,
    options: {},
    siasaccounts: {},
    campuses: {},
    sections: {},
    programs: {},
    years: [1,2,3,4,5], 
    rules: {
      required: (v) => !!v || "This Field is Required",
      min: (v) => v.length >= 5 || "Minimum 5 Characters Required",
      validEmail: (v) => /.+@.+\..+/.test(v) || "Email must be valid",
    },
    editedIndex: -1,
    editedItem: {
      id: "",
      siasaccount_number: "",
      name: "",
      campus: "",
      section: "",
      program: "",
      campus_id: "",
      section_id: "",
      program_id: "",
      code:"",
      year: "",
      created_at: "",
    },
    defaultItem: {
      id: "",
      siasaccount_number: "",
      name: "",
      campus: "",
      section: "",
      program: "",
      campus_id: "",
      section_id: "",
      program_id: "",
      code:"",
      year: "",
      created_at: "",
    },
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Item" : "Edit Item";
    },
    formIcon() {
      return this.editedIndex === -1 ? "mdi-plus" : "mdi-pen";
    }, 
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
     onFileChange(e) {
        this.import_file = e.target.files[0];
    },

    proceedAction() {
        let formData = new FormData();
        formData.append('import_file', this.import_file);

          axios.post('api/v1/siasaccounts/import', formData, {
              headers: { 'content-type': 'multipart/form-data' }
            })
            .then(response => {
                if(response.status === 200) {
                  // codes here after the file is upload successfully
                }
            })
            .catch(error => {
                // code here when an upload is not valid
                this.uploading = false
                this.error = error.response.data
                console.log('check error: ', this.error)
            });
        },

     readDataFromAPI() {
      this.loading = true;
      const { page, itemsPerPage } = this.options;
      let pageNumber = page;
      axios
        .get(`/api/v1/siasaccounts?page=` + pageNumber, {
          params: { 'per_page': itemsPerPage },
        })
        .then((response) => {
          //Then injecting the result to datatable parameters.
          this.loading = false;
         this.siasaccounts = response.data.siasaccounts;
          this.campuses = response.data.campuses;
          this.programs = response.data.programs;
          this.sections = response.data.sections;
          this.totalStudents = response.data.siasaccounts.total;
          this.numberOfPages = response.data.siasaccounts.last_page;
        });
    },

    searchIt(d) {
      if (d.length > 2) {
        const { page, itemsPerPage } = this.options;
        let pageNumber = page;
        axios
          .get(`/api/v1/siasaccounts/${d}?page=` + pageNumber, {
          params: { 'per_page': itemsPerPage },
        })
          .then((res) => {
            this.loading = false;  
            this.siasaccounts = res.data.siasaccounts;
            console.log(this.siasaccounts); 
          this.campuses = res.data.campuses;
          this.programs = res.data.programs;
          this.sections = res.data.sections;
            this.totalStudents = res.data.siasaccounts.total;
            this.numberOfPages = res.data.siasaccounts.last_page;
          })
          .catch((err) => {
            console.error(err);
          });
      }
      if (d.length <= 0) {
        const { page, itemsPerPage } = this.options;
        let pageNumber = page;
        axios
          .get(`/api/v1/siasaccounts?page=`+ pageNumber, {
          params: { 'per_page': itemsPerPage },
          })
          .then((res) => {
            this.loading = false;   
          this.campuses = res.data.campuses;
          this.programs = res.data.programs;
          this.sections = res.data.sections;
            this.siasaccounts = res.data.siasaccounts;
            this.totalStudents = res.data.siasaccounts.total;
            this.numberOfPages = res.data.siasaccounts.last_page;
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
      this.editedIndex = this.siasaccounts.data.indexOf(item);
      this.editedItem = Object.assign({}, item);
      console.log(this.editedItem);
      this.dialog = true;
    },
   
    deleteItem(item) {
      const index = this.siasaccounts.data.indexOf(item);
      let decide = confirm("Are you sure you want to delete this item?");
      if (decide) {
        axios
          .delete("/api/v1/siasaccounts/" + item.id)
          .then((res) => {
            this.text = "Record Deleted Successfully!"; 
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            this.siasaccounts.data.splice(index, 1);
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
          .put("/api/v1/siasaccounts/" + this.editedItem.id, this.editedItem)
          .then((res) => {
            this.text = "Record Updated Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            Object.assign(this.siasaccounts.data[index], res.data.siasaccount);
            console.log(this.editedItem);
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Updating Record";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;
          });
      } else {
        axios
          .post("/api/v1/siasaccounts", this.editedItem)
          .then((res) => {
            this.text = "Record Added Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            // this.siasaccounts.data.push(res.data.siasaccount); 
            this.siasaccounts = res.data.siasaccounts
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