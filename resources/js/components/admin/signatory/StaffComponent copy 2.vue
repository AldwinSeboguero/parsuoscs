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
            <v-card-actions>
            <div class="text-center pb-4">
                    <v-btn
                      type="submit" 
                      @click.prevent="save" 
                      
                      dark
                      success
                      small
                      min-width="40%"
                      class="elevation-0 success"
                    >
                    <v-icon left>mdi-magnify</v-icon>  Search
                    </v-btn>
                    <v-btn
                      type="submit" 
                      @click.prevent="copy" 
                      
                      dark
                      small
                      min-width="40%"
                      @click="$router.push('signatories/copy-prev')"
                      class="elevation-0 default"
                    >
                     <v-icon left>mdi-content-copy</v-icon> Copy Prev
                    </v-btn>
              </div>
            </v-card-actions>
        </v-card>
      </v-col>
      <v-col cols="12" lg="9">
       
           <Table :forms="forms" ></Table>
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
      query:' ',
      offSet: true,
      search: null,
    page: 0,
    totalStaffs: 0,
    numberOfPages: 0,
    options: {},
    staffs: {},
    campuses: {},
    programs:{},
    designations: {},
    semesters: {},
    signatories:{},
    forms: {
      semester: '',
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
          this.signatories = response.data.signatories.data;
    });
  },

  methods: {
    
  },
};
</script>
