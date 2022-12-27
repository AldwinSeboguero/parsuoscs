<template>
  <v-container>
   <v-card> 
   <v-card-subtitle class="white--text text-uppercase elevation-2 mb-0 pb-1"   style="background: linear-gradient(to left, #1A237E, #1A237E, #0D47A1);">
          <span class="text-h6">  Approved Requests </span>

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
     <v-data-table
        item-key="id"
        class="px-6 pb-6  mt-4"

        :loading="loading"
        loading-text="Loading... Please wait"
        :headers="headers"
        :page="page + 1"
        :pageCount="numberOfPages"
        :items="clearancerequests.data"
        :options.sync="options"
        :server-items-length="totalclearancerequests"
        :items-per-page="10"  
        :footer-props="{
          itemsPerPageOptions: [5, 10, 15],
          itemsPerPageText: 'Clearance Request Per Page',
          'show-current-page': true,
          'show-first-last-page': true,
        }"
      >
       <template v-slot:item.approved_at="{ item }" >
        <v-chip text-color="white" color="success" small >
           
            {{ item.approved_at }}
        </v-chip>
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
    searchItem: "",
      headers: [
      {
        text: "#",
        align: "left",
        value: "id",
      }, 
      
      { text: "Student Number", value: "student_number" },
      { text: "Name", value: "name" },
      { text: "Program", value: "program" },
       { text: "Purpose", value: "purpose" },
       { text: "Signatory", value: "staff" }, 
        { text: "Date Approved", value: "approved_at" }, 
    ], 
    page: 0,
    totalclearancerequests: 0,
    numberOfPages: 0,
    options: {},
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
     readDataFromAPI() {
      this.loading = true;
      const { page, itemsPerPage } = this.options;
      let pageNumber = page;
      axios
        .get(`/api/v1/clearedclearancerequests?page=` + pageNumber, {
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
          .get(`/api/v1/clearedclearancerequests/${d}?page=` + pageNumber, {
          params: { 'per_page': itemsPerPage,
          'id' : d,
          'semester_id' : this.semester_id,
          },
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
        const { page, itemsPerPage } = this.options;
         let pageNumber = page;
        axios
          .get(`/api/v1/clearedclearancerequests?page=${pageNumber}`, {
            params: { 'per_page': itemsPerPage,
          'semester_id' : this.semester_id, },
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
      this.dialog = true;
    },    
    deleteItem(item) {
      const index = this.clearancerequests.data.indexOf(item);
      let decide = confirm("Are you sure you want to delete this item?");
      if (decide) {
        axios
          .delete("/api/v1/clearedclearancerequests/" + item.id)
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
          .put("/api/v1/clearedclearancerequests/" + this.editedItem.id, this.editedItem)
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
          .post("/api/v1/clearedclearancerequests", this.editedItem)
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