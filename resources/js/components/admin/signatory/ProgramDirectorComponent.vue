<template>
  <v-sheet>
    <v-card elevation="0">
      <v-container class="grey lighten-5" fluid>
        <v-row wrap>
          <v-col cols="12" lg="4">
            <v-card>
              <v-card-text style="padding-bottom: 0">
                <h1 class="title desplay-2 black--text text--accent3">
                  <v-icon class="ma-1 pb-2">{{ formIcon }}</v-icon>
                  {{ formTitle }} Program Director
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
                      <v-autocomplete
                        v-model="editedItem.user_id"
                        :items="user_staff"
                        :loading="isLoading"
                        :search-input.sync="search"
                        chips
                        clearable
                        item-text="user.name"
                        item-value="user.id"
                        item-key="user.id"
                        label="Search User..."
                      >
                        <template v-slot:no-data>
                          <v-list-item>
                            <v-list-item-title>
                              Search user
                              <strong>Staff</strong>
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
                            <v-icon left> mdi-badge-account </v-icon>
                            <span v-text="item.user.name"></span>
                          </v-chip>
                        </template>
                        <template v-slot:item="{ item }">
                          <v-list-item-avatar
                            color="indigo"
                            class="caption font-weight-light white--text"
                          >
                            {{ item.user.name[0] }}
                          </v-list-item-avatar>
                          <v-list-item-content>
                            <v-list-item-title
                              v-text="item.user.name"
                            ></v-list-item-title>
                          </v-list-item-content>
                        </template>
                      </v-autocomplete>
                      <v-select
                        v-model="editedItem.campus_id"
                        :items="campuses"
                        item-text="name"
                        label="Select Campus"
                        item-value="id"
                        item-key="id"
                        color="primary"
                        chips
                        @change="campusListener"
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
                        v-model="editedItem.program_id"
                        :items="programs"
                        :loading="isLoading"
                        :search-input.sync="search"
                        chips
                        clearable
                        item-text="name"
                        item-value="id"
                        item-key="id"
                        label="Search Program..."
                        @change="campusListenerNew"
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
                      <v-autocomplete
                        v-model="editedItem.semester_id"
                        :items="semesters"
                        :loading="isLoading"
                        :search-input.sync="search"
                        chips
                        clearable
                        item-text="semester"
                        item-value="id"
                        item-key="id"
                        label="Search Semester..."
                      >
                        <template v-slot:no-data>
                          <v-list-item>
                            <v-list-item-title>
                              Search Semester
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
                            <span v-text="item.semester"></span>
                          </v-chip>
                        </template>
                        <template v-slot:item="{ item }">
                          <v-list-item-content>
                            <v-list-item-title
                              v-text="item.semester"
                            ></v-list-item-title>
                          </v-list-item-content>
                        </template>
                      </v-autocomplete>

                      <v-divider />

                      <v-row class="ma-2 float-right">
                        <!-- <v-btn color="blue darken-1" text @click="close">
                      Cancel
                    </v-btn> -->
                        <v-btn
                          color="blue darken-1"
                          text
                          type="submit"
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
          <v-col cols="12" lg="8">
            <v-data-table
              item-key="id"
              class="elevation-1 pa-6"
              :loading="loading"
              loading-text="Loading... Please wait"
              :headers="headers"
              :page="page + 1"
              :pageCount="numberOfPages"
              :items="programdirectors.data"
              :options.sync="options"
              :server-items-length="totalProgramDirectors"
              :items-per-page="5"
              :footer-props="{
                itemsPerPageOptions: [5, 10, 15],
                itemsPerPageText: 'Stcouncil Per Page',
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

              <template v-slot:item.name="{ item }">
                <v-edit-dialog
                  large
                  block
                  persistent
                  :return-value.sync="item.name"
                  @save="updatePD(item)"
                >
                  {{ item.name }}
                  <template v-slot:input>
                    <h2>Change PD</h2>
                  </template>
                  <template v-slot:input>
                    <v-select
                      v-model="item.user_id"
                      :items="user_staff"
                      label="Select PD"
                      item-value="user.id"
                      item-text="user.name"
                      color="primary"
                      :rules="[rules.required]"
                    ></v-select>
                  </template>
                </v-edit-dialog>
              </template>
              <template v-slot:item.college="{ item }">
                <span class="text-uppercase">{{ item.college }}</span>
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
                  mdi-delete-forever
                </v-icon>
              </template>
            </v-data-table>
          </v-col>
        </v-row>
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
    valid: true,
    dialog: false,
    loading: false,
    snackbar: false,
    addEdit: "Add",
    selected: [],
    isLoading: false,
    text: "",
    success: "",
    error: "",
    snackbarColor: "",
    searchItem: "",
    headers: [
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
    user_pd: {},
    years: [1, 2, 3, 4, 5],
    rules: {
      required: (v) => !!v || "This Field is Required",
      min: (v) => v.length >= 5 || "Minimum 5 Characters Required",
      validEmail: (v) => /.+@.+\..+/.test(v) || "Email must be valid",
    },
    editedIndex: -1,
    editedItem: {
      id: "",
      user_id: "",
      name: "",
      campus: "",
      section: "",
      program: "",
      campus_id: "",
      section_id: "",
      program_id: "",
    },
    defaultItem: {
      id: "",
      user_id: "",
      campus: "",
      program: "",
      campus_id: "",
      section_id: "",
      program_id: "",
    },
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New" : "Edit";
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
          params: { per_page: itemsPerPage },
        })
        .then((response) => {
          //Then injecting the result to datatable parameters.
          this.loading = false;
          this.programdirectors = response.data.programdirectors;
          this.user_pd = response.data.user_pd;
          this.campuses = response.data.campuses;
          this.programs = response.data.programs;
          this.semesters = response.data.semesters;
          this.user_staff = response.data.user_staff;
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
            params: { per_page: itemsPerPage },
          })
          .then((res) => {
            this.loading = false;
            this.programdirectors = res.data.programdirectors;
            this.user_pd = res.data.user_pd;
            this.colleges = res.data.colleges;

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
          .get(`/api/v1/programdirectors?page=` + pageNumber, {
            params: { per_page: itemsPerPage },
          })
          .then((res) => {
            this.loading = false;
            this.programdirectors = res.data.programdirectors;
            this.user_pd = res.data.user_pd;
            this.colleges = res.data.colleges;
            this.semesters = res.data.semesters;
            this.campuses = res.data.campuses;
            this.programs = res.data.programs;
            this.user_staff = res.data.user_staff;
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
    campusListener() {
      axios
        .post("/api/v1/campusListener", {
          campus_id: this.editedItem.campus_id,
        })
        .then((res) => {
          this.programs = res.data.programs;
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
    updatePD(item) {
      const index = this.programdirectors.data.indexOf(item);
      axios
        .post("/api/v1/pd/update", { new_pd: item.user_id, pd: item.id })
        .then((res) => {
          this.programdirectors = res.data.programdirectors;

          this.user_pd = res.data.user_pd;
          this.totalProgramDirectors = res.data.programdirectors.total;
          this.numberOfPages = res.data.programdirectors.last_page;
          this.text = "successfully update";
          this.snackbar = true;
        })
        .catch((err) => {
          console.error(err);
        });
      console.log(item);
    },
    deleteItem(item) {
      const index = this.programdirectors.data.indexOf(item);
      let decide = confirm("Are you sure you want to delete this item?");
      if (decide) {
        axios
          .delete("/api/v1/programdirectors/" + item.id)
          .then((res) => {
            this.text = "Record Deleted Successfully!";
            this.snackbarColor = "primary darken-1";
            this.snackbar = true;
            this.programdirectors.data.splice(index, 1);
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Deleting Record";
            this.snackbarColor = "error darken-1";
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
          .put(
            "/api/v1/programdirectors/" + this.editedItem.id,
            this.editedItem
          )
          .then((res) => {
            this.text = "Record Updated Successfully!";
            this.snackbarColor = "primary darken-1";
            this.snackbar = true;
            Object.assign(
              this.programdirectors.data[index],
              res.data.programdirector
            );
            console.log(this.editedItem);
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Updating Record";
            this.snackbarColor = "error darken-1";
            this.snackbar = true;
          });
      } else {
        axios
          .post("/api/v1/programdirectors", this.editedItem)
          .then((res) => {
            this.text = "Record Added Successfully!";
            this.snackbarColor = "primary darken-1";
            this.snackbar = true;
            // this.programdirectors.data.push(res.data.programdirector);
            this.programdirectors = res.data.programdirectors;
          })
          .catch((err) => {
            console.dir(err);
            this.text = "Error Inserting Record";
            this.snackbarColor = "error darken-1";
            this.snackbar = true;
          });
      }
      this.close();
    },
  },
};
</script>