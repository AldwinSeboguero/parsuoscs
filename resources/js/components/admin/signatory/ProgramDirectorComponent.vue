<template>
  <v-sheet>
   <v-card>
    <v-container>
     <v-data-table
        item-key="id"
        class="elevation-0"
        :loading="loading"
        loading-text="Loading... Please wait"
        :headers="headers"
        :page="page + 1"
        :pageCount="numberOfPages"
        :items="programdirectors.data"
        :options.sync="options"
        :server-items-length="totalProgramDirectors"
        :items-per-page="10" 
        show-select 
        :footer-props="{
          itemsPerPageOptions: [5, 10, 15],
          itemsPerPageText: 'Program Director Per Page',
          'show-current-page': true,
          'show-first-last-page': true,
        }"
      >
      <template v-slot:top>
        <v-text-field 
            append-icon="mdi-magnify"
            label="Search"
            v-model="searchItem"
            @input="searchIt"
          ></v-text-field>
        <v-toolbar flat color="white">
          <div class="overline text-h6">
              Program Director List
              <span class="font-italic subtitle-2"
                >(2nd Semester A/Y 2020-2021 )</span
              >
            </div>
          <v-spacer></v-spacer>

          <v-dialog v-model="dialog" scrollable max-width="500px">
           <template v-slot:activator="{ on, attrs }">
              <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
                New ProgramDirector
              </v-btn>
            </template>
            
      <v-card>
         <v-card-title class="primary white--text">
                <v-icon class="white--text" style="padding-right: 8px">{{
                  formIcon
                }}</v-icon>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>
        <v-divider></v-divider>
        <v-card-text style="height: 500px;">
          <v-form
                  v-model="valid"
                  method="post"
                  v-on:submit.stop.prevent="save"
                > 
                    <v-container v-if="editedIndex!=-1">
                      <v-row>
                        <v-col cols="12" sm="12">
                          <v-text-field
                            v-model="editedItem.programdirector_number"
                            label="ProgramDirector Number"
                            :rules="[rules.required, rules.min]"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="12">
                          <v-text-field
                            v-model="editedItem.name"
                            label="Full Name"
                            :rules="[rules.required, rules.min]"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="12" style="margin: 0">
                          <v-select
                            v-model="editedItem.campus_id"
                            :items="campuses"
                            item-text="name"
                            label="Select Campus"
                            item-value="id" 
                            color="primary"
                            @change="campusListener"
                            :rules="[rules.required]"
                          ></v-select>
                        </v-col>
                        <v-col cols="12" sm="12" style="margin: 0">
                          <v-select
                            v-model="editedItem.program_id"
                            :items="programs"
                            label="Select Program"
                             item-value="id"
                          item-text="name"
                           @change="programListener"
                            color="primary"
                            :rules="[rules.required]"
                          ></v-select>
                        </v-col>
                        <v-col cols="12" sm="12" style="margin: 0">
                          <v-select
                            v-model="editedItem.section_id"
                            :items="sections"
                            label="Select Section"
                            item-value="id"
                            item-text="name"
                            color="primary"
                            :rules="[rules.required]"
                          ></v-select>
                        </v-col>
                        <v-col cols="12" sm="12" style="margin: 0">
                          <v-select
                            v-model="editedItem.year"
                            :items="years"
                            label="Select Year Level"
                             item-value="id"
                          item-text="name"
                            color="primary"
                            :rules="[rules.required]"
                          ></v-select>
                        </v-col>
                      </v-row>
                    </v-container> 
 
                    <v-container v-if="editedIndex==-1">
                      <v-row>
                        <v-col cols="12" sm="12">
                          <v-text-field
                            v-model="editedItem.programdirector_number"
                            label="ProgramDirector Number"
                            :rules="[rules.required, rules.min]"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="12">
                          <v-text-field
                            v-model="editedItem.name"
                            label="Full Name"
                            :rules="[rules.required, rules.min]"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="12" style="margin: 0">
                          <v-select
                            v-model="editedItem.campus"
                            :items="campuses"
                            label="Select Campus"
                             item-value="id"
                              item-text="name"
                              @change="campusListenerNew"
                            color="primary"
                            :rules="[rules.required]"
                          ></v-select>
                        </v-col>
                        <v-col cols="12" sm="12" style="margin: 0">
                          <v-select
                            v-model="editedItem.program"
                            :items="programs"
                            label="Select Program"
                             item-value="id"
                          item-text="name"
                          @change="programListenerNew"
                            color="primary"
                            :rules="[rules.required]"
                          ></v-select>
                        </v-col>
                        <v-col cols="12" sm="12" style="margin: 0">
                          <v-select
                            v-model="editedItem.section"
                            :items="sections"
                            label="Select Section"
                             item-value="id"
                          item-text="name"
                            color="primary"
                            :rules="[rules.required]"
                          ></v-select>
                        </v-col>
                        <v-col cols="12" sm="12" style="margin: 0">
                          <v-select
                            v-model="editedItem.year"
                            :items="years"
                            label="Select Year Level"
                             item-value="id"
                          item-text="name"
                            color="primary"
                            :rules="[rules.required]"
                          ></v-select>
                        </v-col>
                      </v-row>
                    </v-container> 

                  
                </v-form>
        </v-card-text>
        <v-divider></v-divider>
       <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="close">
                      Cancel
                    </v-btn>
                    <v-btn
                      color="blue darken-1"
                      text
                      type="submit"
                      :disabled="!valid"
                      @click.prevent="save"
                    >
                      Save
                    </v-btn>
     </v-card-actions>
      </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:item.id="{ item }">
      <td>{{programdirectors.data.indexOf(item)+1}}</td> 
    </template>
       
      <template v-slot:item.actions="{ item }">
        <v-icon small class="mr-2" @click="editItem(item)"> mdi-pencil </v-icon>
        <v-icon small class="mr-2" @click="deleteItem(item)">
          mdi-delete-forever
        </v-icon>
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
    </v-container>
    </v-card>
  </v-sheet>
</template>
<script>
export default {
  data: () => ({
    valid: true,
    dialog: false,
    loading: false,
    snackbar: false,
    selected: [],
    text: "",
    success: "",
    error: "", 
    snackbarColor:"",
    searchItem: '',
    headers: [
      {
        text: "No",
        align: "left",
        value: "id",
      },
      { text: "Name", value: "name" }, 
      { text: "Program", value: "program" }, 
      { text: "Campus", value: "campus" }, 
      { text: "Semester", value: "semester" }, 
      { text: "Action", value: "actions" },
    ],
    page: 0,
    totalProgramDirectors: 0,
    numberOfPages: 0,
    options: {},
    programdirectors: {},
    campuses: {},
    sections: {},
    programs: {},
    years: [1,2,3,4,5], 
    rules: {
      required: (v) => !!v || "This Field is Required",
      min: (v) => v.length >= 5 || "Minimum 5 Characters Required",
      validEmail: (v) => /.+@.+\..+/.test(v) || "Email must be valid",
    },
    editedIndex: -1,
    editedItem: {
      id: "",
      programdirector_number: "",
      name: "",
      campus: "",
      section: "",
      program: "",
      campus_id: "",
      section_id: "",
      program_id: "",
      code:"",
      year: "",
      created_at: "",
    },
    defaultItem: {
      id: "",
      programdirector_number: "",
      name: "",
      campus: "",
      section: "",
      program: "",
      campus_id: "",
      section_id: "",
      program_id: "",
      code:"",
      year: "",
      created_at: "",
    },
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Item" : "Edit Item";
    },
    formIcon() {
      return this.editedIndex === -1 ? "mdi-plus" : "mdi-pen";
    }, 
  },

  watch: {
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
    this.initialize();
  },

  methods: {
     readDataFromAPI() {
      this.loading = true;
      const { page, itemsPerPage } = this.options;
      let pageNumber = page;
      axios
        .get(`/api/v1/programdirectors?page=` + pageNumber, {
          params: { 'per_page': itemsPerPage },
        })
        .then((response) => {
          //Then injecting the result to datatable parameters.
          this.loading = false;
         this.programdirectors = response.data.programdirectors;
          this.campuses = response.data.campuses;
          this.programs = response.data.programs;
          this.sections = response.data.sections;
          this.totalProgramDirectors = response.data.programdirectors.total;
          this.numberOfPages = response.data.programdirectors.last_page;
        });
    },

    searchIt(d) {
      if (d.length > 2) {
        const { page, itemsPerPage } = this.options;
        let pageNumber = page;
        axios
          .get(`/api/v1/programdirectors/${d}?page=` + pageNumber, {
          params: { 'per_page': itemsPerPage },
        })
          .then((res) => {
            this.loading = false;  
            this.programdirectors = res.data.programdirectors;
            console.log(this.programdirectors);
            this.totalProgramDirectors = res.data.programdirectors.total;
            this.numberOfPages = res.data.programdirectors.last_page;
          })
          .catch((err) => {
            console.error(err);
          });
      }
      if (d.length <= 0) {
        const { page, itemsPerPage } = this.options;
        let pageNumber = page;
        axios
          .get(`/api/v1/programdirectors?page=`+ pageNumber, {
          params: { 'per_page': itemsPerPage },
          })
          .then((res) => {
            this.loading = false;  
            this.programdirectors = res.data.programdirectors;
            this.totalProgramDirectors = res.data.programdirectors.total;
            this.numberOfPages = res.data.programdirectors.last_page;
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
      this.editedIndex = this.programdirectors.data.indexOf(item);
      this.editedItem = Object.assign({}, item);
      console.log(this.editedItem);
      this.dialog = true;
    },
    campusListener(){
 
     axios
          .post("/api/v1/campusListener",{'campus_id': this.editedItem.campus_id})
          .then((res) => { 
            this.programs = res.data.programs;
            this.sections = ''; 
          })
          .catch((err) => {
            console.error(err);
          });
    },
    programListener(){ 
     axios
          .post("/api/v1/programListener",{'program_id': this.editedItem.program_id})
          .then((res) => { 
            this.sections = res.data.sections;  
          })
          .catch((err) => {
            console.error(err);
          });
    },
    campusListenerNew(){
 
     axios
          .post("/api/v1/campusListener",{'campus_id': this.editedItem.campus})
          .then((res) => { 
            this.programs = res.data.programs;
            this.sections = ''; 
          })
          .catch((err) => {
            console.error(err);
          });
    },
    programListenerNew(){
 
     axios
          .post("/api/v1/programListener",{'program_id': this.editedItem.program})
          .then((res) => { 
            this.sections = res.data.sections;  
          })
          .catch((err) => {
            console.error(err);
          });
    },
    deleteItem(item) {
      const index = this.programdirectors.data.indexOf(item);
      let decide = confirm("Are you sure you want to delete this item?");
      if (decide) {
        axios
          .delete("/api/v1/programdirectors/" + item.id)
          .then((res) => {
            this.text = "Record Deleted Successfully!"; 
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            this.programdirectors.data.splice(index, 1);
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Deleting Record";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;

          });
      }
    },

    close() {
      this.dialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    }, 
    save() {
      console.log(this.editedItem);
      if (this.editedIndex > -1) {
        const index = this.editedIndex;
        axios
          .put("/api/v1/programdirectors/" + this.editedItem.id, this.editedItem)
          .then((res) => {
            this.text = "Record Updated Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            Object.assign(this.programdirectors.data[index], res.data.programdirector);
            console.log(this.editedItem);
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Updating Record";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;
          });
      } else {
        axios
          .post("/api/v1/programdirectors", this.editedItem)
          .then((res) => {
            this.text = "Record Added Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            // this.programdirectors.data.push(res.data.programdirector); 
            this.programdirectors = res.data.programdirectors
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