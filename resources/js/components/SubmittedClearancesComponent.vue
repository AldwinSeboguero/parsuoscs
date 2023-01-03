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
              <label class="black--text font-weight-medium mt-2" for="">Semester</label>
           
                         
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
                          <label class="black--text font-weight-medium mt-2" for="">College</label>

                            <v-autocomplete
                              v-model="college"
                              :items="colleges"
                              
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
                                    Search College 
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
                              v-model="program"
                              :items="programs"
                              
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
                          <label class="black--text font-weight-medium mt-2" for="">Search</label>

                                <v-text-field 
                                      append-icon="mdi-magnify"
                                      placeholder="ID No. or Name"
                                      class="mb-0 pb-0 mt-2 pt-0"
                                      v-model="search"
                                      outlined
                                      flat
                                      clearable
                                      dense
                                      hide-details
                                    ></v-text-field>
                  
                   
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
                
                  <v-dialog v-model="exportExcelDialog" width="390">
                    <template v-slot:activator="{ on, attrs }">
                  
                      <v-btn
                        dark
                        v-bind="attrs"
                        small
                        v-on="on"
                        icon
                        class="float-right success white--text mr-2"
                      >
                        <v-icon small>mdi-file-excel</v-icon>
                      </v-btn>
                    </template>
                    <v-card>
                     <v-card-title class="align-center ma-2 mt-0 pa-2 rounded white--red elevation-0 black--text text--lighten-1 subtitle-1 text-uppercase"  >
                      <v-icon left class="grey--text text--darken-1">mdi-download</v-icon> Export Submiited Clearance

                      <v-spacer/>
                        <v-btn elevation-0 color="black" small  @click=" exportExcelDialog = false" icon> <v-icon>mdi-close</v-icon></v-btn>
                      </v-card-title>
                      <v-card-text >
                        <i class="caption mb-2">* Please use filters to specify exported data.</i>
                      <downloadexcel
                        class            = "btn"
                        :fetch           = "fetchData"
                        :fields          = "json_fields"
                        type="csv"
                        :name="excelFilename"
                        :before-generate = "startDownload"
                        :before-finish   = "finishDownload">
                      <v-list-item
                      link
                      dark
                      class="success"
                      active-class="orange--text text--accent-4 font-weight-bold "
                      >
                    
                        
                      <v-icon small class="mr-2 ">mdi-file-excel</v-icon>
                      <span class="font-semibold ">CSV</span>
                    
                      </v-list-item>
                      </downloadexcel>
                      <br/>
                      <span>{{ downloadProgress }}%</span>

                      <v-progress-linear v-if="downloadLoading" :value="downloadProgress" color="primary" class="" >
                      </v-progress-linear>
                        
                      </v-card-text>
                   
                    </v-card>
                  </v-dialog>

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
                    <v-card-title class="align-center ma-2 mt-0 pa-2 rounded white--red elevation-0 black--text text--lighten-1 subtitle-1 text-uppercase"  >
                      <v-icon left class="grey--text text--darken-1">mdi-download</v-icon> Export Multiple PDF

                      <v-spacer/>
                        <v-btn elevation-0 color="black" small  @click=" uploadDialog = false" icon> <v-icon>mdi-close</v-icon></v-btn>
                      </v-card-title>
                      <v-card-text >
                        <i class="caption mb-2">* Please use filters to specify exported data.</i>
                     
                      <v-list-item
                      link
                      dark
                      class="red"
                      active-class="orange--text text--accent-4 font-weight-bold "
                      :loading="downloadMultipleLoading"
                      @click="generateMultiplePDF"
                      >
                    
                        
                      <v-icon small class="mr-2 ">mdi-download</v-icon>
                      <span class="font-semibold ">Multiple PDF</span>
                    
                      </v-list-item>
                      <br/>
                      <span>{{ downloadProgress }}%</span>

                      <v-progress-linear v-if="downloadMultipleLoading" :value="downloadProgress" color="primary" class="" >
                      </v-progress-linear>
                        
                      </v-card-text>
                   
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
        <v-btn @click="generatePDF(item)" class="elevation-0 error lighten-1" icon  small dark  text 
                 >
                  <v-icon small>mdi-file-download</v-icon>
                  </v-btn>
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
import downloadexcel from "vue-json-excel";
import PDFMerger from 'pdf-merger-js';

import JSZip from 'jszip';
    import { saveAs } from 'file-saver';
export default {
  components: {
            downloadexcel

        },
  data: () => ({
    excelData: [],
    downloadLoading: false,
    downloadMultipleLoading: false,

    exportExcelDialog: false,
    json_fields: {
                  'Student ID' : 'student_number',
                  'Name': 'name',
                  'College/Campus': 'college',
                  'Program' : 'program',
                  'Purpose' : 'purpose',
                  'Date Submitted' : 'datesubmitted',
                  'Clearance ID' : 'clearance_id',
                },
    excelFilename:'',
    valid: true,
    dialog: false,
    loading: false,
    downloadProgress:0,
    offSet:true,
    totalPageDownloadExcel: 0,
    currentPageDownloadExcel: 0,
    uploadDialog:false,
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
     program: '',
     college: '',
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
    axios.get(`/api/v1/programs`,{
              params: { 
                'signatoryProgram': true,
              },
            }).then((response) => {
              this.programs = response.data.programs;
            });
     axios.get(`/api/v1/campuses`).then((response) => {
          this.campuses = response.data.campuses;
    });
     axios.get(`/api/v1/semesters`).then((response) => {
          this.semesters = response.data.semesters;
    });
     axios.get(`/api/v1/designations`).then((response) => {
          this.designations = response.data.designations;
    });
     axios.get(`/api/v1/students`).then((response) => {
          this.students = response.data.students.data;
    });
     axios.get(`/api/v1/purposes`).then((response) => {
          this.purposes = response.data.purposes;
    });
   axios.get(`/api/v1/colleges`,{
              params: { 
                'signatoryCollege': true,
              },
            }).then((response) => {
          this.colleges = response.data.colleges;
    });
  },

  watch: {
    'college': debounce(function (val) {
          console.log(val)
          const { page, itemsPerPage } = this.options;
          let pageNumber = page;
           axios
           .get(`/api/v1/submittedclearances?page=` + pageNumber, {
              params: { 
                'per_page': itemsPerPage,
                'semester': this.semester,
                'program': this.program,
                'college': val,
                'purpose': this.purpose,
                'designation': this.designation,
                'student': this.student,
               },
            })
            .then((response) => {
              // this.semester = response.data.semester.semester;
              this.page = response.data.submittedclearances.current_page;
            this.submittedclearances = response.data.submittedclearances.data;
            this.totalsubmittedclearances = response.data.submittedclearances.total;
            this.numberOfPages = response.data.submittedclearances.total_pages;
              this.loading = false;
            });
        }, 300),
        'program': debounce(function (val) {
          console.log(val)
          const { page, itemsPerPage } = this.options;
          let pageNumber = page;
           axios
           .get(`/api/v1/submittedclearances?page=` + pageNumber, {
              params: { 
                'per_page': itemsPerPage,
                'semester' : this.semester,
                'program' : val,
                'college' : this.college,
                'search' : this.search,
               },
            })
            .then((response) => {
              // this.semester = response.data.semester.semester;
              this.page = response.data.submittedclearances.current_page;
            this.submittedclearances = response.data.submittedclearances.data;
            this.totalsubmittedclearances = response.data.submittedclearances.total;
            this.numberOfPages = response.data.submittedclearances.total_pages;
              this.loading = false;
            });
        }, 300),
    'semester': debounce(function (val) {
          console.log(val)
          const { page, itemsPerPage } = this.options;
          let pageNumber = page;
          axios
        .get(`/api/v1/submittedclearances?page=` + pageNumber, {
            params: { 
          'per_page': itemsPerPage,
          'semester' : val,
          'program' : this.program,
          'college' : this.college,
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
          'program' : this.program,
          'college' : this.college,
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
    // this.initialize();
  },

  methods: {
    clean($val) {
          if($val){$val = $val.replace(/ +(?= )/g, "");
          $val = $val.replace(/[&\/\\#,+()$~%.'":*?<>{}]/g, "_"); // Replaces all spaces with hyphens.
          $val = $val.replace(/ +(?= )/g, "_");
          
          return $val;
          }
          // Removes special chars.
        },
    clearForms(){
      this.semester = null;
      this.search = '';
    },
    async generateMultiplePDF(){
      this.downloadMultipleLoading = true;

              var zip = new JSZip();
              var merger = new PDFMerger();
              var option = {};
              var i;

              await  axios
              .get(`/api/v1/submittedclearances?page=` + 1, {
                params: { 
                  'per_page': 1,
                  'semester': this.semester,
                  'program': this.program,
                  'college': this.college,
                  'purpose': this.purpose,
                  'designation': this.designation,
                  'student': this.student,
                  },
              })
              .then(async (response) => {

                // this.semester = response.data.semester.semester;
              this.currentPageDownloadExcel = response.data.submittedclearances.current_page;
              this.totalPageDownloadExcel = response.data.submittedclearances.total_pages;
              
              for (let i = 1; i <= this.totalPageDownloadExcel; i++) {
                var fileURL ='' ;

              await axios
                  .get(`/api/v1/submittedclearances?page=` + i, {
                    params: { 
                      'per_page': 1,
                      'semester': this.semester,
                      'program': this.program,
                      'college': this.college,
                      'purpose': this.purpose,
                      'designation': this.designation,
                      'student': this.student,
                      },
                  })
                  .then(async (response) => {
                    var sc;
                    for(sc of response.data.submittedclearances.data){

                      await axios.get('/api/v1/active-clearance/signatory/pdf',{responseType: 'blob'
                      ,params: { 'clearance_id': sc.clearance_id }

                      }).then((response) => {
                        fileURL  = new Blob([response.data], {type: 'application/pdf'});
                        });
                    // fileURL  = new Blob([response.data], {type: 'application/pdf'});
                  //  await merger.add(fileURL);
                  
                  await zip.file(this.clean(sc.name)+".pdf", fileURL);
                  
                  this.downloadProgress = (i/this.totalPageDownloadExcel*100).toFixed(2);

                 

                }
                  });
              

              }
              // this.loading = false
              // console.log(this.excelData)
              this.excelFilename = response.data.file_name;
              });

               this.downloadMultipleLoading = false;

              // await merger.save("Submitted Clearance"+'_'+((Math.floor(Date.now() / 1))));
              await zip.generateAsync({type:"blob"})
                    .then(function(content) {
                    // see FileSaver.js
                    saveAs(content, "Submitted Clearances"+'_'+((Math.floor(Date.now() / 1)))+".zip");
                    });

 
            
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
          'semester' : this.semester,
          'program' : this.program,
          'college' : this.college,
          'search' : this.search },
        })
        .then((response) => {
          this.loading = false;  
            this.page = response.data.submittedclearances.current_page;
            this.submittedclearances = response.data.submittedclearances.data;
            this.totalsubmittedclearances = response.data.submittedclearances.total;
            this.numberOfPages = response.data.submittedclearances.total_pages;
        });
    },
    startDownload(){
            this.downloadLoading = true;
          },
    finishDownload(){
      this.downloadLoading = false;
    },
    async fetchData() {
      this.downloadLoading = true
      this.downloadProgress =0;
      this.excelData = [];

       await  axios
        .get(`/api/v1/submittedclearances?page=` + 1, {
          params: { 
            'per_page': 100,
            'semester': this.semester,
            'program': this.program,
            'college': this.college,
            'purpose': this.purpose,
            'designation': this.designation,
            'student': this.student,
            },
        })
        .then(async (response) => {

          // this.semester = response.data.semester.semester;
        this.currentPageDownloadExcel = response.data.submittedclearances.current_page;
        this.totalPageDownloadExcel = response.data.submittedclearances.total_pages;
        
        for (let i = 1; i <= this.totalPageDownloadExcel; i++) {
         await axios
            .get(`/api/v1/submittedclearances?page=` + i, {
              params: { 
                'per_page': 100,
                'semester': this.semester,
                'program': this.program,
                'college': this.college,
                'purpose': this.purpose,
                'designation': this.designation,
                'student': this.student,
                },
            })
            .then((response) => {

              this.excelData =[].concat(this.excelData,response.data.submittedclearances.data);
              
            });
            this.downloadProgress = (i/this.totalPageDownloadExcel*100).toFixed(2);
         

        }
        // this.loading = false
        console.log(this.excelData)
        this.excelFilename = response.data.file_name;
        });
       
      // simulate download with setInterval
      // const interval = setInterval(() => {
      //   this.downloadProgress += 1
      //   if (this.downloadProgress >= 100) {
      //     clearInterval(interval)
      //     this.loading = false

      //   }
      // }, 1000)
      return this.excelData;

    }
   
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