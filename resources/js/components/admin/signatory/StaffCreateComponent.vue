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
                <v-checkbox v-model="forms.isForAllInCollege" small class="mt-0 pt-0 mb-0 pb-2" hide-details>
                  <template v-slot:label>
                    <span class="caption font-italic">
                      Programs for selected college
                    </span>
                  </template>
                </v-checkbox>
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
                  :disabled="isProgramDisable"
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
                <label class="black--text font-weight-medium" for="">Order</label>
                <v-text-field
                      v-model="forms.order"
                      label=""
                      auto-grow
                      outlined
                      dense
                      placeholder=""
                      type="number"
                      clearable
                      hide-details
                      class="mb-2 text-field-text-size"

                    />
                    <v-divider />
                  
                   
              </v-form>
              
            </v-card-text>
            <v-card-actions>
                   <v-spacer/>
                    <v-btn
                      type="submit" 
                      @click="submit"
                      :loading="formActionLoading"
                      dark
                      success
                      small
                      class="elevation-0 success"
                    >
                    {{ isEditMode ? 'Save Changes' : 'Save' }}
                    </v-btn>
                    <v-btn
                      dark
                      outlined
                      small
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
       
           <Table :forms="forms" ref="childComponent" @childEvent="setEditedItem"></Table>
           <!-- <v-textarea v-model="query" label="Results" rows="1" auto-grow disabled></v-textarea> -->
           <v-dialog v-model="showErrorDialog" persistent width="400">
            <v-card class="pt-1 pb-4">
            <v-card-title class="align-center ma-2 mt-0 pa-2 rounded white--red elevation-0 red--text" >

              <v-icon left class="red--text text--lighten-1">mdi-lightbulb-alert</v-icon> Error Message
              <v-spacer/>
                <v-btn elevation-0 color="black" small  @click="showErrorDialog = false" icon> <v-icon>mdi-close</v-icon></v-btn>
              </v-card-title>
              <v-card-text >
                {{errorMessage}}<br/>
                
              </v-card-text>
            </v-card>
          </v-dialog>
      </v-col>
    </v-row>
  </div>
</template>

<script>
    import Table from "../../../pages/components/CreateSignatoryTable";
    import debounce from "lodash/debounce";

export default {
  name: 'ParsuoscsV2StaffComponent',
  components:{
    Table,
  },
  data() {
    return {
      valid: true,
      formName:'Create Signatory',
      formIcon : 'mdi-account-plus',
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
      search: null,
    page: 0,
    totalStaffs: 0,
    numberOfPages: 0,
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
    purposes: {}
    };
    
  },

  watch: {
    'forms.college': debounce( function (val) {

        
          axios.get(`/api/v1/programs`,{
                    params: { 
                      'college': val,
                      'campus' :  this.forms.campus_id
                    },
                  }).then((response) => {
                this.programs = response.data.programs;
          });
        }, 300),
    'forms.isForAllInCollege': debounce( function (val) {
      if(this.forms.isForAllInCollege){
        this.isProgramDisable = true;
        this.forms.program = '';
      }
      else{
        this.isProgramDisable = false;
      }
    }, 300),

      },
   mounted() {
     axios.get(`/api/v1/programs`).then((response) => {
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
     axios.get(`/api/v1/signatories`).then((response) => {
          this.signatories = response.data.signatories;
    });
     axios.get(`/api/v1/purposes`).then((response) => {
          this.purposes = response.data.purposes;
    });
   axios.get(`/api/v1/colleges`,{
              params: { 
                'campus': this.forms.campus_id,
              },
            }).then((response) => {
          this.colleges = response.data.colleges;
    });

  },

  methods: {
     setEditedItem(val){
      // this.editedForms = val;
      this.isEditMode = true;
      this.forms = Object.assign({},val);
      this.formName = 'Update Signatory';
      this.formIcon = 'mdi-account-edit';
    },
    clearForms(){
      // this.editedForms = Object.assign({}, this.defaultForms);

      this.isEditMode = false;
      this.forms = Object.assign({}, this.defaultForms);

      // this.$refs.childComponent.editRowReset();
      this.formName = 'Create Signatory';
      this.formIcon = 'mdi-file-plus';

    },
    async submit() {
      this.formActionLoading = true;
        const data = Object.assign({}, this.forms);
        // console.log(this.forms);
      if (this.isEditMode) {
        
          // save changes
          try {
            const response = await axios.put('/api/v1/signatories/'+ this.forms.id, data);
            this.editedForms = Object.assign({},this.defaultForms);
            
            // this.forms = Object.assign({},this.defaultForms);
            this.formName = 'Create Signatory';
            this.formIcon = 'mdi-account-plus';
            this.isEditMode = false;
            this.formActionLoading = false;
            this.$refs.childComponent.nextPage();
            // this.$refs.childComponent.editRowReset();
            // handle success
          } catch (error) {
            this.formActionLoading = false;

            // handle error
          }
        } else {

      try {
            const response = await axios.post('/api/v1/signatories', this.forms);
            this.$refs.childComponent.nextPage();
            this.$refs.childComponent.editRowReset();

            // this.forms = Object.assign({},this.defaultForms);
            this.formActionLoading = false;
            this.formName = 'Create Signatory';
            this.formIcon = 'mdi-account-plus';
            this.$refs.childComponent.filter(this.forms);

            // handle success
          } catch (error) {
            this.formActionLoading = false;
            console.log(error.response.data.message);
            this.showErrorDialog = true;
            this.errorMessage = error.response.data.message;
            // handle error
          }
      }
    }
  },
};
</script>

<style>
.text-field-text-size {
  font-size: 14px;
}
</style>
