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
                <span class="font-semibold overline"><v-icon dark left>mdi-filter-variant</v-icon>Filters</span>
            </v-card-title>

            <v-card-text>
            <v-form 
                method="post"
                lazy-validation
                v-on:submit.stop.prevent="save"
                ref="entryForm"
              >
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
              <label class="black--text font-weight-medium mt-2" for="">Campus</label>

                <v-autocomplete
                  v-model="forms.campus"
                  :items="campuses"
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
                <label class="black--text font-weight-medium mt-2" for="">Program</label>

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

                    <v-divider />
                  
                   
              </v-form>
              
            </v-card-text>
            <div v-if="isEdit===true">
              <v-row class="ml-4 mr-10 mb-1 mt-2 pb-4 ml-3">
                <v-col cols="6" class="ma-0 pa-0 pr-1">
                <v-btn block type="submit" 
                      class="elevation-0 success"
                      dark
                      width="100%"
                      @click="submit"
                      :loading="formActionLoading"
                      small> <v-icon left>mdi-content-save-edit</v-icon>Save </v-btn>
                </v-col>
                <v-col cols="6" class="ma-0 pa-0 pr-1">
                <v-btn  
                      
                      block
                      elevation="0"
                      outlined
                      color="primary"
                      small
                      width="130%"
                      @click="cancelEdit"
                      > <v-icon left>mdi-cancel</v-icon>Cancel</v-btn>
                </v-col>
              </v-row>
            </div>
            <v-card-actions v-else>
            <v-row class=" mr-10 mb-1 mt-0 pt-0 pb-4 ml-3">
                <v-col cols="5" class=" pa-0 mr-6">
                <v-btn
                  
                      @click="$router.push('signatories/create-update')"
                      dark
                      success
                      small
                      min-width="100%"
                      class="elevation-0 success"
                    >
                    <v-icon left>mdi-account-plus</v-icon>  Create
                    </v-btn>
                </v-col>
                <v-col cols="5" class="ma-0 pa-0 pr-1">
                <v-btn
                    
                      
                      dark
                      small
                      min-width="100%"
                      @click="$router.push('signatories/copy-prev')"
                      class="elevation-0 default"
                    >
                     <v-icon left>mdi-content-copy</v-icon> Copy Prev
                    </v-btn>
                </v-col>
              </v-row>
           
              
            </v-card-actions>
        </v-card>
      </v-col>
      <v-col cols="12" lg="9">
       
           <Table :forms="forms" :isEditMode="isEditMode" @childEvent="setEditedItem"  ref="childComponent"  ></Table>
           <!-- <v-textarea v-model="query" label="Results" rows="1" auto-grow disabled></v-textarea> -->
    
      </v-col>
    </v-row>

  </div>
</template>

<script>
  import Table from "../../../pages/components/Table";
  
export default {
  name: 'ParsuoscsV2StaffComponent',
  components:{
    Table,
  },
  data() {
    return {
      formActionLoading:false,
      isEditMode:'',
      query:' ',
      offSet: true,
      search: null,
    page: 0,
    isEdit:false,
    totalStaffs: 0,
    numberOfPages: 0,
    options: {},
    staffs: {},
    campuses: {},
    programs:{},
    purposes:{},
    designations: {},
    semesters: {},
    signatories:{},
    forms: {
      
      id: "", 
      semester: '',
      campus:'',
      program: '',
      designation: '',
      signatory: '',
      campus_id: "",
      designee_id: "",
      semester_id: "", 
      user_id:'',
      new_semester_id:'',
    },
    

    editedItem: {
      semester: '',
      campus:'',
      program: '',
      designation: '',
      signatory: '',
      id: "", 
      campus_id: "",
      designee_id: "",
      semester_id: "", 
      user_id:'',
      new_semester_id:'',
    },
    defaultItem: {
      semester: '',
      campus:'',
      program: '',
      designation: '',
      signatory: '',
      id: "", 
      campus_id: "",
      designee_id: "",
      semester_id: "", 
      user_id:'',
      new_semester_id:'',
    },
    };
    
  },

  async mounted() {
    await axios.get(`/api/v1/programs`).then((response) => {
              this.programs = response.data.programs;
            });
    await axios.get(`/api/v1/campuses`).then((response) => {
          this.campuses = response.data.campuses;
    });
    await axios.get(`/api/v1/semesters`).then((response) => {
          this.semesters = response.data.semesters;
    });
    await axios.get(`/api/v1/designations`).then((response) => {
          this.designations = response.data.designations;
    });
    await axios.get(`/api/v1/signatories`).then((response) => {
          this.signatories = response.data.signatories;
    });
    await axios.get(`/api/v1/purposes`).then((response) => {
          this.purposes = response.data.purposes;
    });
  },

  methods: {
    async cancelEdit(){
      this.isEdit = false;
      this.editedForms = this.defaultForms;
      // this.isEditMode = false;
      this.forms = { ...this.defaultForms };
      this.$refs.childComponent.editRowReset();
    },
     setEditedItem(val, isEditedMode){
      this.editedForms = val;
      this.isEditMode = isEditedMode;

      
      this.forms =  { ...this.editedForms };
      // console.log("Edit Mode : "+this.isEditMode)
      this.isEdit = true;
      // this.forms.signatory.id = this.editedForms.id;
      this.forms.signatory = this.signatories.find(signatory => signatory.id === this.editedForms.user_id);
      this.forms.designation = this.designations.find(signatory => signatory.id === this.editedForms.designee_id);
      this.forms.campus = this.campuses.find(signatory => signatory.id === this.editedForms.campus_id);
      this.forms.program = this.programs.find(signatory => signatory.id === this.editedForms.program_id);
      this.forms.purpose = this.purposes.find(signatory => signatory.id === this.editedForms.purpose_id);
      this.forms.semester = this.semesters.find(signatory => signatory.id === this.editedForms.semester_id);

    },
    async clearForms(){
      this.editedForms = this.defaultForms;
      this.isEditMode = false;
      this.forms = this.defaultForms;
      this.$refs.childComponent.editRowReset();

    },
    async submit() {
      console.log("Submit Click")
     
        this.formActionLoading = true;
        const data = {
          // code: this.forms.code,
          description: this.forms.description,
          schedules: this.forms.schedules,

        };
        
          try {
            const response = await axios.put('/api/v1/staffs/'+ this.editedForms.id, data);
            this.editedForms = this.defaultForms;
            
            this.forms = this.defaultForms;

            this.isEditMode = false;
            this.formActionLoading = false;
            this.$refs.childComponent.nextPage();
            this.$refs.childComponent.editRowReset();
            // handle success
          } catch (error) {
            this.formActionLoading = false;

            // handle error
          }
       
      
    },
  },
};
</script>
