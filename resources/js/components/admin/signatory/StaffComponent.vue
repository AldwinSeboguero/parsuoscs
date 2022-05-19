<template>
<v-sheet 
     >
   <v-card elevation="0">
    <v-container class="grey lighten-5" fluid>
     <v-row wrap>
       <v-col cols="12" lg="4">
           <v-card >
         <v-card-text style="padding-bottom:0">
                      <h1
                        class="title desplay-2 black--text text--accent3"
                       
                      >
                      <v-icon class="ma-1 ">mdi-account-plus-outline</v-icon>
                        Add Staff
                        <v-dialog
                         v-model="copyDialog"
                         
                          width="390"
                      
                        >
                      <template v-slot:activator="{ on, attrs }">
                        <v-btn
                          dark
                          v-bind="attrs"
                          small
                          v-on="on"
                          icon class="float-right success white--text ml-2">
                                        <v-icon small>mdi-content-duplicate</v-icon>

                                      </v-btn>
                      </template>
                     <v-card >
                      <v-card-title class="overline pa-4">
                        Copy Staff List from Previous Semester
                           
                 <v-autocomplete
                  v-model="editedItem.new_semester_id"
                  :items="semesters"
                  :loading="isLoading"
                  :search-input.sync="search"
                  chips
                  clearable
                  item-text="semester"
                  item-value="id"
                  item-key="id" 
                  label="Search New Semester..."
 
                  
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
                      color="purple"
                      class="white--text"
                      v-on="on"
                    >
                       
                      <span v-text="item.semester"></span>
                    </v-chip>
                  </template>
                  <template v-slot:item="{ item }">
                    
                    <v-list-item-content>
                      <v-list-item-title v-text="item.semester"></v-list-item-title> 
                    </v-list-item-content> 
                  </template>
                </v-autocomplete>
                        <v-btn :disabled="editedItem.new_semester_id == null" block class="success" rounded @click="copyPrevStaff">Copy</v-btn>
                      </v-card-title>
                     </v-card>
           
                    </v-dialog>
                      </h1> 
                      
        </v-card-text>
        <v-list-item three-line>
          <v-list-item-content> 
            <div class="text-center pb-3 ">
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
                  <template v-slot:selection="{ attr, on, item, selected }">
                    <v-chip
                      v-bind="attr"
                      :input-value="selected"
                      color="purple"
                      class="white--text"
                      v-on="on"
                    >
                      <v-icon left>
                        mdi-badge-account
                      </v-icon>
                      <span v-text="item.user.name"></span>
                    </v-chip>
                  </template>
                  <template v-slot:item="{ item }">
                    <v-list-item-avatar
                      color="indigo"
                      class="caption font-weight-light white--text"
                    >
                      {{ item.user.name[0]}}
                    </v-list-item-avatar>
                    <v-list-item-content>
                      <v-list-item-title v-text="item.user.name"></v-list-item-title> 
                    </v-list-item-content> 
                  </template>
                </v-autocomplete>

                <v-autocomplete
                  v-model="editedItem.designee_id"
                  :items="designations"
                  :loading="isLoading"
                  :search-input.sync="searchDesignation"
                  chips
                  clearable
                  item-text="name"
                  item-value="id"
                  item-key="id" 
                  label="Search Designation..."
 
                  
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
                      color="purple"
                      class="white--text"
                      v-on="on"
                    > 
                      <span v-text="item.name"></span>
                    </v-chip>
                  </template>
                  <template v-slot:item="{ item }">
                    
                    <v-list-item-content>
                      <v-list-item-title v-text="item.name"></v-list-item-title> 
                    </v-list-item-content> 
                  </template>
                </v-autocomplete>
                <v-autocomplete
                  v-model="editedItem.campus_id"
                  :items="campuses"
                  :loading="isLoading"
                  :search-input.sync="search"
                  chips
                  clearable
                  item-text="name"
                  item-value="id"
                  item-key="id" 
                  label="Search Campus..."
 
                  
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
                      color="purple"
                      class="white--text"
                      v-on="on"
                    >
                       
                      <span v-text="item.name"></span>
                    </v-chip>
                  </template>
                  <template v-slot:item="{ item }">
                    
                    <v-list-item-content>
                      <v-list-item-title v-text="item.name"></v-list-item-title> 
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
                  <template v-slot:selection="{ attr, on, item, selected }">
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
                      <v-list-item-title v-text="item.semester"></v-list-item-title> 
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
                      :disabled="editedItem.name == '' || editedItem.short_name == ''  ? true : false"
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
       <v-col cols="12" lg="8" >
     <v-data-table
        item-key="id" 
        class="elevation-1 pa-6"
        :loading="loading"
        loading-text="Loading... Please wait"
        :headers="headers"
        :page="page + 1"
        :pageCount="numberOfPages"
        :items="staffs.data"
        :options.sync="options"
        :server-items-length="totalStaffs"
        :items-per-page="5"  
        :footer-props="{
          itemsPerPageOptions: [5, 10, 15],
          itemsPerPageText: 'Staff Per Page',
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
        :return-value.sync ="item.name"
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
             <template v-slot:item.actions="{ item }">
        <v-icon small class="mr-2 warning--text" @click="editItem(item)"> mdi-pencil </v-icon>
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
     items: [],
      model: null,
      search: null,
      copyDialog: false,
      tab: null,
    valid: true,
    dialog: false,
    loading: false,
    isLoading: false,
    snackbar: false,
    selected: [],
    text: "",
    success: "",
    error: "", 
    snackbarColor:"",
    searchItem: '',
    headers: [  
      { text: "Name", value: "name" },  
      { text: "Campus", value: "campus" }, 
      { text: "Semester", value: "semester" }, 
      { text: "Action", align: 'right', value: "actions" },
    ],
    page: 0,
    totalStaffs: 0,
    numberOfPages: 0,
    options: {},
    staffs: {},
    campuses: {},
    designations: {},
    semesters: {},
    user_staff:{},
    years: [1,2,3,4,5], 
    rules: {
      required: (v) => !!v || "This Field is Required",
      min: (v) => v.length >= 5 || "Minimum 5 Characters Required",
      validEmail: (v) => /.+@.+\..+/.test(v) || "Email must be valid",
    },
    editedIndex: -1,
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
    model (val) {
        if (val != null) this.tab = 0
        else this.tab = null
      },
      search (val) {
        console.log(this.editedItem.user_id+"Name"+val)
        // Items have already been loaded
         if (this.user_staff.length > 0) return
        this.editItem.user_id = val
         this.isLoading =true
        // // Lazily load input items
        // fetch('/api/v1/staffs?page=0')
        //   .then(res => res.clone().json())
        //   .then(res => {
        //     this.user_staff = res.user_staff[0].user.name
        //   })
        //   .catch(err => {
        //     console.log(err)
        //   })
        //   .finally(() => (this.isLoading = false))
        const { page, itemsPerPage } = this.options;
        let pageNumber = page;
        axios
          .get(`/api/v1/staffs/${val}?page=` + pageNumber, {
          params: { 'per_page': itemsPerPage },
        })
          .then((res) => { 
             this.user_staff = res.data.user_staff; 
             console.log(this.user_staff)
             this.isLoading = false
          })
          .catch((err) => {
            console.error(err);
            this.isLoading = false
          });
      },
       searchDesignation (val) {
        console.log(this.editedItem.user_id+"Name"+val)
        // Items have already been loaded
         if (this.user_staff.length > 0) return
        this.editItem.user_id = val
         this.isLoading =true
        // // Lazily load input items
        // fetch('/api/v1/staffs?page=0')
        //   .then(res => res.clone().json())
        //   .then(res => {
        //     this.user_staff = res.user_staff[0].user.name
        //   })
        //   .catch(err => {
        //     console.log(err)
        //   })
        //   .finally(() => (this.isLoading = false))
        const { page, itemsPerPage } = this.options;
        let pageNumber = page;
        axios
          .get(`/api/v1/staffs/${val}?page=` + pageNumber, {
          params: { 'per_page': itemsPerPage },
        })
          .then((res) => { 
             this.user_staff = res.data.user_staff; 
             console.log(this.user_staff)
             this.isLoading = false
          })
          .catch((err) => {
            console.error(err);
            this.isLoading = false
          });
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
    copyPrevStaff(){
       axios
          .post("/api/v1/copyPreviousStaff", this.editedItem)
          .then((res) => {
            this.text = "Copying Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            // this.staffs.data.push(res.data.staff); 
            this.staffs = res.data.staffs
            this.totalStaffs = res.data.staffs.total;
            this.numberOfPages = res.data.staffs.last_page;
            this.copyDialog = false;
          })
          .catch((err) => {
       
            this.text = "Error Copying Record";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;
          });
    },
     readDataFromAPI() {
      this.loading = true;
      const { page, itemsPerPage } = this.options;
      let pageNumber = page;
      axios
        .get(`/api/v1/staffs?page=` + pageNumber, {
          params: { 'per_page': itemsPerPage },
        })
        .then((response) => {
          //Then injecting the result to datatable parameters.
          this.loading = false;
         this.staffs = response.data.staffs;
         this.user_staff = response.data.user_staff;
         this.designations = response.data.designations;
          this.campuses = response.data.campuses;
          this.semesters = response.data.semesters; 
          this.totalStaffs = response.data.staffs.total;
          this.numberOfPages = response.data.staffs.last_page;
        });
    },

    searchIt(d) {
      if (d.length > 2) {
        const { page, itemsPerPage } = this.options;
        let pageNumber = page;
        axios
          .get(`/api/v1/staffs/${d}?page=` + pageNumber, {
          params: { 'per_page': itemsPerPage },
        })
          .then((res) => {
            this.loading = false;  
            this.staffs = res.data.staffs; 
             this.designations = res.data.designations;
              this.campuses = res.data.campuses;
               this.semesters = res.data.semesters;
            console.log(this.staffs);
            this.totalStaffs = res.data.staffs.total;
            this.numberOfPages = res.data.staffs.last_page;
          })
          .catch((err) => {
            console.error(err);
          });
      }
      if (d.length <= 0) {
        const { page, itemsPerPage } = this.options;
        let pageNumber = page;
        axios
          .get(`/api/v1/staffs?page=`+ pageNumber, {
          params: { 'per_page': itemsPerPage },
          })
          .then((res) => {
            this.loading = false;  
            this.staffs = res.data.staffs;
             this.user_staff = res.data.user_staff;
             this.designations = res.data.designations;
              this.campuses = res.data.campuses;
               this.semesters = res.data.semesters;
            this.totalStaffs = res.data.staffs.total;
            this.numberOfPages = res.data.staffs.last_page;
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
      this.editedIndex = this.staffs.data.indexOf(item);
      this.editedItem = Object.assign({}, item);
      console.log(this.editedItem);
      this.dialog = true;
    },
   
    updatePD(item){
      const index = this.staffs.data.indexOf(item);
      axios.post('/api/v1/staff/update',{'new_staff': item.user_id, 'staff' : item.id})
      .then(res =>{
        this.staffs = res.data.staffs;
            
             this.user_staff = res.data.user_staff;
            this.totalStaffs = res.data.staffs.total;
            this.numberOfPages = res.data.staffs.last_page;
        this.text = "successfully update"
        this.snackbar = true
      })
      .catch(err => {
        console.error(err); 
      })
      console.log(item);
    },
    deleteItem(item) {
      const index = this.staffs.data.indexOf(item);
      let decide = confirm("Are you sure you want to delete this item?");
      if (decide) {
        axios
          .delete("/api/v1/staffs/" + item.id)
          .then((res) => {
            this.text = "Record Deleted Successfully!"; 
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            this.staffs.data.splice(index, 1);
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
          .put("/api/v1/staffs/" + this.editedItem.id, this.editedItem)
          .then((res) => {
            this.text = "Record Updated Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            Object.assign(this.staffs.data[index], res.data.staff);
            console.log(this.editedItem);
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Updating Record";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;
          });
      } else {
        console.log(this.editItem)
        axios
          .post("/api/v1/staffs", this.editedItem)
          .then((res) => {
            this.text = "Record Added Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            //] this.staffs.data.push(res.data.staff); 
            this.staffs = res.data.staffs
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