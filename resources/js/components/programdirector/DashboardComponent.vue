<template >
  <v-row>
    <v-col cols="12" md="4">
      <v-card class="mx-auto" >
        <v-list-item two-line>
          <v-list-item-content>
            <v-list-item-title class="headline">
              Total Completed Clearance
            </v-list-item-title>
            <v-list-item-subtitle>As of {{ new Date() }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>

        <v-card-text>
          <v-row align="center">
            <v-col class="display-3" cols="6">
              <span class="black--text">{{ completed }}</span>
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
          <v-list-item v-for="item in stats" :key="item.name">
            <v-list-item-title class="text-wrap">{{
              item.name
            }}</v-list-item-title>

            <v-list-item-subtitle class="text-right">
              {{ item.total }}
            </v-list-item-subtitle>
          </v-list-item>
        </v-list>

        <v-divider></v-divider>

        <v-card-actions>
          <v-btn text> Full Report </v-btn>
        </v-card-actions>
      </v-card>
    </v-col>
    <v-col cols="12" md="8">
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
          <v-toolbar flat color="primary accent-4" class="white--text">
            <v-toolbar-title>
              <v-icon class="mr-1 white--text" small> mdi-city </v-icon>
              <span>Clearance Request Status by Office</span></v-toolbar-title
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
    completed: 0,
    totalStudent: 0,
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