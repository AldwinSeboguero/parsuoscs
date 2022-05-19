<template >
  <v-row>
    <!-- activated account -->
     <v-col lg="6" sm="6" cols="12">
          <v-card class="mx-1 mb-1">
            <v-card-title class="pa-4 pb-0">
              <v-list-item two-line>
                <v-list-item-content>
                  <v-list-item-title class="headline">
                     Completed Clearance
                  </v-list-item-title>
                  <v-list-item-subtitle>{{semester}}</v-list-item-subtitle>
                </v-list-item-content>
                 <!-- <v-spacer></v-spacer>
              <v-menu>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon color="textColor">mdi-dots-vertical</v-icon>
                  </v-btn>
                </template>
                <v-list>
                  <v-list-item
                   
                  >
                    <v-list-item-title></v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu> -->
              </v-list-item>
             
            </v-card-title>
            <v-card-text class="pa-4 pt-0">
              <v-row no-gutters>
                <v-col cols="6" class="my-auto">
                  <span class="ml-4" style="font-size: 42px"
                    >{{ completed }} 
                  </span>
                </v-col>
                <v-col cols="6" class="pr-8"> 
                  <v-sparkline
                  class="pb-2"
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
              <v-row no-gutters class="justify-space-between">
                <v-col cols="12" lg="3" class="text-center ma-2">
                  <div class="subtext">
                    {{ pendingRequest }}  <v-icon color="warning"> mdi-account-clock</v-icon>
                  </div>
                  <div class="subtext-index">Pending Request(s)</div>
                </v-col>
                <v-col cols="12" lg="3" class="text-center ma-2">
                  <div class="subtext">
                    {{ approvedRequest }} <v-icon color="success"> mdi-check-decagram</v-icon>
                  </div>
                  <div class="subtext-index">Approved Request(s)</div>
                </v-col>
                <v-col cols="12" lg="3" class="text-center ma-2">
                  <div class="subtext">
                    {{ totalClearanceRequest}} <v-icon color="primary"> mdi-list-status</v-icon>
                  </div>
                  <div class="subtext-index">Total Request(s)</div>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
      </v-col>
    <v-col lg="3" sm="3" cols="12">
          <v-card class="mx-auto " >
            <v-card-title class="pa-4 pb-0">
              <v-list-item two-line>
                <v-list-item-content>
                  <v-list-item-title class="headline">
                     Active Account(s)
                  </v-list-item-title>
                  <v-list-item-subtitle>{{semester}}</v-list-item-subtitle>
                </v-list-item-content>
                 <!-- <v-spacer></v-spacer>
              <v-menu>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon color="textColor">mdi-dots-vertical</v-icon>
                  </v-btn>
                </template>
                <v-list>
                  <v-list-item
                   
                  >
                    <v-list-item-title></v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu> -->
              </v-list-item>
             
            </v-card-title>
            <v-card-text class="pa-4 pt-0">
              <v-row no-gutters>
                <v-col cols="12" class="my-auto text-center">
                  <span  style="font-size: 42px" class="success--text"
                    >{{ totalActivatedAccount }} 
                  </span>
                </v-col>
                
              </v-row>
               
            </v-card-text>
          </v-card>
      </v-col>
      <v-col lg="3" sm="3" cols="12">
          <v-card class="mx-auto " >
            <v-card-title class="pa-4 pb-0">
              <v-list-item two-line>
                <v-list-item-content>
                  <v-list-item-title class="headline">
                     Total Student(s)
                  </v-list-item-title>
                  <v-list-item-subtitle>{{semester}}</v-list-item-subtitle>
                </v-list-item-content>
                 <!-- <v-spacer></v-spacer>
              <v-menu>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon color="textColor">mdi-dots-vertical</v-icon>
                  </v-btn>
                </template>
                <v-list>
                  <v-list-item
                   
                  >
                    <v-list-item-title></v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu> -->
              </v-list-item>
             
            </v-card-title>
            <v-card-text class="pa-4 pt-0">
              <v-row no-gutters>
                <v-col cols="12" class="my-auto text-center">
                  <span  style="font-size: 42px" class="success--text"
                    >{{ totalStudent }} 
                  </span>
                </v-col>
                
              </v-row>
               
            </v-card-text>
          </v-card>
      </v-col>
 
    <v-col cols="12" md="12">
      <v-data-table
        item-key="name"
        class="elevation-1"
        :loading="loading"
        loading-text="Loading... Please wait"
        :headers="headers"
        :items="offices"
        sort-by="calories"
        color="error"
      >
        <template v-slot:top>
          <v-toolbar flat color="blue-grey darken-4" class="white--text" height="40">
            <v-toolbar-title>
              <v-icon class="mr-1 white--text" small> mdi-city </v-icon>
              <span class="caption">Clearance Request Status by Office</span></v-toolbar-title
            >

            <v-spacer></v-spacer>
            
          </v-toolbar>
        </template>
      </v-data-table>
    </v-col>
  </v-row>
</template>
<script>

const gradients = [
  ["#222"],
  ["#42b3f4"],
  ["red", "orange", "yellow"],
  ["purple", "violet"],
  ["#42b3f4", "#42b3f4", "#42b3f4"],
  ["#42b3f4", "#42b3f4", "#42b3f4"],
];
const exhale = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

export default {
  
  data: () => ({
    apexBar1: {
    options: {
      chart: {
        id: 'chartFirst',
        toolbar: {
          show: false
        },
      },
     
      plotOptions: {
        bar: {
          columnWidth: "30%",
          distributed: true,
          endingShape: "rounded",
          startingShape: "rounded",
        },
      },
      grid: {
        xaxis: {
          lines: {
            show: false,
          }
        },
        yaxis: {
          lines: {
            show: false,
          },
        }
      },
      dataLabels: {
        enabled: false,
        dropShadow: {
          enable: false
        }
      },
      legend: {
        show: false
      },
      xaxis: {
        axisBorder: {
          show: false,
        },
        axisTicks: {
          show: false,
        },
        labels: {
          show: false,
        }
      },
      yaxis: {
        labels: {
          show: false
        }
      }
    },
    series: [{
      data: [70, 63, 84, 79, 70, 65, 80]
    }],
  },
    completed: 0,
    totalStudent: 0,
    approvedRequest: 0,
    pendingRequest: 0,
    totalClearanceRequest: 0,
    totalActivatedAccount: 0,
    semester: '',
    width: 2,
    radius: 10,
    padding: 8,
    lineCap: "round",
    gradient: gradients[5],
    value: [0, 2, 5, 9, 5, 10, 3, 5, 0, 0, 1, 8, 2, 9, 0],
    gradientDirection: "top",
    gradients,
    fill: false,
    type: "trend",
    autoLineWidth: false,
    checking: false,
    heartbeats: [],
    stats: [],
    dialog: false,
    loading: false,
    headers: [
      {
        text: "No",
        align: "left",
        sortable: false,
        value: "no",
      },
      { text: "Office", value: "office" },
      { text: "Total Approved", value: "approved" },
      { text: "Pending Request", value: "pending" },
    ],
    offices: [
      { no: 1, office: "Cashier(Goa Campus)", approved: 3308, pending: 16 },
      { no: 2, office: "Library(Goa Campus)", approved: 3292, pending: 0 },
      { no: 3, office: "OSAS(Goa Campus)", approved: 3291, pending: 0 },
      { no: 4, office: "Registrar(Goa Campus)", approved: 3048, pending: 112 },
      { no: 5, office: "Registrar(Goa Campus)", approved: 3048, pending: 112 },
    ],
    editedIndex: -1,
    editedItem: {
      name: "",
      campus: "",
      fat: 0,
      carbs: 0,
      protein: 0,
    },
    defaultItem: {
      name: "",
      campus: "",
      fat: 0,
      carbs: 0,
      protein: 0,
    },
  }),

  created() {
    this.takePulse(false);
  },

  methods: {
    heartbeat() {
      return Math.ceil(Math.random() * (120 - 80) + 80);
    },
    async takePulse(inhale = true) {
      this.checking = true;

      inhale && (await exhale(1000));

      this.heartbeats = Array.from({ length: 20 }, this.heartbeat);

      this.checking = false;
    },
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Item" : "Edit Item";
    },
    avg() {
      // const sum = this.heartbeats.reduce((acc, cur) => acc + cur, 0)
      // const length = this.heartbeats.length

      // if (!sum && !length) return 0

      // return Math.ceil(sum / length)
      return 5421;
    },
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
  },

  created() {
    this.initialize();
  },

  methods: {
    initialize() {
      (this.colleges = []),
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
      axios
        .get("/api/v1/colleges", {})
        .then((res) => {
          // this.colleges = res.data.colleges
        })
        .catch((err) => {
          console.error(err);
        });
      axios
        .get("/api/v1/admindashboard", {})
        .then((res) => {
          console.dir(res.completed);
          this.completed = res.data.completed;
          this.totalStudent = res.data.totalStudent;
          this.approvedRequest = res.data.approvedRequest;
          this.pendingRequest = res.data.pendingRequest;
          this.totalClearanceRequest = res.data.totalClearanceRequest;
          this.semester = res.data.semester;
          this.totalActivatedAccount = res.data.totalActivatedAccount;
          this.totalStudent  = res.data.totalStudent;
          this.stats = [
            { name: "Total Students", total: this.totalStudent },
            {
              name: "Active Account(s)",
              total: res.data.totalActivatedAccount,
            },
            { name: "Pending Request(s)", total: res.data.pendingRequest },
            { name: "Approved Request(s)", total: res.data.approvedRequest },
            {
              name: "Total Clearance Request(s)",
              total: res.data.totalClearanceRequest,
            },
          ];
        })
        .catch((err) => {
          console.error(err);
        });
    },
  },
};
</script>