<template >
  <v-card class="mx-auto" max-width="1500">
    <v-card-text>
      <div>SIAS ACCOUNT</div>
      <p class="display-1 text--primary">
        {{ account.name }}
      </p>
      <p>{{ account.program }}</p>
      <div class="text--primary">
        User ID: {{ account.user_id ? account.user_id : "Not yet available.."
        }}<br />
        Password:
        {{ account.password ? account.password : "Not yet available.." }}
      </div>
      <div>
        Link: <a href="https://my.parsu.edu.ph">https://my.parsu.edu.ph</a>
      </div>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn text color="teal accent-4" @click="dialog = true">
        How to use it?
      </v-btn>
    </v-card-actions> 
  <v-stepper
    v-model="e6"
    vertical
    v-if="dialog == true"
  >
    <v-stepper-step
      :complete="e6 > 1"
      step="1"
    >
      Step 1
      <!-- <small>Summarize if needed</small> -->
    </v-stepper-step>

    <v-stepper-content step="1">
       <v-layout justify-center align-center>
              <v-flex shrink>
                <div>
                  Go to this link
                  <a href="https://my.parsu.edu.ph">https://my.parsu.edu.ph</a>
                  and select which campus you are enrolled.
                </div>
                <v-card
                  class="mb-12" 
                  max-height="1000px"
                  max-width="1500px"
                >
                  <v-img
                    max-width="800"
                    src="images/enrollment/step1.png"
                  ></v-img>
                </v-card>
              </v-flex>
            </v-layout>
            <v-btn color="primary" @click="e6 = 2"> Next </v-btn>

            <v-btn @click="dialog = false" text> Close </v-btn>
    </v-stepper-content>

    <v-stepper-step
      :complete="e6 > 2"
      step="2"
    >
      Step 2
    </v-stepper-step>

    <v-stepper-content step="2">
      <v-layout justify-center align-center>
              <v-flex shrink>
                <div>
                  Then Select Student
                </div>
                <v-card
                  class="mb-12" 
                  max-height="1000px"
                  max-width="1500px"
                >
                  <v-img
                    max-width="800"
                    src="images/enrollment/step2.png"
                  ></v-img>
                </v-card>
              </v-flex>
            </v-layout>
            <v-btn color="primary" @click="e6 = 3"> Next </v-btn>
            <v-btn color="primary" @click="e6 = 1"> Back </v-btn>

            <v-btn @click="dialog = false" text> Close </v-btn>
    </v-stepper-content>

    <v-stepper-step
      :complete="e6 > 3"
      step="3"
    >
      Step 3
    </v-stepper-step>

    <v-stepper-content step="3">
      <v-layout justify-center align-center>
              <v-flex shrink>
                <div>
                  Enter the given user id and password then click login
                </div>
                <v-card
                  class="mb-12" 
                  max-height="1000px"
                  max-width="1500px"
                >
                  <v-img
                    max-width="800"
                    src="images/enrollment/step3.png"
                  ></v-img>
                </v-card>
              </v-flex>
            </v-layout>
            <v-btn color="primary" @click="e6 = 4"> Next </v-btn>
            <v-btn color="primary" @click="e6 = 2"> Back </v-btn>
            <v-btn @click="dialog = false" text> Close </v-btn>
    </v-stepper-content>

    <v-stepper-step :complete="e6 > 4"  step="4" 
    >
      Step 4
    </v-stepper-step>
    <v-stepper-content step="4">
      <v-layout justify-center align-center>
              <v-flex shrink>
                <div>
                  <i>Note:</i> If Regular Student Please Skip this step. <br>

                </div>
                <v-card
                  class="mb-12" 
                  max-height="1000px"
                  max-width="1500px"
                >
                  <v-img
                    max-width="800"
                    src="images/enrollment/step4.png"
                  ></v-img>
                </v-card>
              </v-flex>
            </v-layout>
            <v-btn color="primary" @click="e6 = 5"> Next </v-btn>
            <v-btn color="primary" @click="e6 = 3"> Back </v-btn>
            <v-btn @click="dialog = false" text> Close </v-btn>
    </v-stepper-content>

    <v-stepper-step :complete="e6 > 5" step="5">
        Step 5
    </v-stepper-step>
    <v-stepper-content step="5">
   <v-layout justify-center align-center>
              <v-flex shrink>
                <div>
                  <i>Note:</i> If Regular Student Please Skip this step. <br>

                  Click Auto Enlist to retrieve available subjects to be taken.

                </div>
                <v-card
                  class="mb-12" 
                  max-height="1000px"
                  max-width="1500px"
                >
                  <v-img
                    max-width="800"
                    src="images/enrollment/step5.png"
                  ></v-img>
                </v-card>
              </v-flex>
            </v-layout>
            <v-btn color="primary" @click="e6 = 6"> Next </v-btn>
            <v-btn color="primary" @click="e6 = 4"> Back </v-btn>
            <v-btn @click="dialog = false" text> Close </v-btn>
    </v-stepper-content>

    <v-stepper-step :complete="e6 > 6" step="6">
      Step 6
    </v-stepper-step>
    <v-stepper-content step="6">
       <v-layout justify-center align-center>
              <v-flex shrink>
                <div>
                  <i>Note:</i> If Regular Student Please Skip this step. <br>

                  To delist subject select the subject you wish to delist/remove then Click Delist

                </div>
                <v-card
                  class="mb-12" 
                  max-height="1000px"
                  max-width="1500px"
                >
                  <v-img
                    max-width="800"
                    src="images/enrollment/step6.png"
                  ></v-img>
                </v-card>
              </v-flex>
            </v-layout>
            <v-btn color="primary" @click="e6 = 7"> Next </v-btn>
            <v-btn color="primary" @click="e6 = 5"> Back </v-btn>
            <v-btn @click="dialog = false" text> Close </v-btn>
    </v-stepper-content>

    <v-stepper-step :complete="e6 > 7" step="7">
      Step 7
    </v-stepper-step>
    <v-stepper-content step="7">
     <v-layout justify-center align-center>
              <v-flex shrink>
                <div>
                  
                </div>
                <v-card
                  class="mb-12" 
                  max-height="1000px"
                  max-width="1500px"
                >
                  <v-img
                    max-width="800"
                    src="images/enrollment/step7.png"
                  ></v-img>
                </v-card>
              </v-flex>
            </v-layout>
            <v-btn color="primary" @click="e6 = 8"> Next </v-btn>
            <v-btn color="primary" @click="e6 = 6"> Back </v-btn>
            <v-btn @click="dialog = false" text> Close </v-btn>
    </v-stepper-content>

    <v-stepper-step :complete="e6 > 8" step="8">
     Step 8
    </v-stepper-step>
    <v-stepper-content step="8">
     <v-layout justify-center align-center>
              <v-flex shrink>
                <div>
                 
                </div>
                <v-card
                  class="mb-12" 
                  max-height="1000px"
                  max-width="1500px"
                >
                  <v-img
                    max-width="800"
                    src="images/enrollment/step8.png"
                  ></v-img>
                </v-card>
              </v-flex>
            </v-layout>
            <v-btn color="primary" @click="e6 = 9"> Next </v-btn>
            <v-btn color="primary" @click="e6 = 7"> Back </v-btn>
            <v-btn @click="dialog = false" text> Close </v-btn>
    </v-stepper-content>

    <v-stepper-step :complete="e6 > 9" step="9">
      Step 9
    </v-stepper-step>
    <v-stepper-content step="9">
     <v-layout justify-center align-center>
              <v-flex shrink>
                <div>
                
                </div>
                <v-card
                  class="mb-12" 
                  max-height="1000px"
                  max-width="1500px"
                >
                  <v-img
                    max-width="800"
                    src="images/enrollment/step9.png"
                  ></v-img>
                </v-card>
              </v-flex>
            </v-layout>
            <v-btn color="primary" @click="e6 = 10"> Next </v-btn>
            <v-btn color="primary" @click="e6 = 8"> Back </v-btn>
            <v-btn @click="dialog = false" text> Close </v-btn>
    </v-stepper-content>

    <v-stepper-step :complete="e6 > 10" step="10">
      Step 10
    </v-stepper-step>
    <v-stepper-content step="10">
      <v-layout justify-center align-center>
              <v-flex shrink>
                <div>
                 
                </div>
                <v-card
                  class="mb-12" 
                  max-height="1000px"
                  max-width="1500px"
                >
                  <v-img
                    max-width="800"
                    src="images/enrollment/step10.png"
                  ></v-img>
                </v-card>
              </v-flex>
            </v-layout>
            <v-btn color="primary" @click="e6 = 11"> Next </v-btn>
            <v-btn color="primary" @click="e6 = 9"> Back </v-btn>
            <v-btn @click="dialog = false" text> Close </v-btn>
    </v-stepper-content>

    <v-stepper-step :complete="e6 > 11" step="11">
      Step 11
    </v-stepper-step>
    <v-stepper-content step="11">
     <v-layout justify-center align-center>
              <v-flex shrink>
                <div>
                
                </div>
                <v-card
                  class="mb-12" 
                  max-height="1000px"
                  max-width="1500px"
                >
                  <v-img
                    max-width="800"
                    src="images/enrollment/step11.png"
                  ></v-img>
                </v-card>
              </v-flex>
            </v-layout>
            <v-btn color="primary" @click="e6 = 12"> Next </v-btn>
            <v-btn color="primary" @click="e6 = 10"> Back </v-btn>
            <v-btn @click="dialog = false" text> Close </v-btn>
    </v-stepper-content>

    <v-stepper-step :complete="e6 > 12" step="12">
      Step 12
    </v-stepper-step>
    <v-stepper-content step="12">
       <div>
                
            <v-btn color="primary" @click="e6 = 13"> Next </v-btn>
            <v-btn color="primary" @click="e6 = 11"> Back </v-btn>
            <v-btn @click="dialog = false" text> Close </v-btn>
            </div>
      <v-layout justify-center align-center>
              <v-flex shrink>
                <div>
                 
                </div>
                <v-card
                  class="mb-12"  
                  max-height="1000px"
                  max-width="1500px"
                >
                  <v-img
                    max-width="800"
                    src="images/enrollment/step12.png"
                  ></v-img>
                </v-card>
              </v-flex>
            
            </v-layout>
           
    </v-stepper-content>

    <v-stepper-step step="13">
      Step 13
    </v-stepper-step>
    <v-stepper-content step="13">
      <div>
              
            <v-btn color="primary" @click="e6 = 12"> Back </v-btn>
            <v-btn @click="dialog = false" text> Close </v-btn>
            </div>
      <v-layout justify-center align-center>
              <v-flex shrink>
                <div>
                 
                </div>
                <v-card
                  class="mb-12" 
                  max-height="1000px"
                  max-width="1500px"
                >
                  <v-img
                    max-width="800"
                    src="images/enrollment/step13.png"
                  ></v-img>
                </v-card>
              </v-flex>
              
            </v-layout>
            
    </v-stepper-content>
  </v-stepper> 

  </v-card>
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
    e1: 1,
    e6:1,
    dialog: false,
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
    account: {
      name: "",
      program: "",
      user_id: "",
      password: "",
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
      axios.get(`/api/v1/mysiasaccounts`).then((response) => {
        //Then injecting the result to datatable parameters.
        this.account = response.data.account;
      });
    },
  },
};
</script>