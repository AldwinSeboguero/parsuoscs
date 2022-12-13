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
                v-model="valid" 
                ref="form"
              >
              <label class="black--text font-weight-medium" for="">Program Code</label>
              <v-text-field
                      v-model="forms.code"
                      label=""
                      auto-grow
                      outlined
                      dense
                      v-on="on"
                      clearable
                      hide-details
                      class="mb-2 text-field-text-size"

                    />
                <label class="black--text font-weight-medium mt-2" for="">Description</label>

                <v-text-field
                      v-model="forms.description"
                      label=""
                      
                      outlined
                      dense
                      v-on="on"
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
                      type="submit" 
                      dark
                      outlined
                      small
                      @click="clearForms"
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
  import Table from "../../pages/components/ProgramTable";
  import debounce from "lodash/debounce";

export default {
  name: 'ParsuoscsV2StaffComponent',
  components:{
    Table,
  },
  data() {
    return {
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
      code: '',
      description:'',
      campus_id:'',
      college_id:'',
      schedules: [],
    },
    editedForms: {
      id: '',
      code: '',
      description:'',
      campus_id:'',
      college_id:'',
      schedules: [],
    },
    defaultForms: {
      id: '',
      code: '',
      description:'',
      campus_id:'',
      college_id:'',
      schedules: [],
    },
    };
  },
  watch:{
    'forms.code': debounce( function (val) {
      const data = {
          code: this.clean(this.forms.code),
          description: this.clean(this.forms.description),
          college_id: this.forms.college_id,
          campus_id: this.forms.campus_id,

        };
      this.$refs.childComponent.filter(data);

    }, 300),
    'forms.description': debounce( function (val) {
      const data = {
          code: this.clean(this.forms.code),
          description: this.clean(this.forms.description),
          college_id: this.forms.college_id,
          campus_id: this.forms.campus_id,

        };
        
      this.$refs.childComponent.filter(data);

    }, 300),
    'forms.college_id': debounce( function (val) {
      const data = {
          code: this.clean(this.forms.code),
          description: this.clean(this.forms.description),
          college_id: this.forms.college_id,
          campus_id: this.forms.campus_id,

        };
      this.$refs.childComponent.filter(data);

    }, 300),

    'forms.campus_id': debounce( function (val) {
      const data = {
          code: this.clean(this.forms.code),
          description: this.clean(this.forms.description),
          college_id: this.forms.college_id,
          campus_id: this.forms.campus_id,

        };
        axios.get(`/api/v1/colleges`,{
                  params: { 
                    'campus': val,
                  },
                }).then((response) => {
              this.colleges = response.data.colleges;
        });
      this.$refs.childComponent.filter(data);

    }, 300),
  },
  async mounted() {
    await axios.get(`/api/v1/programs`).then((response) => {
              this.programs = response.data.programs;
            });
    await axios.get(`/api/v1/campuses`).then((response) => {
          this.campuses = response.data.campuses;
    });
    await axios.get(`/api/v1/adminprograms`).then((response) => {
          this.semesters = response.data.semesters;
    });
    await axios.get(`/api/v1/designations`).then((response) => {
          this.designations = response.data.designations;
    });
    await axios.get(`/api/v1/signatories`).then((response) => {
          this.signatories = response.data.signatories.data;
    });
    await axios.get(`/api/v1/purposes`).then((response) => {
          this.purposes = response.data.purposes;
    });
    await axios.get(`/api/v1/colleges`,{
              params: { 
                'campus': this.forms.campus_id,
              },
            }).then((response) => {
          this.colleges = response.data.colleges;
    });
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
       
    async setEditedItem(val){
      this.editedForms = val;
      this.isEditMode = true;
      this.forms = this.editedForms;
      this.formName = 'Update';
      this.formIcon = 'mdi-file-edit';
    },
    async clearForms(){
      this.editedForms = this.defaultForms;
      this.isEditMode = false;
      this.forms = this.defaultForms;
      this.$refs.childComponent.editRowReset();
      this.formName = 'Add';
      this.formIcon = 'mdi-file-plus';

    },
    async submit() {
      if (this.$refs.form.validate()) {
        this.formActionLoading = true;
        const data = {
          code: this.forms.code,
          description: this.forms.description,
          college_id: this.forms.college_id,
          campus_id: this.forms.campus_id,

        };
        if (this.isEditMode) {
          // save changes
          try {
            const response = await axios.put('/api/v1/adminprograms/'+ this.editedForms.id, data);
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
            const response = await axios.post('/api/v1/adminprograms', data);
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