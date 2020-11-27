<template>
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
        :items="students.data"
        :options.sync="options"
        :server-items-length="totalStudents"
        :items-per-page="10"
        show-select
        :footer-props="{
          itemsPerPageOptions: [5, 10, 15],
          itemsPerPageText: 'Students Per Page',
          'show-current-page': true,
          'show-first-last-page': true,
        }"
      >
        <template v-slot:top>
          <v-text-field
            append-icon="mdi-magnify"
            label="Search"
            @input="searchIt"
          ></v-text-field>
          <v-toolbar flat color="white">
            <div class="overline text-h6">
              Student List
              <span class="font-italic subtitle-2"
                >(2nd Semester A/Y 2020-2021 )</span
              >
            </div>

            <v-spacer></v-spacer>

            <v-dialog v-model="dialog" max-width="500px">
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  color="primary"
                  dark
                  class="mb-2"
                  v-bind="attrs"
                  v-on="on"
                >
                  New Student
                </v-btn>
              </template>
              <v-card>
                <v-card-title class="primary white--text">
                  <v-icon class="white--text" style="padding-right: 8px">{{
                    formIcon
                  }}</v-icon>
                  <span class="headline">{{ formTitle }}</span>
                </v-card-title>
                <v-form
                  v-model="valid"
                  method="post"
                  v-on:submit.stop.prevent="save"
                >
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" sm="12">
                          <v-text-field
                            v-model="editedItem.student_number"
                            label="Student Number"
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
                            value="editedItem.campus"
                            color="primary"
                            :rules="[rules.required]"
                          ></v-select>
                        </v-col>
                        <v-col cols="12" sm="12" style="margin: 0">
                          <v-select
                            v-model="editedItem.program"
                            :items="programs"
                            label="Select Program"
                            value="editedItem.program"
                            color="primary"
                            :rules="[rules.required]"
                          ></v-select>
                        </v-col>
                        <v-col cols="12" sm="12" style="margin: 0">
                          <v-select
                            v-model="editedItem.section"
                            :items="sections"
                            label="Select Section"
                            value="editedItem.section"
                            color="primary"
                            :rules="[rules.required]"
                          ></v-select>
                        </v-col>
                        <v-col cols="12" sm="12" style="margin: 0">
                          <v-select
                            v-model="editedItem.year"
                            :items="years"
                            label="Select Year Level"
                            value="editedItem.year"
                            color="primary"
                            :rules="[rules.required]"
                          ></v-select>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>

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
                </v-form>
              </v-card>
            </v-dialog>
          </v-toolbar>
        </template>
        <template v-slot:item.role="{ item }">
          <v-edit-dialog
            large
            block
            persistent
            :return-value.sync="item.role"
            @save="updateRole(item)"
          >
            {{ item.role }}
            <template v-slot:input>
              <h2>Change Role</h2>
            </template>
            <template v-slot:input>
              <v-select
                v-model="item.role"
                :items="roles"
                label="Select Role"
                value="editedItem.role"
                color="primary"
                :rules="[rules.required]"
              ></v-select>
            </template>
          </v-edit-dialog>
        </template>
        <template v-slot:item.id="{ item }">
          <td>{{ students.data.indexOf(item) + 1 }}</td>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-icon small class="mr-2" @click="editItem(item)">
            mdi-pencil
          </v-icon>
          <v-icon small class="mr-2" @click="deleteItem(item)">
            mdi-delete-forever
          </v-icon>
        </template>
        <template v-slot:no-data>
          <v-btn color="primary" @click="initialize"> Reset </v-btn>
        </template>
      </v-data-table>
      <v-snackbar v-model="snackbar" bottom>
        {{ text }}

        <v-btn color="pink" text @click="snackbar = false"> Close </v-btn>
      </v-snackbar>
    </v-container>
  </v-card>
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
    headers: [
      {
        text: "No",
        align: "left",
        value: "id",
      },
      { text: "Student Number", value: "student_number" },
      { text: "Name", value: "name" },
      { text: "Year", value: "year" },
      { text: "Section", value: "section" },
      { text: "Program", value: "program", sortable: false },
      { text: "Campus", value: "campus" },
      { text: "Code", value: "code" },
      { text: "Action", value: "actions" },
    ],
    page: 0,
    totalStudents: 0,
    numberOfPages: 0,
    options: {},
    students: {},
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
      student_number: "",
      name: "",
      campus: "",
      section: "",
      program: "",
      year: "",
      created_at: "",
    },
    defaultItem: {
      id: "",
      student_number: "",
      name: "",
      campus: "",
      section: "",
      program: "",
      year: "",
      created_at: "",
    },
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Student" : "Edit Student";
    },
    formIcon() {
      return this.editedIndex === -1 ? "mdi-account-plus" : "mdi-account-edit";
    },
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
    options: {
      handler() {
        this.readDataFromAPI();
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
          this.totalStudents = response.data.students.total;
          this.numberOfPages = response.data.students.last_page;
        });
    },

    searchIt(d) {
      if (d.length > 3) {
        const { page, itemsPerPage } = this.options;
        axios
          .get(`/api/v1/students/${d}`)
          .then((res) => {
            this.loading = false;
            this.students = res.data.students;
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
        axios
          .get(`/api/v1/students?page=${d.page}`, {
            params: { per_page: d.itemsPerPage },
          })
          .then((res) => {
            this.loading = false;
            this.campuses = res.data.campuses;
            this.programs = res.data.programs;
            this.sections = res.data.sections;
            this.students = res.data.students;
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
      this.dialog = true;
    },

    deleteItem(item) {
      const index = this.students.data.indexOf(item);
      let decide = confirm("Are you sure you want to delete this item?");
      if (decide) {
        axios
          .delete("/api/v1/students/" + item.id)
          .then((res) => {
            this.text = "Record Deleted Successfully!";
            this.snackbar = true;
            this.users.data.splice(index, 1);
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Deleting Record";
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
      // if (this.editedIndex > -1) {
      //   const index = this.editedIndex;
      //   axios
      //     .put("/api/v1/students/" + this.editedItem.id, this.editedItem)
      //     .then((res) => {
      //       this.text = "Record Updated Successfully!";
      //       this.snackbar = true;
      //       Object.assign(this.students.data[index], res.data.user);
      //     })
      //     .catch((err) => {
      //       console.log(err.response);
      //       this.text = "Error Updating Record";
      //       this.snackbar = true;
      //     });
      // } else {
      //   axios
      //     .post("/api/v1/students", this.editedItem)
      //     .then((res) => {
      //       this.text = "Record Added Successfully!";
      //       this.snackbar = true;
      //       this.students.data.push(res.data.student);
      //       console.log(res);
      //     })
      //     .catch((err) => {
      //       console.dir(err);
      //       this.text = "Error Inserting Record";
      //       this.snackbar = true;
      //     });
      // }
      // this.close();
      console.log(this.editedItem);
    },
  },
};
</script>