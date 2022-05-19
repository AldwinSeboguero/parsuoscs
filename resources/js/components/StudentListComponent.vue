<template>
  <v-sheet>
    <v-card elevation="0">
      <v-container class="grey lighten-5" fluid>
        <v-row wrap>
          <v-col cols="12" lg="4">
            <v-card>
              <v-card-text style="padding-bottom: 10">
                <h1 class="title desplay-2 black--text text--accent3">
                  <v-icon class="ma-1 mb-2">mdi-account-plus-outline</v-icon>
                  Add Student
                  <v-dialog v-model="exportDialog" width="390">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        dark
                        v-bind="attrs"
                        small
                        v-on="on"
                        icon
                        class="float-right success white--text ml-2"
                      >
                        <v-icon small>mdi-file-excel</v-icon>
                      </v-btn>
                    </template>
                    <v-card>
                      <v-card-title class="text-lg-caption">
                        Export Students Activation Code
                      </v-card-title>
                      <v-card-text>
                         <v-form
                      method="post"
                      lazy-validation
                      v-on:submit.stop.prevent="save"
                      ref="entryForm"
                    >
                      
                      <v-select
                        v-model="editedItem.export_campus_id"
                        :items="export_campuses"
                        item-text="name"
                        label="Select Campus"
                        clearable
                        item-value="id"
                        item-key="id"
                        color="primary"
                        chips
                        @change="exportcampusListener($event)"
                      >
                        <template
                          v-slot:selection="{ attr, on, item, selected }"
                        >
                          <v-chip
                            v-bind="attr"
                            :input-value="selected"
                            color="purple"
                            class="white--text"
                            v-on="on"
                          >
                            <span v-text="item.name"></span>
                          </v-chip>
                        </template>
                      </v-select>

                      <v-autocomplete
                        v-model="editedItem.export_program_id"
                        :items="export_programs"
                        :loading="isLoading"
                        :search-input.sync="search"
                        chips
                        clearable
                        item-text="name"
                        item-value="id"
                        item-key="id"
                        label="Search Program..." 
                      >
                        <template v-slot:no-data>
                          <v-list-item>
                            <v-list-item-title>
                              Search Program
                            </v-list-item-title>
                          </v-list-item>
                        </template>
                        <template
                          v-slot:selection="{ attr, on, item, selected }"
                        >
                          <v-chip
                            v-bind="attr"
                            :input-value="selected"
                            color="purple"
                            class="white--text"
                            v-on="on"
                          >
                            <span v-text="item.name"></span>
                          </v-chip>
                        </template>
                        <template v-slot:item="{ item }">
                          <v-list-item-content>
                            <v-list-item-title
                              v-text="item.name"
                            ></v-list-item-title>
                          </v-list-item-content>
                        </template>
                      </v-autocomplete>
                      

                      <v-divider />

                      
                    </v-form>
                      </v-card-text>
                      <v-card-actions>
                        <v-spacer></v-spacer>
                         <v-btn
                          block
                          color="blue darken-1"
                          shaped
                          elevation="0"
                          dark
                          type="submit"
                          @click.prevent="exportActivationCode"
                        >
                          Export
                        </v-btn>
                      </v-card-actions>
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
                        <v-btn text class="primary mb-4" @click="proceedAction">
                          Submit
                        </v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                </h1>
              </v-card-text>
              <v-list-item three-line>
                <v-list-item-content>
                  <div class="text-center pb-3">
                    <v-form
                      method="post"
                      lazy-validation
                      v-on:submit.stop.prevent="save"
                      ref="entryForm"
                    >
                      <v-text-field
                        v-model="editedItem.student_number"
                        label="Student Number"
                        :rules="[rules.required]"
                        name="student_number"
                        type="number"
                        color="teal accent-4"
                        dense
                        class="text-sm-h6 mr-2 ml-2 mb-2 mb-1"
                      />
                      <v-text-field
                        dense
                        class="text-sm-h6 mr-2 ml-2 mb-2 mb-1"
                        type="text"
                        v-model="editedItem.name"
                        label="Full Name"
                        :rules="[rules.required, rules.min]"
                        hint="(Surname, First name MI)"
                        color="teal accent-4"
                      />

                      <v-select
                        v-model="editedItem.campus_id"
                        :items="campuses"
                        item-text="name"
                        label="Select Campus"
                        item-value="id"
                        color="primary"
                        @change="campusListener"
                        :rules="[rules.required]"
                        class="text-sm-h6 mr-2 ml-2 mb-2 mb-1"
                        dense
                        chip
                      ></v-select>

                      <v-select
                        v-model="editedItem.program_id"
                        :items="programs"
                        label="Select Program"
                        item-value="id"
                        item-text="name"
                        @change="programListener"
                        color="primary"
                        :rules="[rules.required]"
                        class="mr-2 ml-2 mb-2 mb-1"
                        wrap
                        dense
                        chip
                      ></v-select>

                      <v-select
                        v-model="editedItem.year"
                        :items="years"
                        label="Select Year Level"
                        item-value="id"
                        item-text="name"
                        color="primary"
                        :rules="[rules.required]"
                        class="text-sm-h6 mr-2 ml-2 mb-2 mb-1"
                        dense
                        chip
                      ></v-select>

                      <v-divider />

                      <v-row class="ma-2">
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
                      </v-row>
                    </v-form>
                  </div>
                </v-list-item-content>
              </v-list-item>
            </v-card>
          </v-col>
          <v-col cols="12" lg="8" class="elevation-2 white">
            <v-data-table
              item-key="id"
              class="elevation-0 pr-2 pl-2"
              dense
              :loading="loading"
              loading-text="Loading... Please wait"
              :headers="headers"
              :page="page + 1"
              :pageCount="numberOfPages"
              :items="students.data"
              :options.sync="options"
              :server-items-length="totalStudents"
              :items-per-page="10"
              :footer-props="{
                itemsPerPageOptions: [5, 10, 15],
                itemsPerPageText: 'Student Per Page',
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
              </template>
              <template v-slot:item.id="{ item }">
                <td>{{ students.data.indexOf(item) + 1 }}</td>
              </template>

              <template v-slot:item.student_number="{ item }">
                <td>
                  <v-badge
                    dot
                    small
                    bordered
                    color="success"
                    v-if="item.stat == '1'"
                    class="mr-4"
                  />
                  <v-badge dot small bordered color="red" v-else class="mr-4" />
                  {{ item.student_number }}
                </td>
              </template>

              <template v-slot:item.actions="{ item }">
                <v-icon
                  small
                  class="mr-2 warning--text"
                  @click="editItem(item)"
                >
                  mdi-pencil
                </v-icon>
                <v-icon small class="mr-2 red--text" @click="deleteItem(item)">
                  mdi-delete-forever </v-icon
                >
                
     <!-- <v-icon  @click="resetP(item)" small class="mr-2 red--text"  v-if="item.stat == '1'">
                  mdi-lock-reset
                </v-icon> -->
      
                <v-icon
                  small
                  class="mr-2 primary--text"
                  @click="copyToClipBoard(item.code)"
                  v-clipboard="item.code"
                >
                  mdi-qrcode
                </v-icon>
              </template>
            </v-data-table>
          </v-col>
        </v-row>
        <div class="row">
          <!-- <div class="form-row">
        <div class="col-md-12">
          <label class="form-control-label"  for="input-file-import">Upload Excel File</label>
          <input type="file" class="form-control" :class="{ ' is-invalid' : error.message }" id="input-file-import" name="file_import" ref="import_file"  @change="onFileChange">
          <div v-if="error.message" class="invalid-feedback">
            
          </div>
          <v-btn @click="proceedAction">Import</v-btn>
        </div>
      </div> -->
        </div>

        <v-snackbar
          v-model="snackbar"
          :color="snackbarColor"
          right
          timeout="5000"
          outlined
          top
          width="50"
        >
          <v-icon left> mdi-error </v-icon>{{ text }}

          <template v-slot:action="{ attrs }">
            <v-btn
              :color="snackbarColor"
              text
              v-bind="attrs"
              @click="snackbar = false"
            >
              <v-icon dark left> mdi-close </v-icon>close
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
    uploadDialog: "",
    loadingDialog:false,
    ffile: null,
    error: {},
    import_file: "",
    valid: true,
    dialog: false,
    loading: false,
    isLoading:false,
    snackbar: false,
    cDialog:false,
    selected: [],
    text: "",
    success: "",
    error: "",
    snackbarColor: "",
    searchItem: "",
    headers: [
      { text: "Student Number", value: "student_number" },
      { text: "Name", value: "name" },
      { text: "Program", value: "program", sortable: false },

      { text: "Action", align: "right", value: "actions" },
    ],
    page: 0,
    totalStudents: 0,
    numberOfPages: 0,
    options: {},
    students: {},
    campuses: {},

    sections: {},
    programs: "",
    export_programs: "",
    export_campuses: "",
    filenames:"",
    years: [1, 2, 3, 4, 5],
    rules: [],
    fileRules: [
      (file) => {
        const pattern = /\.csv$/;
        return !file || pattern.test(file) || "File type should be csv.";
      },
    ],
    editedIndex: -1,
    editedItem: {
      id: "",
      student_number: "",
      name: "",
      campus: "",
      section: "",
      program: "",
      campus_id: "",
      section_id: "",
      program_id: "",
      user_id:"",
      code: "",
      year: "",
      created_at: "",
      stat: "",
    },
    defaultItem: {
      id: "",
      student_number: "",
      name: "",
      campus: "",
      section: "",
      program: "",
      campus_id: "",
      section_id: "",
      program_id: "",
      export_campus_id: "", 
      export_program_id: "",
      user_id:"",
      code: "",
      year: "",
      created_at: "",
      stat: "",
    },
    tempItem: {
      id: "",
      student_number: "",
      name: "",
      campus: "",
      section: "",
      program: "",
      campus_id: "",
      section_id: "",
      program_id: "",
      user_id:"",
      export_campus_id: "", 
      export_program_id: "",
      code: "",
      year: "",
      created_at: "",
      stat: "",
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
    "editedItem.student_number"(val) {
      this.rules = [];
    },
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
    copyToClipBoard(textToCopy) {
      console.log(textToCopy);
      this.text = "(" + textToCopy + ") Copied to clipboard!";
      this.snackbarColor = "success darken-1";
      document.execCommand("copy");
      this.snackbar = true;
    },
    onFileChange(e) {
      console.log(e.target.files[0]);
      this.import_file = this.$refs.import_file;
    },

    proceedAction() {
      this.loading = true;
      let formData = new FormData();
      formData.append("import_file", this.ffile);

      axios
        .post("api/v1/students/import", formData, {
          headers: { "content-type": "multipart/form-data" },
        })
        .then((res) => {
          if (res.status === 200) {
            // codes here after the file is upload successfully
            this.uploadDialog = false;
            this.loading = false;
            this.campuses = res.data.campuses;
            this.programs = res.data.programs;
            this.sections = res.data.sections;
            this.students = res.data.students;
            this.totalStudents = res.data.students.total;
            this.numberOfPages = res.data.students.last_page;
          }
        })
        .catch((error) => {
          // code here when an upload is not valid
          this.uploading = false;
          this.loading = false;
          this.error = error.response.data;
          console.log("check error: ", this.error);
        });
    },

    readDataFromAPI() {
      this.loading = true;
      const { page, itemsPerPage } = this.options;
      let pageNumber = page;
      axios
        .get(`/api/v1/students?page=` + pageNumber, {
          params: { per_page: itemsPerPage },
        })
        .then((response) => {
          //Then injecting the result to datatable parameters.
          this.loading = false;
          this.students = response.data.students;
          this.campuses = response.data.campuses;
          this.programs = response.data.programs;
          this.sections = response.data.sections;
          this.export_programs= response.data.programs;
          this.export_campuses= response.data.campuses;
          this.totalStudents = response.data.students.total;
          this.numberOfPages = response.data.students.last_page;
        });
    },

    searchIt(d) {
      if (d.length > 2) {
        const { page, itemsPerPage } = this.options;
        let pageNumber = page;
        axios
          .get(`/api/v1/students/${d}?page=` + pageNumber, {
            params: { per_page: itemsPerPage },
          })
          .then((res) => {
            this.loading = false;
            this.students = res.data.students;
            console.log(this.students);
            this.campuses = res.data.campuses;
            this.programs = res.data.programs;
            this.sections = res.data.sections;
            this.totalStudents = res.data.students.total;
            this.numberOfPages = res.data.students.last_page;
          })
          .catch((err) => {
            console.error(err);
          });
      }
      if (d.length <= 0) {
        const { page, itemsPerPage } = this.options;
        let pageNumber = page;
        axios
          .get(`/api/v1/students?page=` + pageNumber, {
            params: { per_page: itemsPerPage },
          })
          .then((res) => {
            this.loading = false;
            this.campuses = res.data.campuses;
            this.programs = res.data.programs;
            this.sections = res.data.sections;
            this.students = res.data.students;
            this.export_programs= res.data.programs;
            this.export_campuses= res.data.campuses;
            this.totalStudents = res.data.students.total;
            this.numberOfPages = res.data.students.last_page;
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
      this.editedIndex = this.students.data.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.tempItem = this.editedItem;
      this.dialog = true;
    },
    campusListener() {
      axios
        .post("/api/v1/campusListener", {
          campus_id: this.editedItem.campus_id,
        })
        .then((res) => {
          this.programs = res.data.programs;
          this.sections = "";
        })
        .catch((err) => {
          console.error(err);
        });
    },
    exportcampusListener($item) {
      
      console.log($item)
      axios
        .post("/api/v1/campusListener", {
          campus_id: this.editedItem.export_campus_id,
        })
        .then((res) => {
          this.export_programs = res.data.programs;
   
        })
        .catch((err) => {
          console.error(err);
        });
    },
    programListener() {
      axios
        .post("/api/v1/programListener", {
          program_id: this.editedItem.program_id,
        })
        .then((res) => {
          this.sections = res.data.sections;
        })
        .catch((err) => {
          console.error(err);
        });
    },
    campusListenerNew() {
      axios
        .post("/api/v1/campusListener", { campus_id: this.editedItem.campus })
        .then((res) => {
          this.programs = res.data.programs;
          this.sections = "";
        })
        .catch((err) => {
          console.error(err);
        });
    },
    programListenerNew() {
      axios
        .post("/api/v1/programListener", {
          program_id: this.editedItem.program,
        })
        .then((res) => {
          this.sections = res.data.sections;
        })
        .catch((err) => {
          console.error(err);
        });
    },
    deleteItem(item) {
      const index = this.students.data.indexOf(item);
      let decide = confirm("Are you sure you want to delete this item?");
      if (decide) {
        axios
          .delete("/api/v1/students/" + item.id)
          .then((res) => {
            this.text = "Record Deleted Successfully!";
            this.snackbarColor = "primary darken-1";
            this.snackbar = true;
            this.students.data.splice(index, 1);
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Deleting Record";
            this.snackbarColor = "error darken-1";
            this.snackbar = true;
          });
      }
      this.close();
    },

    close() {
      this.dialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },
    exportActivationCode(){
         axios
        .post("/api/v1/getFileName",  this.editedItem)
        .then((response) => {
           
          this.filenames = response.data.filename;
        })
        .catch((err) => {
          console.error(err);
        });
        axios
        .get("/api/v1/students/activationcode/export", {responseType: 'blob'
     ,params: this.editedItem})
        .then((response) => {
          const url = window.URL.createObjectURL(
            new Blob([response.data], { type: "text/xlsx" })
          );

          // Create dynamic <a> element
          const link = document.createElement("a");
          link.href = url;
        
        
          link.setAttribute("download", this.filenames+'.xlsx');
          document.body.appendChild(link);

          // Dynamicall click the a link element
          // This will download the csv file
          link.click();
          this.exportDialog = false;
          this.filenames = '',
          this.close();
        })
        .catch((err) => {
          console.error(err);
        });
    },
    resetP($item){
        axios
        .post("/api/v1/resetP",  $item)
        .then((response) => {
           
          // console.log(response.data)
         
          // this.cDialog = false;
        })
        .catch((err) => {
          console.error(err);
        });
        //  this.loadingDialog = false;
        //   this.cDialog = false;
    },
    save() {
      this.rules = {
        required: (v) => !!v || "This Field is Required",
        min: (v) => v.length >= 5 || "Minimum 5 Characters Required",
        validEmail: (v) => /.+@.+\..+/.test(v) || "Email must be valid",
      };
      this.$refs.entryForm.validate();
      console.log(this.editedItem);
      if (this.editedIndex > -1) {
        const index = this.editedIndex;
        console.log("Temp Data " + this.tempItem);

        axios
          .put("/api/v1/students/" + this.editedItem.id, this.editedItem)
          .then((res) => {
            this.text = "Record Updated Successfully!";
            this.snackbarColor = "primary darken-1";
            this.snackbar = true;
            Object.assign(this.students.data[index], res.data.student);
            console.log(this.editedItem);
            this.close();
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Updating Record";
            this.snackbarColor = "error darken-1";
            this.snackbar = true;
          });
      } else {
        console.log(this.editedItem);

        axios
          .post("/api/v1/students", this.editedItem)
          .then((res) => {
            this.text = "Record Added Successfully!";
            this.snackbarColor = "primary darken-1";
            this.snackbar = true;
            // this.students.data.push(res.data.student);
            this.students = res.data.students;
            this.close();
          })
          .catch((err) => {
            console.dir(err);
            this.text = "Error Inserting Record";
            this.snackbarColor = "error darken-1";
            this.snackbar = true;
          });
      }
      
    },
  },
};
</script>