<template>
<v-sheet >
<v-row >
      <v-col cols="12" lg="5" class="mt-2">
       <Breadcrumbs class="mb-4"/>

      </v-col>
    </v-row>
   <v-card elevation="0">
    <v-container class="grey lighten-5" fluid>
     <v-row wrap>
       <v-col cols="12" lg="4">
           <v-card >
         <v-card-text style="padding-bottom:10">
                      <h1
                        class="title desplay-2 black--text text--accent3"
                       
                      >
                      <v-icon class="ma-1 mb-2">mdi-account-plus-outline</v-icon>
                        Add Campus
      
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
                <v-text-field 
                  label="Campus Name" 
                  name="campus" 
                  v-model="editedItem.name"
                  type="text"
                  color="teal accent-4" 
                  dense  
                  class="text-sm-h6 mr-2 ml-2 mb-2 mb-1"
                
                />
                <v-text-field 
                  label="Short Name" 
                  name="short_name" 
                  v-model="editedItem.short_name"
                  type="text"
                  color="teal accent-4" 
                  dense  
                  class="text-sm-h6 mr-2 ml-2 mb-2 mb-1"
                
                />
                

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
              :loading = "loading"
              loading-text="Loading... Please wait"
              :headers="headers"
              :items="campuses" 
               :items-per-page="7" 
              color="error"
            > 
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
      dialog: false,
      loading: false,
      headers: [ 
        { text: 'Campus Name', value: 'name' }, 
        { text: 'Action', align: 'right',  value: 'actions' },
      ],
      campuses: [],
      editedIndex: -1,
      campus: '', 
      snackbar: false,
      selected: [],
      text: "",
      success: "",
      error: "", 
      snackbarColor:"",
      editedItem: {
        id:'',
        name: '',
        short_name: '',
        campus: '', 
      },
      defaultItem: {
        id:'',
        name: '',
        short_name: '',
        campus: '', 
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
    },

    created () {
      this.initialize()
    },

    methods: {
      initialize () {
        
        axios.interceptors.request.use(config => {
        this.loading = true;
        return config;
        },error => {
        this.loading = false;
        return Promise.reject(error);
        });
        
        axios.interceptors.response.use(response => {
        this.loading = false;
        return response;
        },error => {
        this.loading = false;
        return Promise.reject(error);
        });
      axios.get('/api/v1/campuses',{})
      .then(res => {
        this.campuses = res.data.campuses
      })
      .catch(err => {
        console.error(err); 
      })

      },
     
      editItem (item) {
        this.editedIndex = this.campuses.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        const index = this.campuses.indexOf(item)
        console.log(index)
         let decide = confirm("Are you sure you want to delete this item?");
        if (decide) {
        axios
          .delete("/api/v1/campuses/" + item.id)
          .then(res => {
            this.text = "Record Deleted Successfully!"; 
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
          console.log(this.campuses.splice(index,1))
          })
          .catch((err) => {
            console.log("err.response");
            this.text = "Error Deleting Record!";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;

          });
      }
      this.close();
      },

      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      save () {
       if (this.editedIndex > -1) {
        const index = this.editedIndex;
        console.log("Temp Data "+ this.tempItem)
        
           axios
          .put("/api/v1/campuses/" + this.editedItem.id, this.editedItem)
          .then((res) => {
            this.text = "Record Updated Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            Object.assign(this.campuses[index], res.data.campus);
            console.log(this.editedItem);
            this.close();
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Updating Record";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;
          });
       
          
      } else {
        console.log(this.editedItem)

        axios
          .post("/api/v1/campuses", this.editedItem)
          .then((res) => {
            this.text = "Record Added Successfully!";
            this.snackbarColor ="primary darken-1";
            this.snackbar = true;
            // this.students.data.push(res.data.student); 
        
            this.campuses = res.data.campuses.data
            this.close();
          })
          .catch((err) => {
            console.dir(err);
            this.text = "Error Inserting Record";
            this.snackbarColor ="error darken-1";
            this.snackbar = true;
          });
          
      } 
       
      
      
      },
    },
  }
</script>