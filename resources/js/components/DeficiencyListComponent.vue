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
        <v-text-field 
            append-icon="mdi-magnify"
            label="Search"
            @input="searchIt"
          ></v-text-field>
        <v-toolbar flat color="white">
          <div class="overline text-h6">
              Deficiency List
              
            </div>
          <v-spacer></v-spacer> 
        </v-toolbar>
      </template>
      <template v-slot:item.id="{ item }">
      <td>{{deficiencies.data.indexOf(item)+1}}</td> 
    </template>
    <template v-slot:item.completed="{ item }">
        <v-chip text-color="white" color="success" small  v-if="item.completed == 1">
           
           Completed
        </v-chip>
         <v-chip text-color="white" color="warning" small  v-if="item.completed != 1">
           
           Pending
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
      { text: "Name", value: "student_name" },
      { text: "Completed", value: "completed" }, 
       { text: "Deficiency", value: "deficiency" }, 
       { text: "Note", value: "note" },
      { text: "Staff", value: "staff" },   
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
      this.dialog = true;
    },    
    deleteItem(item) {
      const index = this.deficiencies.data.indexOf(item);
      let decide = confirm("Are you sure you want to delete this item?");
      if (decide) {
        axios
          .delete("/api/v1/deficiencies/" + item.id)
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
          .put("/api/v1/deficiencies/" + this.editedItem.id, this.editedItem)
          .then((res) => {
            this.text = "Record Updated Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            Object.assign(this.deficiencies.data[index], res.data.deficiency); 
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Updating Record";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;
          });
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