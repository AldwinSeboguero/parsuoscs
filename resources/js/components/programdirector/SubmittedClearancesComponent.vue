<template>
  <v-container fluid class="ma-0 pa-0">
  <v-row >
      <v-col cols="12" lg="5" class="mt-2">
       <Breadcrumbs class="mb-4"/>

      </v-col>
    </v-row>  
    <v-row class="container-fluid ml-2 mt-0">
    <v-col cols="12" lg="3">
        <v-card class=" rounded-medium pt-1 pb-2 px-2"
            elevation="2">
            
            <v-card-title class="align-end pl-4 pa-2 mt-2 mb-3 rounded white--text elevation-1" style=" margin-left: -16px; margin-right: -16px; max-height: 50px; background: linear-gradient(to right, #0d47a1, #0d47a1, #1A237E);">
              <span class="font-semibold overline"><v-icon dark left >mdi-filter-variant</v-icon>Filters</span>

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
                            v-model="semester"
                            :items="semesters"
                            chips
                            clearable
                            item-text="semester"
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
                                
                                <span class="text-truncate text-uppercase" v-text="item.semester"></span>
                              </v-chip>
                            </template>
                            <template v-slot:item="{ item }">
                              
                              <v-list-item-content>
                                <v-list-item-title class="text-uppercase" v-text="item.semester"></v-list-item-title> 
                              </v-list-item-content> 
                            </template>
                          </v-autocomplete>
                          <label class="black--text font-weight-medium mt-2" for="">Search</label>

                                <v-text-field 
                                      append-icon="mdi-magnify"
                                      label="Search"
                                      class="mb-0 pb-0 mt-2 pt-0"
                                      v-model="search"
                                      @input="searchIt"
                                      outlined
                                      flat
                                      
                                      dense
                                      hide-details
                                    ></v-text-field>
                    <v-divider />
                  
                   
              </v-form>
              
            </v-card-text>
            <v-card-actions>
                   
                    <v-btn
                      dark
                      outlined
                      small
                      block
                      @click="clearForms"
                      class="elevation-0"
                      color="primary"
                    >
                      Clear
                    </v-btn>
            </v-card-actions>
        </v-card>
      </v-col>
      <v-col cols="12" lg="9">
      <v-card class=" rounded-medium pt-1  px-2"
            elevation="2">
  
            <v-card-title class="align-end pl-4 pa-2 mt-2 mb-3 rounded white--text elevation-1" style=" margin-left: -16px; margin-right: -16px; max-height: 50px; background: linear-gradient(to right, #0d47a1, #0d47a1, #1A237E);">
               <span> 
            Submitted Clearances
            </span>
                 
                  <v-spacer></v-spacer>
                  <span></span>
                 

                  <v-dialog v-model="uploadDialog" width="390">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        dark
                        v-bind="attrs"
                        small
                        v-on="on"
                        icon
                        class="float-right info white--text"
                      >
                        <v-icon small>mdi-download-multiple</v-icon>
                      </v-btn>
                    </template>
                    <v-card>
                      
                    </v-card>
                  </v-dialog>
                
          </v-card-title>
          </v-card>
    
     <v-data-table
        item-key="id"
        class="px-6 pb-6  mt-4"
        :loading="loading"
        loading-text="Loading... Please wait"
        :headers="headers"
        :page="page" 
        :pageCount="numberOfPages"
        :items="submittedclearances"
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
      
   
       <template v-slot:item.datesubmitted="{ item }" >
        <v-chip text-color="white" color="success" small >
           
            {{ item.datesubmitted }}
        </v-chip>
         </template>
      <template v-slot:item.actions="{ item }">
          <template>
        <!-- <v-btn class="ma-2" color="error" text depressed small @click="generatePDF(item)"
          ><v-icon>mdi-file-pdf</v-icon></v-btn
        >   -->
        <v-btn :loading="downloadLoading" @click="generatePDF(item)" class="elevation-0 error lighten-1 ml-2"  small dark  
                 >
                  <v-icon x-small>mdi-file-pdf</v-icon>
                  Download</v-btn>
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
      </v-col>
      </v-row>
  </v-container>
</template>
<script>
import debounce from "lodash/debounce";

export default {
  data: () => ({
    valid: true,
    dialog: false,
    loading: false,
    snackbar: false,
    selected: [],
    text: "",
    
    search: '',
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
     },

     semester:'', 
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

  mounted() {
    axios.get(`/api/v1/semesters`).then((response) => {
          this.semesters = response.data.semesters;
    });
  },

  watch: {
    'semester': debounce(function (val) {
          console.log(val)
          const { page, itemsPerPage } = this.options;
          let pageNumber = page;
          axios
        .get(`/api/v1/submittedclearances?page=` + pageNumber, {
            params: { 
          'per_page': itemsPerPage,
          'semester' : val,
          'search' : this.search,
         },
        })
        .then((response) => {
          this.loading = false;  
            this.page = response.data.submittedclearances.current_page;
            this.submittedclearances = response.data.submittedclearances.data;
            this.totalsubmittedclearances = response.data.submittedclearances.total;
            this.numberOfPages = response.data.submittedclearances.total_pages;
        });
        }, 300),

        'search': debounce(function (val) {
          console.log(val)
          const { page, itemsPerPage } = this.options;
          let pageNumber = page;
          axios
        .get(`/api/v1/submittedclearances?page=` + pageNumber, {
            params: { 
          'per_page': itemsPerPage,
          'semester' : this.semester,
          'search' : val, },
        })
        .then((response) => {
          this.loading = false;  
            this.page = response.data.submittedclearances.current_page;
            this.submittedclearances = response.data.submittedclearances.data;
            this.totalsubmittedclearances = response.data.submittedclearances.total;
            this.numberOfPages = response.data.submittedclearances.total_pages;
        });
        }, 300),

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
    clearForms(){
      this.semester = null;
      this.search = '';
    },
    generatePDF(item) { 
      axios.get('/api/v1/active-clearance/signatory/pdf',{responseType: 'blob'
            ,params: { 'clearance_id': item.clearance_id }

            }).then((response) => {
              item.name = item.name.replace(/[&\/\\#,+()$~%.'":*?<>{}]/g, "_"); // Replaces all spaces with hyphens.
              item.name = item.name.replace(/ +(?= )/g, "");
              
                var fileURL = window.URL.createObjectURL(new Blob([response.data], {type: 'application/pdf'}));
                var fileLink = document.createElement('a');
                fileLink.href = fileURL;
                fileLink.setAttribute('download', item.name+'-'+item.clearance_id+'.pdf');
                document.body.appendChild(fileLink);
                fileLink.click();
                // this.downloadLoading = false;
              });
    },
     readDataFromAPI() {
      this.loading = true;
      const { page, itemsPerPage } = this.options;
      let pageNumber = page;
      axios
        .get(`/api/v1/submittedclearances?page=` + pageNumber, {
            params: { 
          'per_page': itemsPerPage,
          'semester' : this.semester, },
        })
        .then((response) => {
          this.loading = false;  
            this.page = response.data.submittedclearances.current_page;
            this.submittedclearances = response.data.submittedclearances.data;
            this.totalsubmittedclearances = response.data.submittedclearances.total;
            this.numberOfPages = response.data.submittedclearances.total_pages;
        });
    },


    // searchIt(d) {
      
    //   if (d.length > 2) {
    //     const { page, itemsPerPage } = this.options;
    //      let pageNumber = page;
    //     axios
    //       .get(`/api/v1/submittedclearances/${d}?page=` + pageNumber, {
    //       params: { 'per_page': itemsPerPage,
    //       'id' : d,
    //       'semester_id' : this.semester_id,
    //       },
    //     })
    //       .then((res) => {
    //         this.loading = false;   
    //         this.page = res.data.submittedclearances.current_page;
    //         this.submittedclearances = res.data.submittedclearances; 
    //         this.totalsubmittedclearances = res.data.submittedclearances.total;
    //         this.numberOfPages = res.data.submittedclearances.total_pages;
    //       })
    //       .catch((err) => {
    //         console.error(err);
    //       });
    //   }
    //   if (d.length <= 0) {
    //      const { page, itemsPerPage } = this.options;
    //      let pageNumber = page;
    //     axios
    //       .get(`/api/v1/submittedclearances?page=` + pageNumber, {
    //         params: { 'per_page': itemsPerPage,
    //       'semester_id' : this.semester_id, },
    //       })
    //       .then((res) => {
    //         this.loading = false;  
    //         this.page = res.data.submittedclearances.current_page;
    //         this.submittedclearances = res.data.submittedclearances;
    //         this.totalsubmittedclearances = res.data.submittedclearances.total;
    //         this.numberOfPages = res.data.submittedclearances.total_pages;
    //       })
    //       .catch((err) => {
    //         console.error(err);
    //       });
    //   }
    // },  
   
  },
};
</script>
<style>
 html body div#app div div#inspire.v-application.v-application--is-ltr.theme--light div.v-application--wrap main.v-main.grey.lighten-5 div.v-main__wrap div.container.container--fluid div.container.ma-0.pa-0.container--fluid div.row.container-fluid.ml-2.mt-0 div.col-lg-9.col-12 div.v-data-table.px-6.pb-6.mt-4.v-data-table--has-bottom.theme--light div.v-data-table__wrapper table tbody tr td{
 font-size: 12px;
    padding-bottom: 0px;
    margin-bottom: 0px;
  }
</style>