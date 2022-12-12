<template>
  <div>
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
                <span class="font-semibold overline"><v-icon dark left>mdi-account-arrow-right</v-icon>Generate</span>
            </v-card-title>

            <v-card-text>
            <v-form 
                method="post"
                lazy-validation
                v-on:submit.stop.prevent="save"
                ref="entryForm"
              >
              
                <label class="black--text font-weight-medium mt-2" for="">Previous Semester</label>

                <v-autocomplete
                  v-model="forms.semester_prev"
                  :items="semesters_prev"
                  :search-input.sync="search"
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
              <label class="black--text font-weight-medium" for="">New Semester</label>
               
                 <v-autocomplete
                  v-model="forms.semester_next"
                  :items="semesters_next"
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
                       
                      
                    <span class="text-truncate text-uppercase" v-text="item.semester"></span>
                    </v-chip>
                  </template>
                  <template v-slot:item="{ item }">
                    
                    <v-list-item-content>
                      <v-list-item-title class="text-uppercase" v-text="item.semester"></v-list-item-title> 
                    </v-list-item-content> 
                  </template>
                </v-autocomplete>

                <label class="black--text font-weight-medium " for="">Purpose</label>
               
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

                    <v-divider />
                  
                   
              </v-form>
              
            </v-card-text>
            <v-card-actions>
                    <v-btn
                      type="submit" 
                      @click="copyPrev" 
                      :loading="generateLoading"
                      dark
                      small
                      class="elevation-0 light-green darken-3"
                      block
                    >
                     <v-icon left>mdi-content-copy</v-icon> Generate
                    </v-btn>
            </v-card-actions>
        </v-card>
      </v-col>
      <v-col cols="12" lg="12">
           <v-textarea v-model="result" label="Results" rows="9" row-height="5" readonly scrollable></v-textarea>

      </v-col>
    </v-row>
  </div>
</template>

<script>
  // import Table from "../../../pages/components/Table";
export default {
  name: 'ParsuoscsV2StaffComponent',
  components:{
    // Table,
  },
  data() {
    return {
      result:' ',
      generateLoading: false,
      offSet: true,
      search: null,
    page: 0,
    totalStaffs: 0,
    numberOfPages: 0,
    campus: {},
    options: {},
    staffs: {},
    campuses: {},
    programs:{},
    designations: {},
    semesters_prev: {},
    semesters_next: {},

    signatories:{},
    forms: {
      semester_prev: '',
      semester_next: '',
      purpose: '',
      campus:'',
      program: '',
      designation: '',
      signatory: '',
    },
    

    editedItem: {
      id: "", 
      campus_id: "",
      designee_id: "",
      semester_id: "", 
      user_id:'',
      new_semester_id:'',
    },
    defaultItem: {
       id: "", 
      campus_id: "",
      designee_id: "",
      semester_id: "", 
      user_id:'',
       new_semester_id:'',
    },
    purposes: {}
    };
    
  },

  async mounted() {
    await axios.get(`/api/v1/programs`).then((response) => {
              this.programs = response.data.programs;
            });
    await axios.get(`/api/v1/campuses`).then((response) => {
          this.campuses = response.data.campuses;
    });
    await axios.get(`/api/v1/getprev`).then((response) => {
          this.semesters_prev = response.data.semesters;
    });
    await axios.get(`/api/v1/getnext`).then((response) => {
          this.semesters_next = response.data.semesters;
    });
    await axios.get(`/api/v1/purposes`).then((response) => {
          this.purposes = response.data.purposes;
    });
    await axios.get(`/api/v1/designations`).then((response) => {
          this.designations = response.data.designations;
    });
    await axios.get(`/api/v1/signatories`).then((response) => {
          this.signatories = response.data.signatories.data;
    });

  },

  methods: {

    async copyPrev(){
      this.generateLoading = true;
      this.result = '';
      if(this.forms.purpose == 1){
        var count = 1;

        for(this.campus of this.campuses){
          console.log(this.campus)
        await axios.get(`/api/v1/getPrevDeanEnrollment`,{
              params: { 
                'prev': this.forms.semester_prev,
                'next': this.forms.semester_next,
                'campus' : this.campus.id,

              }
            }).then(async (response) => {
        var signatory;
        for(signatory of response.data.signatories){
          await axios.get(`/api/v1/copyPrevDeanEnrollment`,{
              params: { 
                'prev': this.forms.semester_prev,
                'next': this.forms.semester_next,
                'signatory': signatory.id,

              }
              }).then((response) => {
            this.result = (count++)+'. '+response.data.result+'\n'+this.result+'\n';
            });
          }
        });
        this.result = 'Copying Dean from previous semester has been completed!!\n'+this.result+'\n';

        await axios.get(`/api/v1/getPrevCashierEnrollment`,{
                params: { 
                  'prev': this.forms.semester_prev,
                  'next': this.forms.semester_next,
                  'campus' : this.campus.id,

                }
              }).then(async (response) => {
          var signatory;
          // var count = 1;
          for(signatory of response.data.signatories){
            await axios.get(`/api/v1/copyPrevCashierEnrollment`,{
                params: { 
                  'prev': this.forms.semester_prev,
                  'next': this.forms.semester_next,
                  'signatory': signatory.id,

                }
              }).then((response) => {
            this.result = (count++)+'. '+response.data.result+'\n'+this.result+'\n';
            });
          }
        });
        this.result = 'Copying Cashier from previous semester has been completed!!\n'+this.result+'\n';

        await axios.get(`/api/v1/getPrevLibraryEnrollment`,{
                params: { 
                  'prev': this.forms.semester_prev,
                  'next': this.forms.semester_next,
                  'campus' : this.campus.id,

                }
              }).then(async (response) => {
          var signatory;
          // var count = 1;
          for(signatory of response.data.signatories){
            await axios.get(`/api/v1/copyPrevLibraryEnrollment`,{
                params: { 
                  'prev': this.forms.semester_prev,
                  'next': this.forms.semester_next,
                  'signatory': signatory.id,

                }
              }).then((response) => {
            this.result = (count++)+'. '+response.data.result+'\n'+this.result+'\n';
            });
          }
        });
        this.result = 'Copying Library from previous semester has been completed!!\n'+this.result+'\n';

        await axios.get(`/api/v1/getPrevOSASEnrollment`,{
                params: { 
                  'prev': this.forms.semester_prev,
                  'next': this.forms.semester_next,
                  'campus' : this.campus.id,

                }
              }).then(async (response) => {
          var signatory;
          // var count = 1;
          for(signatory of response.data.signatories){
            await axios.get(`/api/v1/copyPrevOSASEnrollment`,{
                params: { 
                  'prev': this.forms.semester_prev,
                  'next': this.forms.semester_next,
                  'signatory': signatory.id,

                }
              }).then((response) => {
            this.result = (count++)+'. '+response.data.result+'\n'+this.result+'\n';
            });
          }
        });
        this.result = 'Copying OSAS from previous semester has been completed!!\n'+this.result+'\n';
      }
      }
      else if(this.forms.purpose == 2){
        // var campus;
        for(this.campus of this.campuses){
          // console.log(this.campus);
        var count = 1,total=0;

            await axios.get(`/api/v1/getPrevGraduation`,{
                    params: { 
                      'prev': this.forms.semester_prev,
                      'next': this.forms.semester_next,
                      'campus' : this.campus.id,
                    }
                  }).then(async (response) => {
                    total = response.data.signatories.last_page;
            });
            for(var x=1; x<=total;x++){
            await axios.get(`/api/v1/getPrevGraduation?page=` + x,{
                    params: { 
                      'prev': this.forms.semester_prev,
                      'next': this.forms.semester_next,
                      'campus' : this.campus.id,
                    }
                  }).then(async (response) => {
              var signatory;
              for(signatory of response.data.signatories.data){
                await axios.get(`/api/v1/copyPrevGraduation`,{
                    params: { 
                      'prev': this.forms.semester_prev,
                      'next': this.forms.semester_next,
                      
                      'signatory': signatory.id,

                    }
                  }).then((response) => {
                this.result = (count++)+'. '+response.data.result+'\n'+this.result+'\n';
                });
              }
            });
            
              
          }
        }
         
        this.result = 'Copying Graduation Signatory from previous semester has been completed!!\n'+this.result+'\n';
      }
      else if(this.forms.purpose == 3){
        for(this.campus of this.campuses){
        var count = 1,total=0;
        await axios.get(`/api/v1/getPrevRequestCredentials`,{
                params: { 
                  'prev': this.forms.semester_prev,
                  'next': this.forms.semester_next,
                  'campus' : this.campus.id,

                }
              }).then(async (response) => {
                total = response.data.signatories.last_page;
        });
        for(var x=1; x<=total;x++){
        await axios.get(`/api/v1/getPrevRequestCredentials?page=` + x,{
                params: { 
                  'prev': this.forms.semester_prev,
                  'next': this.forms.semester_next,
                  'campus' : this.campus.id,

                }
              }).then(async (response) => {
          var signatory;
          for(signatory of response.data.signatories.data){
            await axios.get(`/api/v1/copyPrevRequestCredentials`,{
                params: { 
                  'prev': this.forms.semester_prev,
                  'next': this.forms.semester_next,
                  'signatory': signatory.id,

                }
              }).then((response) => {
            this.result = (count++)+'. '+response.data.result+'\n'+this.result+'\n';
            });
          }
        });
        
          
      }
    }
      this.result = 'Copying Request Credentials Signatory from previous semester has been completed!!\n'+this.result+'\n';

      }
      
      
   
      this.generateLoading = false;
    }
    
  },
};
</script>
