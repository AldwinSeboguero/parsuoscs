<template >
<v-row>
  <v-col cols="12" md="4">
    <v-card
    class="mx-auto"
    max-width="400"
  >
    <v-list-item two-line >
      <v-list-item-content>
        <v-list-item-title class="headline">
          Total Completed Clearace
        </v-list-item-title>
        <v-list-item-subtitle>As of September 16, 12:30 PM</v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>

    <v-card-text>
      <v-row align="center">
        <v-col
          class="display-3"
          cols="6"
        >
          <span class="black--text">4441</span>
        </v-col>
        <v-col cols="6">
        <v-sparkline
          :value="value"
          :gradient="gradient"
          :smooth="radius || false"
          :padding="padding"
          :line-width="width"
          :stroke-linecap="lineCap"
          :gradient-direction="gradientDirection"
          :fill="fill"
          :type="type"
          :auto-line-width="autoLineWidth"
          auto-draw
        ></v-sparkline>
        </v-col>
      </v-row>
    </v-card-text>
 

   

    <v-list class="transparent">
      <v-list-item
        v-for="item in stats"
        :key="item.name"
      >
        <v-list-item-title>{{ item.name }}</v-list-item-title>
 

        <v-list-item-subtitle class="text-right">
          {{ item.total }}
        </v-list-item-subtitle>
      </v-list-item>
    </v-list>

    <v-divider></v-divider>

    <v-card-actions>
      <v-btn text >
        Full Report
      </v-btn>
    </v-card-actions>
  </v-card>
  </v-col>
  <v-col cols="12" md="8">
      <v-data-table
    item-key="name"
    class="elevation-1"
    :loading = "loading"
    loading-text="Loading... Please wait"
    :headers="headers"
    :items="colleges"
    sort-by="calories" 
    color="error"
    
  >
   <template v-slot:top>
      <v-toolbar
        flat
        color="primary accent-4"
        class="white--text"
      >
        <v-toolbar-title>Activity Logs</v-toolbar-title>
         
        <v-spacer></v-spacer>
        <v-dialog
          v-model="dialog"
          max-width="500px"
        >
          <template v-slot:activator="{ on, attrs }">
             
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
  </v-data-table>
    
  </v-col>
</v-row>
</template>
<script>
const gradients = [
    ['#222'],
    ['#42b3f4'],
    ['red', 'orange', 'yellow'],
    ['purple', 'violet'],
    ['#42b3f4', '#42b3f4', '#42b3f4'],
    ['#42b3f4', '#42b3f4', '#42b3f4'],
  ]
  const exhale = ms =>
    new Promise(resolve => setTimeout(resolve, ms))

  export default {
    data: () => ({
      width: 2,
      radius: 10,
      padding: 8,
      lineCap: 'round',
      gradient: gradients[5],
      value: [0, 2, 5, 9, 5, 10, 3, 5, 0, 0, 1, 8, 2, 9, 0],
      gradientDirection: 'top',
      gradients,
      fill: false,
      type: 'trend',
      autoLineWidth: false,
      checking: false,
      heartbeats: [],
      stats: [
        {name: 'Total Students', total: 5421},
        {name: 'Active Account(s)', total: 5027},
        {name: 'Pending Request(s)', total: 475},
        {name: 'Total Clearance Request(s)', total: 32843}, 
      ],
      dialog: false,
      loading: false,
      headers: [
        {
          text: 'No',
          align: 'left',
          sortable: false,
          value: 'id',
        },
        { text: 'Activity', value: 'name' }, 
        { text: 'Created At', value: 'created_at' },  
      ],
      colleges: [],
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

     

    created () {
      this.takePulse(false)
    },

    methods: {
      heartbeat () {
        return Math.ceil(Math.random() * (120 - 80) + 80)
      },
      async takePulse (inhale = true) {
        this.checking = true

        inhale && await exhale(1000)

        this.heartbeats = Array.from({ length: 20 }, this.heartbeat)

        this.checking = false
      },
    },
     computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
      },
      avg () {
        // const sum = this.heartbeats.reduce((acc, cur) => acc + cur, 0)
        // const length = this.heartbeats.length

        // if (!sum && !length) return 0

        // return Math.ceil(sum / length)
        return 5421
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
      axios.get('/api/colleges',{})
      .then(res => {
        this.colleges = res.data.colleges
      })
      .catch(err => {
        console.error(err); 
      })

      },
     
      editItem (item) {
        this.editedIndex = this.colleges.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        const index = this.colleges.indexOf(item)
        confirm('Are you sure you want to delete this item?') && this.colleges.splice(index, 1)
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
          Object.assign(this.colleges[this.editedIndex], this.editedItem)
        } else {
          this.colleges.push(this.editedItem)
        }
        this.close()
      },
    },
  }
</script>