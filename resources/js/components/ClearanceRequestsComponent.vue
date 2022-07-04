<template>
  <v-container>
      <v-dialog
      v-model="dialog"
      persistent
      max-width="400"
      v-if="editedIndex > -1"
    >
     
      <v-card>
        <v-card-subtitle class="white--text text-uppercase elevation-2 pt-4"   style="background: linear-gradient(to left, #1A237E, #1A237E, #0D47A1);">
          <span class="text-h6"> Approving Request </span>

    </v-card-subtitle>
       
        <v-card-text class="pt-4">This will certifiy that {{studentName}} is cleared from any property and money responsibility as of this date.</v-card-text>
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

      <v-dialog
     v-model="deferDialog" persistent max-width="500"
    >
     
      <v-card>
        <v-card-subtitle class="white--text text-uppercase elevation-2 pt-4"   style="background: linear-gradient(to left, #1A237E, #1A237E, #0D47A1);">
          <span class="text-h6">Add Deficiency </span>

    </v-card-subtitle>
       
        <v-card-text class="pt-4">
            <v-col cols="12" sm="12" md="12">
                <v-text-field filled class="elevation-0" v-model="deficiency.title" label="Item of Deficiency*" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="12">
                <v-textarea filled v-model="deficiency.note"  label="Additional Information" hint="Notes or Instructions for student"></v-textarea>
              </v-col>
          
       
          <small>*indicates required field</small>
        </v-card-text>
        <v-card-actions>
           <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="deferDialog = false">Close</v-btn>
          <v-btn color="blue darken-1" text @click="deferItem()">Save</v-btn>
        </v-card-actions>
        
      </v-card>
    </v-dialog>
   

   <v-card> 
     <v-card-subtitle class="white--text text-uppercase elevation-2 mb-0 pb-1"   style="background: linear-gradient(to left, #1A237E, #1A237E, #0D47A1);">
          <span class="text-h6"> Clearance Requests </span>

    </v-card-subtitle>
     <v-card-title class="white--text elevation-2 mb-0 pb-6 mt-0 pt-2"  style="background: linear-gradient(to left, #1A237E, #1A237E, #0D47A1);">
     <v-row>
        <v-col
        class="mb-0 pb-0 mt-0 pt-0"
        cols="12"
        md="3">
        <v-select 
        label="Select Semester"
        class="mb-0 pb-0 mt-2 pt-0"

        item-value="id"
        item-text="semester" 
        :items="semesters"
        v-model="semester"
     
        solo-inverted
        flat
        dark
        dense
        hide-details
        ></v-select>

        </v-col>
         <v-col
           class="mb-0 pb-0 mt-0 pt-0"
          cols="12"
          md="3">
            <v-select 
            label="Select College"
            class="mb-0 pb-0 mt-2 pt-0"

            item-value="id"
            item-text="name" 
            :items="colleges"
            v-model="college"
            
            solo-inverted
            flat
            dark
            dense
            hide-details
            ></v-select>
            
            </v-col>
            <v-col
           class="mb-0 pb-0 mt-0 pt-0"
          cols="12"
          md="3">
            <v-select 
            label="Select Program"
            class="mb-0 pb-0 mt-2 pt-0"

            item-value="id"
            item-text="name" 
            :items="programs"
            v-model="program"
            
            solo-inverted
            flat
            dark
            dense
            hide-details
            ></v-select>
            
            </v-col>

            <v-col
            cols="12"
            md="3"
            class="mb-0 pb-0 mt-0 pt-0"

            > 
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
            hide-details
            ></v-text-field>
          </v-col>
        </v-row>

    </v-card-title>

     <v-data-table
        item-key="id"
        class="px-6 pb-6  mt-2"
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
    
      
        
      <!-- <template v-slot:item.id="{ item }">
      <td>{{clearancerequests.data.indexOf(item)+1}}</td> 
    </template> -->

     <template v-slot:item.request_at="{ item }" >
        <v-chip text-color="white" color="success" small >
           
            {{ item.request_at }}
        </v-chip>
         </template>
       
      <template v-slot:item.actions="{ item }">
          <template>
        <v-btn class="ma-2 pr-5 pl-4 text-center" color="success" depressed x-small  @click="editItem(item)"
          ><v-icon
          dark
          x-small
        >
          mdi-check-circle-outline
        </v-icon>APPROVE</v-btn
        > 
         </template> 
            <template>
        <v-btn class="ma-2" color="error" depressed x-small @click="defer(item)"
          ><v-icon
          dark
          x-small
        >
          mdi-close-circle-outline
        </v-icon>DISAPPROVE</v-btn
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
import debounce from "lodash/debounce";

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
    
    searchItem: '',
    snackbarColor:"",
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
       { text: "Date Requested", value: "request_at" },
      { text: "Action", value: "actions" },
    ], 
    page: 0,
    totalclearancerequests: 0,
    numberOfPages: 0,
    options: {},
    clearancerequests: [], 
    semesters: [],
    colleges: [],
    programs:[],
    semester: '',
    college: '',
    program:'',
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
    searchItem: debounce(function (val) {
      this.loading = true;
      
      const { page, itemsPerPage } = this.options;
      let pageNumber = page;
      axios
      .get(`/api/v1/clearancerequests?page=` + pageNumber, {
        params: { 'per_page': itemsPerPage,
          'search': val,
          'semester': this.semester,
          'college': this.college,
          'program': this.program,  },
      })
      .then((response) => {
        //Then injecting the result to datatable parameters.
        this.clearancerequests = response.data.clearance_requests; 
        this.totalclearancerequests = response.data.clearance_requests.total;
        this.numberOfPages = response.data.clearance_requests.last_page;
        this.loading = false;

      });
      
    }, 300),

    semester: debounce(function (val) {
      // this.college = '';
      // this.program = '';
      this.loading = true;
      
      const { page, itemsPerPage } = this.options;
      let pageNumber = page;
      axios
      .get(`/api/v1/clearancerequests?page=` + pageNumber, {
        params: { 'per_page': itemsPerPage,
          'semester': val,
          'search': this.searchItem,
          'college': this.college,
          'program': this.program, },
      })
      .then((response) => {
        //Then injecting the result to datatable parameters.
        
        // this.colleges = response.data.colleges; 

        this.clearancerequests = response.data.clearance_requests; 
        this.totalclearancerequests = response.data.clearance_requests.total;
        this.numberOfPages = response.data.clearance_requests.last_page;
        this.loading = false;

      });
      
    }, 300),
    college: debounce(function (val) {
      this.loading = true;
      this.program = '';
      
      const { page, itemsPerPage } = this.options;
      let pageNumber = page;
      axios
      .get(`/api/v1/clearancerequests?page=` + pageNumber, {
        params: { 'per_page': itemsPerPage,
          'semester': this.semester,
          'program': this.program,
          'search': this.searchItem,
          'college': val },
      })
      .then((response) => {
        //ThhIten injecting the result to datatable parameters.
        this.programs = response.data.programs; 

        this.clearancerequests = response.data.clearance_requests; 
        this.totalclearancerequests = response.data.clearance_requests.total;
        this.numberOfPages = response.data.clearance_requests.last_page;
        this.loading = false;

      });
      
    }, 300),
    program: debounce(function (val) {
      this.loading = true;
      
      const { page, itemsPerPage } = this.options;
      let pageNumber = page;
      axios
      .get(`/api/v1/clearancerequests?page=` + pageNumber, {
        params: { 'per_page': itemsPerPage,
          'semester': this.semester,
          'search': this.searchItem,
          'program': val,
          'college': this.college },
      })
      .then((response) => {
        //Then injecting the result to datatable parameters.
        // this.programs = response.data.programs; 

        this.clearancerequests = response.data.clearance_requests; 
        this.totalclearancerequests = response.data.clearance_requests.total;
        this.numberOfPages = response.data.clearance_requests.last_page;
        this.loading = false;

      });
      
    }, 300),


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
    this.readDataFromAPI();
  },

  methods: {
    clean($val) {
      if($val){$val = $val.replace(/ +(?= )/g, "");
      $val = $val.replace(/[&\/\\#,+()$~%.'":*?<>{}]/g, " "); // Replaces all spaces with hyphens.
      $val = $val.replace(/ +(?= )/g, "");
      
      return $val;
      }
       // Removes special chars.
    },
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
          this.semesters = response.data.semesters; 
          this.colleges = response.data.colleges; 
          this.programs = response.data.programs; 
        
         this.clearancerequests = response.data.clearance_requests; 
          this.totalclearancerequests = response.data.clearance_requests.total;
          this.numberOfPages = response.data.clearance_requests.last_page;
        });
    },

    searchIt(d) {
       const { page, itemsPerPage } = this.options;
           let pageNumber = page;
      if (d.length > 2) {
       
        axios
          .get(`/api/v1/clearancerequests?page=` + pageNumber, {
          params: { 'per_page': itemsPerPage,
             'semester': this.semester,
          'search': this.searchItem,
          'program': this.program,
          'college': this.college},
        })
          .then((res) => {
            this.loading = false;  
            this.semesters = res.data.semesters; 
            this.colleges = res.data.colleges; 
            this.programs = res.data.programs; 
            this.clearancerequests = res.data.clearance_requests; 
            this.totalclearancerequests = res.data.clearance_requests.total;
            this.numberOfPages = res.data.clearance_requests.last_page;
          })
          .catch((err) => {
            console.error(err);
          });
      }
      if (d.length <= 0) {
        axios
          .get(`/api/v1/clearancerequests?page=` + pageNumber, {
            params: { 'per_page': d.itemsPerPage },
          })
          .then((res) => {
            this.loading = false;  
            this.semesters = res.data.semesters; 
            this.colleges = res.data.colleges; 
            this.programs = res.data.programs; 
            this.clearancerequests = res.data.clearance_requests;
            this.totalclearancerequests = res.data.clearance_requests.total;
            this.numberOfPages = res.data.clearance_requests.last_page;
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
      // console.log(this.clearanceRequest.id);
        axios
          .post("/api/v1/clearancerequests/approve", 
          {
             'clearanceRequest': this.clearanceRequest,
              'semester': this.semester,
              'college': this.college ,
              'program': this.program,
          })
          .then((res) => {
            this.text = "Successfully Approved!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            this.clearancerequests = res.data.clearance_requests; 
            this.totalclearancerequests = res.data.clearance_requests.total;
            this.numberOfPages = res.data.clearance_requests.last_page;
          this.dialog = false;
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Approving Request";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;
          });
      
      this.close();
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
          .get("/api/v1/clearancerequests/disapprove",
          {
            params: {
              'requestId': this.clearanceRequest.id,
              'title': this.deficiency.title,
              'note': this.deficiency.note,
              'semester': this.semester,
              'college': this.college ,
              'program': this.program,
            },
          })
          .then((res) => {
            this.text = "Record Updated Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true; 
            this.loading = false;
          this.clearancerequests = res.data.clearance_requests; 
            this.totalclearancerequests = res.data.clearance_requests.total;
            this.numberOfPages = res.data.clearance_requests.last_page;
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
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
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