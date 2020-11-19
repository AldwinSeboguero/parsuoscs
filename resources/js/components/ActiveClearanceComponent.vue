<template>
 
      <v-sheet style="margin-bottom: 36px">
    <v-data-table
      item-key="name"
      class="elevation-1"
      :loading="loading"
      loading-text="Loading... Please wait"
      :headers="headers"
      :items="clearances"
      sort-by="name"
      color="error"
      hide-default-footer
      style="padding-bottom:60px"
      disable-sort
    >
      <template v-slot:top>
        <v-toolbar flat color="white">
          <div class="overline text-h6">
            Active Clearance
            <span class="font-italic subtitle-2"
              >( Enrollment 2nd Semester A/Y 2020-2021 )</span
            >
          </div>

          <v-spacer></v-spacer>
        </v-toolbar>
        <v-divider></v-divider>
      </template>
      <template v-slot:item.office="{ item }">
        <div class="font-weight-bold">{{item.office}}</div>
      </template>
       <!-- start for the status of clearance -->
      <template v-slot:item.status="{ item }" >
        <v-chip text-color="white" color="success" small v-if="item.status == 2">
          <v-avatar left>
            <v-icon small>mdi-check-circle</v-icon>
          </v-avatar>
            Approved on {{ item.data_approved }}
        </v-chip> 
        <v-chip text-color="white" color="cyan" small v-else-if="item.status == 1">
          <v-avatar left>
            <v-icon small>mdi-information</v-icon>
          </v-avatar>
          Pending for Approval
        </v-chip> 
        <v-chip text-color="white" color="blue-grey lighten-1" small v-else-if="item.status == 0">
           
          No deficiency
        </v-chip> 
        <v-chip text-color="white" color="error lighten-1" small v-else-if="item.status == -1">
          <v-avatar left>
            <v-icon small>mdi-clipboard-alert</v-icon>
          </v-avatar>
          With Deficiency
        </v-chip>
      </template>
        <!-- end for the status of clearance -->

      <template v-slot:item.actions="{ item }">
         <template v-if="item.status == 0">
        <v-btn class="ma-2" color="primary" depressed small
          >Click to Request Clearance</v-btn
        >
         </template>
         <template v-else-if="item.status == -1">
        <v-btn class="ma-2" color="error" depressed small
          >View Deficiency</v-btn
        >
      </template>
      </template>
     
      <template v-slot:no-data>
        <v-btn color="primary" @click="initialize"> Reset </v-btn>
      </template>
      
    </v-data-table>
    <v-divider></v-divider>
     <v-snackbar v-model="snackbar" bottom>
      {{ text }}

      <v-btn color="pink" text @click="snackbar = false"> Close </v-btn>
    </v-snackbar>
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
    headers: [
      { text: "Office", value: "office" },
      { text: "Designee", value: "designee" },
      { text: "Status", value: "status" },
      { text: "Action", value: "actions" },
    ],
    clearances: [
        {
          office: "Student Council",
          designee: "Cas Student Council",
          status: 1,
          data_approved: ''
        },
        {
          office: "Cashier",
          designee: "Mr. Tomas C. Sales, Jr.",
          status: 2,
          data_approved: '',
        },
        {
          office: "Library",
          designee: "Ms. Ann Charmaine B. Visitacion",
          status: -1,
          data_approved: '',
        },
        {
          office: "OSAS",
          designee: "Dr. Gemmah T. Barcillano",
          status: 0,
          data_approved: '',
        },
        {
          office: "Program Director",
          designee: "Kennedy Cuya",
          status: 0,
          data_approved: '',
        },
        {
          office: "Dean",
          designee: "Erlinda Basilla",
          status: 0,
          data_approved: '',
        },
        {
          office: "Registrar Staff",
          designee: "Maria Felicidad O. De Gula",
          status: 0,
          data_approved: '',
        },
      ],
    editedIndex: -1,
    editedItem: {
      id: "",
      token: "",
      name: "",
      student_number: "",
      program: "",
      purpose: "",
    },
    defaultItem: {
      id: "",
      token: "",
      name: "",
      student_number: "",
      program: "",
      purpose: "",
    },
  }),

  computed: {},

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
        .get(`/api/clearancerequests/`)
        .then((res) => {
          //this.clearances = res.data.clearances;
        })
        .catch((err) => {
          if (err.response.status == 401) {
            localStorage.removeItem("token");
            this.$router.push("/login");
          }
        });
        this.clearances[1].data_approved = 'Mon, Nov 09, 2020';
    },

    editItem(item) {
      this.editedIndex = this.clearances.data.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      const index = this.clearances.data.indexOf(item);
      let decide = confirm("Are you sure you want to delete this item?");
      if (decide) {
        axios
          .delete("/api/clearancerequests/" + item.id)
          .then((res) => {
            this.text = "Record Deleted Successfully!";
            this.snackbar = true;
            this.clearancerequests.data.splice(index, 1);
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
      if (this.editedIndex > -1) {
        const index = this.editedIndex;
        axios
          .put("/api/clearancerequests/" + this.editedItem.id, this.editedItem)
          .then((res) => {
            this.text = "Record Updated Successfully!";
            this.snackbar = true;
            Object.assign(
              this.clearances.data[index],
              res.data.clearancerequests
            );
          })
          .catch((err) => {
            console.log(err.response);
            this.text = "Error Updating Record";
            this.snackbar = true;
          });
      }
      this.close();
    },
  },
};
</script>