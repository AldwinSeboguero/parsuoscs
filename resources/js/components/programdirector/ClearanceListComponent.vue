<template>
  <v-container>
   <v-card>
     <v-card-subtitle class="white--text text-uppercase elevation-2 mb-0 pb-1"   style="background: linear-gradient(to left, #1A237E, #1A237E, #0D47A1);">
          <span class="text-h6"> Clearance List </span>

    </v-card-subtitle>
     <v-card-title class="white--text elevation-2 mb-0 pb-0 mt-0 pt-0"  style="background: linear-gradient(to left, #1A237E, #1A237E, #0D47A1);">
         <v-text-field 
            append-icon="mdi-magnify"
            label="Search"
            class="mb-0 pb-0 mt-0 pt-0"
            v-model="search"
            solo-inverted
            flat
            dark
            dense
          ></v-text-field>
         
           
    </v-card-title>

    <v-container>
     <v-data-table
        item-key="id"
        class="elevation-0"
        :loading="loading"
        loading-text="Loading... Please wait"
        :headers="headers"
        :page="page + 1"
        :pageCount="numberOfPages"
        :items="clearances.data"
        :options.sync="options"
        :server-items-length="totalclearances"
        :items-per-page="10" 
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
              Clearance List
              
            </div>
          <v-spacer></v-spacer> 
        </v-toolbar>
      </template>
      <template v-slot:item.id="{ item }">
      <td>{{clearances.data.indexOf(item)+1}}</td> 
    </template>
    
      <template v-slot:item.actions="{ item }">
          <template>
        <v-btn class="ma-2" color="primary" depressed small 
          >View</v-btn
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
    success: "",
    error: "", 
    snackbarColor:"",
      headers: [
      {
        text: "No",
        align: "left",
        value: "id",
      },  
      { text: "Clearance Id", value: "clearance_id" },  
      { text: "Student Number", value: "student_number" },
      { text: "Name", value: "name" },
      { text: "Program", value: "program" }, 
      { text: "Purpose", value: "purpose" },  
      { text: "Semester", value: "semester" },  
      { text: "Action", value: "actions" },
    ], 
    page: 0,
    totalclearances: 0,
    numberOfPages: 0,
    options: {},
     clearances: [], 
    editedIndex: -1,
    editedItem: {
      id: "", 
      name: "",
      student_number: "",
      program: "",
      purpose: "", 
      semester:"", 
    },
    defaultItem: {
       id: "", 
      name: "",
      student_number: "",
      program: "",
      purpose: "", 
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
        .get(`/api/v1/clearances?page=` + pageNumber, {
          params: { 'per_page': itemsPerPage },
        })
        .then((response) => {
          //Then injecting the result to datatable parameters.
          this.loading = false;
         this.clearances = response.data.clearances; 
          this.totalclearances = response.data.clearances.total;
          this.numberOfPages = response.data.clearances.last_page;
        });
    },

    searchIt(d) {
      if (d.length > 2) {
        const { page, itemsPerPage } = this.options;
        axios
          .get(`/api/v1/clearances/${d}`)
          .then((res) => {
            this.loading = false;  
            this.clearances = res.data.clearances; 
            this.totalclearances = res.data.clearances.total;
            this.numberOfPages = res.data.clearances.last_page;
          })
          .catch((err) => {
            console.error(err);
          });
      }
      if (d.length <= 0) {
        axios
          .get(`/api/v1/clearances?page=${d.page}`, {
            params: { 'per_page': d.itemsPerPage },
          })
          .then((res) => {
            this.loading = false;  
            this.clearances = res.data.clearances;
            this.totalclearances = res.data.clearances.total;
            this.numberOfPages = res.data.clearances.last_page;
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
      this.editedIndex = this.clearances.data.indexOf(item);
      this.editedItem = Object.assign({}, item); 
      this.dialog = true;
    },    
    deleteItem(item) {
      const index = this.clearances.data.indexOf(item);
      let decide = confirm("Are you sure you want to delete this item?");
      if (decide) {
        axios
          .delete("/api/v1/clearances/" + item.id)
          .then((res) => {
            this.text = "Record Deleted Successfully!"; 
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            this.clearances.data.splice(index, 1);
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
          .put("/api/v1/clearances/" + this.editedItem.id, this.editedItem)
          .then((res) => {
            this.text = "Record Updated Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            Object.assign(this.clearances.data[index], res.data.clearance); 
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Updating Record";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;
          });
      } else {
        axios
          .post("/api/v1/clearances", this.editedItem)
          .then((res) => {
            this.text = "Record Added Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            // this.students.data.push(res.data.student); 
            this.clearances = res.data.clearances
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