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
                <span class="font-weight-bold overline"><v-icon dark left small>{{formIcon}}</v-icon>{{formName}}</span>
                <v-spacer/>
                <v-dialog v-model="uploadDialog" width="390">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        dark
                        v-bind="attrs"
                        small
                        v-on="on"
            
                        icon
                        class="float-right info white--text mr-2 mb-1"
                      >
                        <v-icon small>mdi-cloud-upload-outline</v-icon>
                      </v-btn>
                    </template>
                    <v-card>
                      <v-card-title class="text-sm-h6">
                        Upload File
                      </v-card-title>
                      <v-card-text>
                        <v-file-input
                          label="Select a XLSX file..."
                          v-model="ffile"
                          color="deep-purple accent-4"
                          counter
                          accept=".xlsx"
                          id="input-file-import"
                          name="file_import"
                          ref="import_file"
                          size="5"
                          @change="onFileChange"
                          placeholder="Select your files"
                          prepend-icon="mdi-paperclip"
                          :class="{ ' is-invalid': error.message }"
                          :error-messages="error.message"
                          outlined
                          :show-size="1000"
                        >
                          <template v-slot:selection="{ index, text }">
                            <v-chip
                              v-if="index < 2"
                              color="deep-purple accent-4"
                              dark
                              label
                              small
                            >
                              {{ text }}
                            </v-chip>

                            <span
                              v-else-if="index === 2"
                              class="
                                text-overline
                                grey--text
                                text--darken-3
                                mx-2
                              "
                            >
                              +{{ ffile.length - 2 }} File(s)
                            </span>
                          </template>
                        </v-file-input>
                      </v-card-text>

                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn text  :loading="uploadLoading" class="primary mb-4" @click="proceedAction">
                          Submit
                        </v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
            </v-card-title>

            <v-card-text>
            <v-form 
                v-model="valid" 
                ref="form"
              >
              <label class="black--text font-weight-medium" for="">Student ID</label>
              <v-text-field
                      v-model="forms.student_id"
                      label=""
                      auto-grow
                      outlined
                      dense
                      placeholder="201311592"
                      
                      clearable
                      hide-details
                      class="mb-2 text-field-text-size"

                    />
              <label class="black--text font-weight-medium" for="">Name</label>
              <v-text-field
                      v-model="forms.name"
                      label=""
                      auto-grow
                      outlined
                      dense
                      placeholder="Lastname, Firstname MI."
                      
                      clearable
                      hide-details
                      class="mb-2 text-field-text-size"

                    />
                <label class="black--text font-weight-medium mt-2" for="">Email</label>

                <v-text-field
                      v-model="forms.email"
                      label=""
                      type="email"
                      placeholder="example@parsu.edu.ph"
                      outlined
                      dense
                      
                      clearable
                      hide-details
                      class="mb-2 text-field-text-size"

                    />
              <label class="black--text font-weight-medium mt-2" for="">Campus</label>
              <v-autocomplete
                  v-model="forms.campus_id"
                  :items="campuses"
                  :search-input.sync="search"
                  chips
                  clearable
                  item-text="name"
                  item-value="id"
                  item-key="id" 
                  outlined
                  class="mb-2"
                  dense
                  hide-details
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
                  v-model="forms.college_id"
                  :items="colleges"
                  :search-input.sync="search"
                  chips
                  clearable
                  item-text="name"
                  item-value="id"
                  item-key="id" 
                  outlined
                  class="mb-2"
                  dense
                  hide-details
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
                  v-model="forms.program_id"
                  :items="programs"
                  :search-input.sync="search"
                  chips
                  clearable
                  item-text="name"
                  item-value="id"
                  item-key="id" 
                  outlined
                  class="mb-2"
                  dense
                  hide-details
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
                    <v-icon left>{{formActionIcon}}</v-icon> {{ isEditMode ? 'Save Changes' : 'Save' }}
                    </v-btn>
                    <v-btn
                      dark
                      outlined
                      small
                      @click.prevent="clearForms"
                      class="elevation-0"
                      color="primary"
                    >
                      Cancel
                    </v-btn>
              
            </v-card-actions>
        </v-card>
      </v-col>
      <v-col cols="12" lg="9">
       
           <Table :forms="forms"  ref="childComponent" @childEvent="setEditedItem"></Table>
           <!-- <v-textarea v-model="query" label="Results" rows="1" auto-grow disabled></v-textarea> -->

      </v-col>
    </v-row>
  </div>
</template>

<script>
  import Table from "../../pages/components/StudentTable";
  import debounce from "lodash/debounce";

export default {
  name: 'ParsuoscsV2StaffComponent',
  components:{
    Table,
  },
  data() {
    return {
    uploadDialog: "",
    loadingDialog:false,
    uploadLoading: false,
    ffile: null,
    error: {},
    import_file: "",
    valid: true,

      formName:'Add',
      formIcon : 'mdi-file-plus',
      formAction : 'Save',
      formActionIcon : 'mdi-content-save',
      formActionColor : 'success',
      formActionLoading: false,
      isEditMode: false,
      startDate:'',
      endDate:'',
      menuOpen: false,
      schedules: [],
      query:' ',
      offSet: true,
      search: null,
    page: 0,
    totalStaffs: 0,
    numberOfPages: 0,
    options: {},
    staffs: {},
    campuses: {},
    colleges: {},

    programs:{},
    purposes:{},
    designations: {},
    semesters: {},
    signatories:{},
    forms: {
      id: '',
      name: '',
      student_id:'',
      campus_id:'',
      college_id:'',
      email: '',
      program_id:'',
    },
    editedForms: {
      id: '',
      name: '',
      student_id:'',
      campus_id:'',
      college_id:'',
      email: '',
      program_id:'',
    },
    defaultForms: {
      id: '',
      name: '',
      student_id:'',
      campus_id:'',
      college_id:'',
      email: '',
      program_id:'',
    },
    };
  },
  watch:{
    
    'forms.student_id': debounce( function (val) {
      const data = {
        student_id: this.clean(this.forms.student_id),
          name: this.clean(this.forms.name),
          email: this.forms.email,
          college_id: this.forms.college_id,
          campus_id: this.forms.campus_id,
          program_id: this.forms.program_id,

        };
      if(!this.isEditMode){

      this.$refs.childComponent.filter(data);
      }
    }, 300),
    'forms.name': debounce( function (val) {

      const data = {
        student_id: this.clean(this.forms.student_id),
          name: this.clean(this.forms.name),
          email: this.forms.email,
          college_id: this.forms.college_id,
          campus_id: this.forms.campus_id,
          program_id: this.forms.program_id,
        };
      if(!this.isEditMode){
        
      this.$refs.childComponent.filter(data);
      }
    }, 300),
    'forms.email': debounce( function (val) {

      const data = {
        student_id: this.clean(this.forms.student_id),
          name: this.clean(this.forms.name),
          email: this.forms.email,
          college_id: this.forms.college_id,
          campus_id: this.forms.campus_id,
          program_id: this.forms.program_id,


        };
      if(!this.isEditMode){

      this.$refs.childComponent.filter(data);
      }
      }, 300),
    'forms.college_id': debounce( function (val) {

      const data = {
        student_id: this.clean(this.forms.student_id),
          name: this.clean(this.forms.name),
          email: this.forms.email,
          college_id: this.forms.college_id,
          campus_id: this.forms.campus_id,
          program_id: this.forms.program_id,


        };
        axios.get(`/api/v1/programs`,{
                  params: { 
                    'college': val,
                    'campus' :  this.forms.campus_id
                  },
                }).then((response) => {
              this.programs = response.data.programs;
        });
      if(!this.isEditMode){

      this.$refs.childComponent.filter(data);
      }
    }, 300),

    'forms.campus_id': debounce( function (val) {

      const data = {
        student_id: this.clean(this.forms.student_id),
          name: this.clean(this.forms.name),
          email: this.forms.email,
          college_id: this.forms.college_id,
          campus_id: this.forms.campus_id,
          program_id: this.forms.program_id,

        };
        axios.get(`/api/v1/colleges`,{
                  params: { 
                    'campus': val,
                    'byPass': true,
                  },
                }).then((response) => {
              this.colleges = response.data.colleges;
              this.programs = [];
        });
      if(!this.isEditMode){

      this.$refs.childComponent.filter(data);
      }
    }, 300),
    'forms.program_id': debounce( function (val) {

      const data = {
        student_id: this.clean(this.forms.student_id),
          name: this.clean(this.forms.name),
          email: this.forms.email,
          college_id: this.forms.college_id,
          campus_id: this.forms.campus_id,
          program_id: this.forms.program_id,

        };
      
      if(!this.isEditMode){
       
      this.$refs.childComponent.filter(data);
      }
    }, 300),
  },
   mounted() {
    // await axios.get(`/api/v1/programs`).then((response) => {
    //           this.programs = response.data.programs;
    //         });
     axios.get(`/api/v1/campuses`).then((response) => {
          this.campuses = response.data.campuses;
    });
     axios.get(`/api/v1/adminstudents`).then((response) => {
          this.semesters = response.data.semesters;
    });
     axios.get(`/api/v1/designations`).then((response) => {
          this.designations = response.data.designations;
    });
     axios.get(`/api/v1/signatories`).then((response) => {
          this.signatories = response.data.signatories.data;
    });
     axios.get(`/api/v1/purposes`).then((response) => {
          this.purposes = response.data.purposes;
    });
    // await axios.get(`/api/v1/colleges`,{
    //           params: { 
    //             'campus': this.forms.campus_id,
    //           },
    //         }).then((response) => {
    //       this.colleges = response.data.colleges;
    // });
  },

  methods: {
    //upload student accounts
    onFileChange(e) {
      console.log(e.target.files[0]);
      this.import_file = this.$refs.import_file;
    },

    async proceedAction() {
      this.uploadLoading = true;
      let formData = new FormData();
      formData.append("import_file", this.ffile);

      await axios
        .post("api/v1/students/import", formData, {
          headers: { "content-type": "multipart/form-data" },
        })
        .then((res) => {
       
            this.uploadDialog = false;
            this.uploadLoading = false;
            const data = {
              student_id: this.clean(this.forms.student_id),
                name: this.clean(this.forms.name),
                email: this.forms.email,
                college_id: this.forms.college_id,
                campus_id: this.forms.campus_id,
                program_id: this.forms.program_id,

              };

            this.$refs.childComponent.filter(data);
        
        })
        .catch((error) => {
          // code here when an upload is not valid
          this.uploading = false;
          this.uploadLoading = false;
          this.error = error.response.data;
          console.log("check error: ", this.error);
        });
    },
    //end of upload
    clean($val) {
          if($val){$val = $val.replace(/ +(?= )/g, "");
          $val = $val.replace(/[&\/\\#,+()$~%.'":*?<>{}]/g, " "); // Replaces all spaces with hyphens.
          $val = $val.replace(/ +(?= )/g, "");
          
          return $val;
          }
          // Removes special chars.
        },
       
     setEditedItem(val){
      console.log(val)
      this.editedForms = val;
      this.isEditMode = true;
      this.forms =  { ...this.editedForms };
      this.forms.college_id = this.colleges.find(signatory => signatory.id === this.editedForms.college_id);
      this.forms.campus_id = this.campuses.find(signatory => signatory.id === this.editedForms.campus_id);
      this.forms.program_id = this.programs.find(signatory => signatory.id === this.editedForms.program_id);

      this.formName = 'Update';
      this.formIcon = 'mdi-file-edit';
    },
     clearForms(){
      this.editedForms = Object.assign({}, this.defaultForms);

      this.isEditMode = false;
      this.forms = Object.assign({}, this.defaultForms);

      this.$refs.childComponent.editRowReset();
      this.formName = 'Add';
      this.formIcon = 'mdi-file-plus';

    },
    async submit() {
      if (this.$refs.form.validate()) {
        this.formActionLoading = true;
        const data = {
          student_id: this.clean(this.forms.student_id),
          name: this.clean(this.forms.name),
          email: this.forms.email,
          college_id: this.forms.college_id,
          campus_id: this.forms.campus_id,
          program_id: this.forms.program_id,

        };
        if (this.isEditMode) {
          // save changes
          try {
            const response = await axios.put('/api/v1/adminstudents/'+ this.editedForms.id, data);
            this.editedForms = this.defaultForms;
            
            this.forms = this.defaultForms;
            this.formName = 'Add';
            this.formIcon = 'mdi-file-plus';
            this.isEditMode = false;
            this.formActionLoading = false;
            this.$refs.childComponent.nextPage();
            this.$refs.childComponent.editRowReset();
            // handle success
          } catch (error) {
            this.formActionLoading = false;

            // handle error
          }
        } else {
          // add item
          try {
            const response = await axios.post('/api/v1/adminstudents', data);
            this.$refs.childComponent.nextPage();
            this.$refs.childComponent.editRowReset();

            this.forms = this.defaultForms;
            this.formActionLoading = false;
            this.formName = 'Add';
            this.formIcon = 'mdi-file-plus';
            // handle success
          } catch (error) {
            this.formActionLoading = false;

            // handle error
          }
        }
      }
      
    },
  },
};
</script>

<style>
.text-field-text-size {
  font-size: 14px;
}
</style>