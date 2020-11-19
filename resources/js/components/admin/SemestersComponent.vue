<template>

  <v-data-table
    item-key="name"
    class="elevation-1"
    :loading = "loading"
    loading-text="Loading... Please wait"
    :headers="headers"
    :items="semesters"
    sort-by="calories" 
    color="error"
  >
   <template v-slot:top>
      <v-toolbar
        flat
        color="white"
      >
        <v-toolbar-title>Semesters</v-toolbar-title>
         
        <v-spacer></v-spacer>
        <v-dialog
          v-model="dialog"
          max-width="500px"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="primary"
              dark
              class="mb-2"
              v-bind="attrs"
              v-on="on"
            >
              New Item
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container fluid>
                <v-row>
                  <v-col
                    cols="12"
                    sm="12"
                    md="12"
                  >
                    <v-text-field
                      v-model="editedItem.name"
                      label="College Name"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="12"
                    md="12"
                  >
                    <v-text-field
                      v-model="editedItem.campus.name"
                      label="Campus"
                    ></v-text-field>
                  </v-col>
                  
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                color="blue darken-1"
                text
                @click="close"
              >
                Cancel
              </v-btn>
              <v-btn
                color="blue darken-1"
                text
                @click="save"
              >
                Save
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="editItem(item)"
      >
        mdi-pencil
      </v-icon> 
    </template>
    <template v-slot:no-data>
      <v-btn
        color="primary"
        @click="initialize"
      >
        Reset
      </v-btn>
    </template> 
  </v-data-table>
</template>
<script>
  export default {
    data: () => ({
      dialog: false,
      loading: false,
      headers: [
        {
          text: 'No',
          align: 'left',
          sortable: false,
          value: 'id',
        },
        { text: 'Code', value: 'code' },
        { text: 'Semester', value: 'semester' }, 
        { text: 'From', value: 'from' }, 
        { text: 'To', value: 'to' }, 
        { text: 'Action', value: 'actions' },
      ],
      semesters: [],
      editedIndex: -1,
      editedItem: {
        name: '',
        campus: '',
        fat: 0,
        carbs: 0,
        protein: 0,
      },
      defaultItem: {
        name: '',
        campus: '',
        fat: 0,
        carbs: 0,
        protein: 0,
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
        this.colleges = [
         
        ],
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
      axios.get('/api/v1/semesters',{})
      .then(res => {
        this.semesters = res.data.semesters
      })
      .catch(err => {
        console.error(err); 
      })

      },
     
      editItem (item) {
        this.editedIndex = this.semesters.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        const index = this.semesters.indexOf(item)
        confirm('Are you sure you want to delete this item?') && this.semesters.splice(index, 1)
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
          Object.assign(this.semesters[this.editedIndex], this.editedItem)
        } else {
          this.semesters.push(this.editedItem)
        }
        this.close()
      },
    },
  }
</script>