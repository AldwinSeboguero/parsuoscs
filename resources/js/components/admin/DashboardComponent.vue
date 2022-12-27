<template>
   <v-container fluid class="ma-0 pa-0">
    <v-row >
      <v-col cols="12" lg="5" class="mt-2">
       <Breadcrumbs class="mb-4"/>

      </v-col>
    </v-row> 
    <v-row class="container-fluid ml-2 mt-0">

    <p class="headline font-weight-bold  mt-5 ml-5 lg:mb-0"> Welcome, Administrator! </p>
    </v-row>
    <v-row class="container ml-0 mt-0">

      
        <v-col cols='12' lg="3">
        <label class="black--text font-weight-medium caption" for="">Semester</label>

            <v-autocomplete
              v-model="forms.semester"
              :items="semesters"
              
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
        </v-col>
        
        <v-col cols='12' lg="3">

        <label class="black--text font-weight-medium mt-2 caption" for="">Purpose</label>

          <v-autocomplete
            v-model="forms.purpose"
            :items="purposes"
            
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
                
                <span class="text-truncate" v-text="item.purpose"></span>
              </v-chip>
            </template>
            <template v-slot:item="{ item }">
              
              <v-list-item-content>
                <v-list-item-title v-text="item.purpose"></v-list-item-title> 
              </v-list-item-content> 
            </template>
          </v-autocomplete>
          </v-col>

          <v-col cols='12' lg="3">

          <label class="black--text font-weight-medium mt-2 caption" for="">College</label>

            <v-autocomplete
              v-model="forms.college"
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
            </v-col>
    </v-row> 
  </v-container>
</template>

<script>
export default {
  name: 'ParsuoscsV2DashboardComponent',

  data() {
    return {
      forms: {
      semester: '',
      purpose:'',
      college:'',
    },
    purposes:{},
    semesters:{},
    colleges:{},
    };
  },

  mounted() {
    axios.get(`/api/v1/semesters`).then((response) => {
          this.semesters = response.data.semesters;
    });
     axios.get(`/api/v1/purposes`).then((response) => {
          this.purposes = response.data.purposes;
    });
    axios.get(`/api/v1/colleges`,{
              params: { 
                // 'signatoryCollege': true,
              },
            }).then((response) => {
          this.colleges = response.data.colleges;
    });
  },

  methods: {
    
  },
};
</script>

<style lang="stylus" scoped>

</style>