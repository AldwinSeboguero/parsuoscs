<template>
  <v-container fluid class="ma-0 pa-0">
  <v-row >
      <v-col cols="12" lg="5" class="mt-2">
       <Breadcrumbs class="mb-4"/>

      </v-col>
    </v-row>


    <v-row class="container-fluid ml-2 mt-0">
      <v-col cols="12" lg="3">
        <v-card class=" rounded-medium pt-1  px-2"
            elevation="2">
            
            <v-card-title class="align-end pl-4 pa-2 mt-2 mb-3 rounded white--text elevation-1" style=" margin-left: -16px; margin-right: -16px; max-height: 50px; background: linear-gradient(to right, #0d47a1, #0d47a1, #1A237E);">
              <span class="font-semibold overline"><v-icon dark left >{{formIcon}}</v-icon>{{formName}}</span>

            </v-card-title>

            <v-card-text>
            <v-form 
                method="post"
                lazy-validation
                v-on:submit.stop.prevent="save"
                ref="entryForm"
              >
              <label class="black--text font-weight-medium mt-2" for="">Purpose</label>

              <v-autocomplete
                v-model="forms.purpose"
                :items="purposes"
                :search-input.sync="search"
                chips
                clearable
                item-text="purpose"
                item-value="id"
                item-key="id" 
                outlined
                dense
                hide-details
              class="mb-2"
                :offset-y="offSet"
              >
                <template v-slot:no-data>
                  <v-list-item>
                    <v-list-item-title>
                      Search Purpose 
                    </v-list-item-title>
                  </v-list-item>
                </template>
                <template v-slot:selection="{ attr, on, item, selected }">
                  <v-chip
                    v-bind="attr"
                    :input-value="selected"
                    class="blue darken-3 white--text rounded"
                    v-on="on"
                  >
                    
                    <span class="text-truncate text-uppercase" v-text="item.purpose"></span>
                  </v-chip>
                </template>
                <template v-slot:item="{ item }">
                  
                  <v-list-item-content>
                    <v-list-item-title class="text-uppercase" v-text="item.purpose"></v-list-item-title> 
                  </v-list-item-content> 
                </template>
              </v-autocomplete>
              <label class="black--text font-weight-medium" for="">Semester</label>

              <v-autocomplete
                v-model="forms.semester"
                :items="semesters"
                :search-input.sync="search"
                chips
                clearable
                item-text="semester"
                item-value="id"
                item-key="id" 
                outlined
                dense
              class="mb-2"
                hide-details
                :offset-y="offSet"
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
                    class="blue darken-3 white--text rounded"
                    v-on="on"
                  >
                    
                    <span class="text-truncate" v-text="item.semester"></span>
                  </v-chip>
                </template>
                <template v-slot:item="{ item }">
                  
                  <v-list-item-content>
                    <v-list-item-title v-text="item.semester"></v-list-item-title> 
                  </v-list-item-content> 
                </template>
              </v-autocomplete>
              
                <label class="black--text font-weight-medium mt-2" for="">Designation</label>

                <v-autocomplete
                  v-model="forms.designation"
                  :items="designations"
                  chips
                  clearable
                  item-text="name"
                  item-value="id"
                  item-key="id" 
                  outlined
                  dense
                  hide-details
                  class="mb-2"
                  :offset-y="offSet"
                >
                  <template v-slot:no-data>
                    <v-list-item>
                      <v-list-item-title>
                        Search Designation 
                      </v-list-item-title>
                    </v-list-item>
                  </template>
                  <template v-slot:selection="{ attr, on, item, selected }">
                    <v-chip
                      v-bind="attr"
                      :input-value="selected"
                      class="blue darken-3 white--text rounded"
                      v-on="on"
                    >
                       
                      <span class="text-truncate" v-text="item.name"></span>
                    </v-chip> 
                  </template>
                  <template v-slot:item="{ item }">
                    
                    <v-list-item-content>
                      <v-list-item-title v-text="item.name"></v-list-item-title> 
                    </v-list-item-content> 
                  </template>
                </v-autocomplete>
              <label class="black--text font-weight-medium mt-2" for="">College</label>

                <v-autocomplete
                  v-model="forms.college"
                  :items="colleges"
                  :search-input.sync="search"
                  chips
                  clearable
                  item-text="name"
                  item-value="id"
                  item-key="id" 
                  outlined
                  dense
                  hide-details
                  class="mb-2"
                  :offset-y="offSet"
                >
                  <template v-slot:no-data>
                    <v-list-item>
                      <v-list-item-title>
                        Search Campus 
                      </v-list-item-title>
                    </v-list-item>
                  </template>
                  <template v-slot:selection="{ attr, on, item, selected }">
                    <v-chip
                      v-bind="attr"
                      :input-value="selected"
                      class="blue darken-3 white--text rounded"
                      v-on="on"
                    >
                       
                      <span class="text-truncate" v-text="item.name"></span>
                    </v-chip>
                  </template>
                  <template v-slot:item="{ item }">
                    
                    <v-list-item-content>
                      <v-list-item-title v-text="item.name"></v-list-item-title> 
                    </v-list-item-content> 
                  </template>
                </v-autocomplete>
                <label class="black--text font-weight-medium " for="" 
                >Program</label>

                <v-autocomplete
                  v-model="forms.program"
                  :items="programs"
                  :search-input.sync="search"
                  chips
                  clearable
                  item-text="short_name"
                  item-value="id"
                  item-key="id" 
                  outlined
                  dense
                  hide-details
                class="mb-2"
                  :offset-y="offSet"
                >
                  <template v-slot:no-data>
                    <v-list-item>
                      <v-list-item-title>
                        Search Program 
                      </v-list-item-title>
                    </v-list-item>
                  </template>
                  <template v-slot:selection="{ attr, on, item, selected }">
                    <v-chip
                      v-bind="attr"
                      :input-value="selected"
                      class="blue darken-3 white--text rounded"
                      v-on="on"
                    >
                      
                      <span class="text-truncate text-uppercase" v-text="item.short_name"></span>
                    </v-chip>
                  </template>
                  <template v-slot:item="{ item }">
                    
                    <v-list-item-content>
                      <v-list-item-title class="text-uppercase" v-text="item.short_name"></v-list-item-title> 
                    </v-list-item-content> 
                  </template>
                </v-autocomplete>
                <label class="black--text font-weight-medium" for="">Signatory</label>
              <v-autocomplete
                  v-model="forms.signatory"
                  :items="signatories"
                  chips
                  clearable
                  item-text="name"
                  item-value="id"
                  item-key="id" 
                  
                  dense
                  hide-details
                  outlined
                  class="mb-2"
                  :offset-y="offSet"
                >
                  <template v-slot:no-data>
                    <v-list-item>
                      <v-list-item-title>
                        Search 
                        <strong>Signatory</strong>
                      </v-list-item-title>
                    </v-list-item>
                  </template>
                  <template v-slot:selection="{ attr, on, item, selected }">
                    <v-chip
                      v-bind="attr"
                      :input-value="selected"
                      class="blue darken-3 white--text rounded"
                      v-on="on"
                    >
                       
                      <v-icon dark left>
                        mdi-badge-account
                      </v-icon>
                      
                      <span class="text-truncate"v-text="item.name"></span>
                    </v-chip>
                  </template>
                  <template v-slot:item="{ item }">
                    <v-list-item-avatar
                      color="indigo"
                      class="caption font-weight-light white--text"
                    >
                      {{ item.name[0]}}
                    </v-list-item-avatar>
                    <v-list-item-content>
                      <v-list-item-title v-text="item.name"></v-list-item-title> 
                    </v-list-item-content> 
                  </template>
                </v-autocomplete>
                    <v-divider />
                  
                   
              </v-form>
              
            </v-card-text>
            <v-card-actions>
                  
            </v-card-actions>
        </v-card>
      </v-col>
      <v-col cols="12" lg="9">
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
   

    <v-card class=" rounded-medium pt-1 pb-2 px-2"
            elevation="2">
   <v-card-title class="align-end pl-4 pa-2 mt-2 mb-3 rounded white--text elevation-1" style=" margin-left: -16px; margin-right: -16px; max-height: 50px; background: linear-gradient(to right, #0d47a1, #0d47a1, #1A237E);">
                <span class="font-semibold"><v-icon  dark left>mdi-list-box</v-icon>Clearance Requests</span>
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
        <v-chip text-color="white" color="success" x-small >
           
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
      </v-col>
    </v-row>
      
  </v-container>
</template>
<script>
import debounce from "lodash/debounce";

export default {
  data: () => ({
    showDeleteDialog: false,
        itemToDelete: null,
      formName:'Filters',
      formIcon : 'mdi-filter-variant',
      formAction : 'Save',
      formActionIcon : 'mdi-content-save',
      formActionColor : 'success',
      formActionLoading: false,
      isEditMode: false,
      showErrorDialog: false,
      errorMessage: '',
      result:' ',
      generateLoading: false,
      offSet: true,
      campus: {},
    options: {},
    staffs: {},
    campuses: {},
    colleges:{},
    programs:{},
    designations: {},
    semesters_prev: {},
    semesters_next: {},
    isProgramDisable: true,
    signatories:{},
    forms: {
      semester: '',
      college:'',
      program: '',
      designation: '',
      signatory: '',
      purpose:'',
      order: '',
      isForAllInCollege: false,
    },
    

    editedItem: {
      semester: '',
      college:'',
      program: '',
      designation: '',
      signatory: '',
      purpose:'',
      order: '',
      isForAllInCollege: false,


    },
    defaultItem: {
      semester: '',
      college:'',
      program: '',
      designation: '',
      signatory: '',
      purpose:'',
      order: '',
      isForAllInCollege: false,


    },
    purposes: {},
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
      // {
      //   text: "#",
      //   align: "left",
      //   value: "id",
      // }, 
      
      { text: "Student Number", value: "student_number" },
      { text: "Name", value: "name" },
      { text: "Program", value: "program" },
       { text: "Purpose", value: "purpose" },
      //  { text: "Signatory", value: "staff" },
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
      .get(`/api/v1/clearance-requests?page=` + pageNumber, {
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
      this.college = '';
      this.program = '';
      this.loading = true;
      
      const { page, itemsPerPage } = this.options;
      let pageNumber = page;
      axios
      .get(`/api/v1/clearance-requests?page=` + pageNumber, {
        params: { 'per_page': itemsPerPage,
          'semester': val,
          'search': this.searchItem,
          'college': this.college,
          'program': this.program, },
      })
      .then((response) => {
        //Then injecting the result to datatable parameters.
        
        this.colleges = response.data.colleges; 

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
      .get(`/api/v1/clearance-requests?page=` + pageNumber, {
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
      .get(`/api/v1/clearance-requests?page=` + pageNumber, {
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
        .get(`/api/v1/clearance-requests?page=` + pageNumber, {
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
          .get(`/api/v1/clearance-requests?page=` + pageNumber, {
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
          .get(`/api/v1/clearance-requests?page=` + pageNumber, {
            params: { 'per_page': d.itemsPerPage,
              'semester': this.semester,
          'search': this.searchItem,
          'program': this.program,
          'college': this.college },
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
          .post("/api/v1/clearance-requests/approve", 
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
          .get("/api/v1/clearance-requests/disapprove",
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
<style>
  html body div#app div div#inspire.v-application.v-application--is-ltr.theme--light div.v-application--wrap main.v-main.grey.lighten-5 div.v-main__wrap div.container.container--fluid div.container div.row.container-fluid.ml-2.mt-0 div.col-lg-9.col-12 div.v-card.v-sheet.theme--light div.v-data-table.px-6.pb-6.mt-2.v-data-table--has-bottom.theme--light div.v-data-table__wrapper table tbody tr td{
    font-size: 12px;
    padding-bottom: 0px;
    margin-bottom: 0px;
  }
</style>