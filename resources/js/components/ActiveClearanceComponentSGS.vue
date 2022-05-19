<template>

  <v-sheet style="margin-bottom: 36px" v-if="purposeVal">
       <v-alert
      border="right"
      colored-border
      type="info"
      elevation="0"
      v-if="!submittedClearance
      && ( 
                   clearance.cashier==true
                  && clearance.library==true
                  && clearance.osas==true
                  && clearance.program_director==true
                  && clearance.college_deaan==true
                  && clearance.registrar==true)
                  || clearance.adviser==true
                  && clearance.principal==true
      "
    >
       <b><i>Note:</i></b> To complete the proccess. Please click the submit button to send your clearance to the Office of the Registrar.
    </v-alert>

       <v-alert
      border="right"
      colored-border
      type="success"
      elevation="0"
      v-if="submittedClearance"
    >
      <b><i>Note:</i></b> You have successfully submitted your clearance to the Office of the Registrar (<i> {{
                  submittedClearance.created_at | moment("from", "now")
                }} </i>).
 </v-alert>
          <template >
           <span class="font-italic subtitle-2">Status: </span> 
                <v-chip   text-color="white" style="margin-left: 4px;"
                  color="success"
                  x-small
                  v-if="submittedClearance"
                >
                 Completed
                </v-chip> 
                  <v-chip   text-color="white" style="margin-left: 4px;"
                  :color="stad ? 'info' : 'red'"
                  x-small
                  v-else
                >
                 {{stad ? "On-Going" : "Closed"}}
                </v-chip> 
                 <v-btn @click="generatePDF" class="elevation-0 error lighten-1" small
                   v-if="( clearance.cashier==true
                  && clearance.library==true
                  && clearance.osas==true
                  && clearance.program_director==true
                  && clearance.college_deaan==true
                  && clearance.registrar==true)
                  || clearance.adviser==true
                  && clearance.principal==true
                  "
                 ><v-icon>mdi-file-pdf</v-icon>Generate Clearance</v-btn>
                <v-btn @click="submitClearance" class="elevation-0 success lighten-1" small
                  v-if="!submittedClearance
                  && ( clearance.cashier==true
                  && clearance.library==true
                  && clearance.osas==true
                  && clearance.program_director==true
                  && clearance.college_deaan==true
                  && clearance.registrar==true)
                  || clearance.adviser==true
                  && clearance.principal==true"
                ><v-icon>mdi-forward</v-icon>Submit to the Registrar</v-btn>
      
          </template>
          
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
      style="padding-bottom: 60px;margin-top:20px;"
      disable-sort
    >
      <template v-slot:top>
        <v-toolbar flat color="white">
          <div class="overline text-h6">
            Active Clearance
            <span class="font-italic subtitle-2"
              >( {{ activeClearancePurpose }} )</span
            >
          </div>

          <v-spacer></v-spacer>
           
         
        
        </v-toolbar>
        <v-divider></v-divider>
      </template>
      <template v-slot:item.office="{ item }">
        <div class="font-weight-bold">{{ item.office }}</div>
      </template>
      <!-- start for the status of clearance -->
      <template v-slot:item.status="{ item }">
        <template v-if="item.status == 1">
          <v-chip text-color="white" color="success" small>
            <v-avatar left>
              <v-icon small>mdi-check-circle</v-icon>
            </v-avatar>
            Approved on {{ item.data_approved }}
          </v-chip>
        </template>
        <template v-else-if="item.status == 0 || item.status == 2 && item.designee">
          <template v-if="item.clearanceRequestCount == 1">
            <v-chip text-color="white" color="cyan" small v-if="item.status != 2">
              <v-avatar left>
                <v-icon small>mdi-information</v-icon>
              </v-avatar>
              Pending for Approval
            </v-chip>
            <template v-if="item.deficiencyCount != 0">
              <v-chip
                text-color="white"
                color="error lighten-1"
                small
                @click="viewDeficiency(item)"
              >
                <v-avatar left>
                  <v-icon small>mdi-clipboard-alert</v-icon>
                </v-avatar>
                With Deficiency
              </v-chip>
            </template>
          </template>
          <template v-if="item.clearanceRequestCount == 0">
            <template v-if="item.deficiencyCount == 0">
              <v-chip text-color="white" color="blue-grey lighten-1" small>
                No deficiency
              </v-chip>
            </template>
            <template v-else>
              <v-chip
                text-color="white"
                color="error lighten-1"
                small
                @click="deficiencydialog = true"
              >
                <v-avatar left>
                  <v-icon small>mdi-clipboard-alert</v-icon>
                </v-avatar>
                With Deficiency
              </v-chip>
            </template>
          </template>
        </template>
      </template>

       <template v-slot:item.actions="{ item }" v-if="stad">
        <template v-if="item.status == 0 && item.designee">
          <template v-if="item.clearanceRequestCount == 0">
            <v-btn
              class="ma-2"
              color="primary"
              depressed
              small
              @click="sendRequest(item)"
         
              >Click to Request Clearance</v-btn
            >
             
          </template>
          <template v-else>
            <template v-if="item.deficiencyCount != 0">
              <v-btn
                class="ma-2"
                color="error"
                depressed
                small
                @click="viewDeficiency(item)"
                >View Deficiency</v-btn
              >
            </template>
          </template>
        </template>
      
      </template>
      <template v-slot:item.actions="{ item }"  v-else>
          <v-btn
              class="ma-2"
              color="red"
              depressed
              small 
              text 
              >Request for Clearance is Closed</v-btn
            >
      </template>

      <!-- <template v-slot:no-data>
        <v-btn color="primary" @click="initialize"> Reset </v-btn>
      </template>
       -->
    </v-data-table>
    <v-divider></v-divider>
   

    <v-dialog v-model="deficiencydialog" scrollable max-width="500px">
      <v-card>
        <v-card-title class="headline"
          >Deficiency List
          <span class="font-italic subtitle-2">
            (From: {{ deficiency_designee }})</span
          >
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text style="height: 300px">
          <v-list subheader two-line>
            <v-list-item
              v-for="deficiency in deficiencies"
              :key="deficiency.title"
            >
              <v-list-item-avatar>
                <v-icon class="success lighten-1" dark v-if="deficiency.completed"> mdi-check </v-icon>
                <v-icon class="error lighten-1" dark v-else> mdi-alert </v-icon>                
              </v-list-item-avatar>


              <v-list-item-content>
                <v-list-item-title class="small" small>{{
                  deficiency.title
                }}</v-list-item-title>

                <v-list-item-subtitle small class="subtitle-2"
                  >*{{ deficiency.note }}</v-list-item-subtitle
                >
                <v-list-item-subtitle> </v-list-item-subtitle>
              </v-list-item-content>
              <v-list-item-action>
                <v-list-item-action-text>{{
                  deficiency.updated_at | moment("from", "now")
                }}</v-list-item-action-text>

                <v-chip
                  text-color="white"
                  color="success"
                  x-small
                  v-if="deficiency.completed"
                >
                  Completed
                </v-chip>
                <v-chip text-color="white" color="warning" x-small v-else>
                  Pending
                </v-chip>
              </v-list-item-action>
            </v-list-item>
            <v-divider></v-divider>
          </v-list>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="deficiencydialog = false"
            >Close</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-snackbar v-model="snackbar" bottom>
      {{ text }}

      <v-btn color="pink" text @click="snackbar = false"> Close </v-btn>
    </v-snackbar>

    <v-dialog v-model="dialog" persistent max-width="300">
      <v-card>
        <v-card-title class="headline">
          Send this Clearance Request?
        </v-card-title>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="dialog = false">
            Cancel
          </v-btn>
          <v-btn color="green darken-1" text @click="approve()">
            Approve
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    
  </v-sheet>
    <v-container  v-else-if="purposeVal == 0">
    
    <v-row align="center "   justify="center" class="pa-12">  

       <v-alert
      icon="mdi-shield-lock-outline"
      prominent
      text
      type="info"
    >
      <v-row align="center" class="pa-3">
        <v-col class="grow">
         <strong>No purpose detected!</strong> Go to settings to setup your clearance purpose.
    
        </v-col>
        <v-col class="shrink">
          <v-btn class="primary" href="/#/sgs/student/settings" elevation="0">Redirect to Settings</v-btn>
        </v-col>
      </v-row>
    </v-alert>
     </v-row>
  </v-container>
  <v-container v-else></v-container>
</template>
<script>
import jspdf from 'jspdf';
import 'jspdf-autotable';
export default {
  data: () => ({
    valid: true,
    stad: null,
    dialog: false,
    purposeVal: null,
    submittedClearance:'',
    staffRegistrar_id:0,
    deficiencydialog: false,
    loading: false,
    snackbar: false,
    selected: [],
    text: "",
    success: "",
    error: "",
    deficiency_designee: "",
    headers: [
      { text: "Office", value: "office" },
      { text: "Designee", value: "designee" },
      { text: "Status", value: "status" },
      { text: "Action", value: "actions" },
    ],
    deficiencyheaders: [
      { text: "Deficiency", value: "title" },
      { text: "Note", value: "note" },
      { text: "Status", value: "completed" },
    ],
    clearanceStatus: "0",
    clearances: [],
    clearance:{},
    student:{},
    deficiencies: [],
    deficiencySignatory: "",
    activeClearancePurpose: "",
    editedIndex: -1,
    editedItem: {
      id: "",
      token: "",
      name: "",
      student_number: "",
      program: "",
      purpose: "",
    },
    submitRequestItem: {
      office: "",
      designee: "",
      status: "",
      data_approved: "",
      deficiencyCount: "",
      clearanceRequestCount: "",
      staff_id: "",
    },
    defaultItem: {
      id: "",
      token: "",
      name: "",
      student_number: "",
      program: "",
      purpose: "",
    },
    heading: "Sample PDF Generator",
      moreText: [
        "This is another few sentences of text to look at it.",
        "Just testing the paragraphs to see how they format.",
        "jsPDF likes arrays for sentences.",
        "Do paragraphs wrap properly?",
        "Yes, they do!",
        "What does it look like?",
        "Not bad at all."
      ],
      items: [
        { title: "Item 1", body: "I am item 1 body text" },
        { title: "Item 2", body: "I am item 2 body text" },
        { title: "Item 3", body: "I am item 3 body text" },
        { title: "Item 4", body: "I am item 4 body text" }
      ],
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
    submitClearance(){ 
      axios
        .post("/api/v1/submitClearance",
        {
          clearance_id: this.clearance.id, 
          staff_id: this.staffRegistrar_id,
        }
        )
        .then((res) => {
          this.submittedClearance = res.data.submittedClearance;
          
        })
        .catch((err) => {
          console.error(err);
        });
    },
    generatePDF() { 
     axios.get('/api/v1/sgs/pdf-create',{responseType: 'blob'
     ,params: { 'clearance': this.clearance.id }

     }).then((response) => {
     var fileURL = window.URL.createObjectURL(new Blob([response.data], {type: 'application/pdf'}));
     var fileLink = document.createElement('a');
     fileLink.href = fileURL;
     fileLink.setAttribute('download', this.student.name+this.clearance.id+'.pdf');
     document.body.appendChild(fileLink);
     fileLink.click();

});
    
      // const columns = [
      //   { title: "Office", dataKey: "office" },
      // { title: "Designee", dataKey: "designee" },
      // { title: "Date Approved", dataKey: "data_approved" },
      // ];
      // const doc = new jspdf({
      //   orientation: "portrait",
      //   unit: "in",
      //   format: "letter"
      // });
      // // text is placed using x, y coordinates
      // doc.setFontSize(16).text(this.heading, 0.5, 1.0);
      // // create a line under heading 
      // doc.setLineWidth(0.01).line(0.5, 1.1, 8.0, 1.1);
      // // Using autoTable plugin
      // doc.autoTable({
      //    headStyles: { fillColor: [0, 0, 0] }, 
      //   columns,
      //   body: this.clearances,
      //   margin: { left: 0.5, top: 1.25 }
      // });
      // // Using array of sentences
      // // doc
      // //   .setFont("helvetica")
      // //   .setFontSize(12)
      // //   .text(this.moreText, 0.5, 3.5, { align: "left", maxWidth: "7.5" });
      
      // // Creating footer and saving file
 



      // doc
      //   .setTextColor(0, 0, 255)
      //   .text(
      //     "This is a simple footer located .5 inches from page bottom",
      //     0.5,
      //     doc.internal.pageSize.height - 0.5
      //   )
      //   .save(`${this.activeClearancePurpose}.pdf`);
    },
    viewDeficiency(item) {
      this.deficiencydialog = true;
      this.deficiency_designee = item.designee;
      this.deficiencies = Object.assign({}, item.deficiency);
      console.log(item);
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

      axios
        .get("/api/v1/activeClearance")
        .then((res) => {
          this.purposeVal = 1;
          console.log(res.data.approvedDateclearanceRequestSTCOUNCIL);
          this.stad = res.data.stad;
          this.student = res.data.student;
          this.activeClearancePurpose = res.data.activeClearancePurpose;
          this.clearance = res.data.clearance;
          this.submittedClearance = res.data.submittedClearance;
          this.staffRegistrar_id = res.data.staffIdREGISTRARSTAFF;
          console.log(this.clearance.cashier);
          this.clearances = [ 
            
            {
              office: "Registrar Staff",
              designee: res.data.signatoryNameclearanceRequestREGISTRARSTAFF
                ? res.data.signatoryNameclearanceRequestREGISTRARSTAFF.name
                : null,
              status:   res.data.clearance.registrar,
              data_approved: res.data.approvedDateclearanceRequestREGISTRARSTAFF
                ? res.data.approvedDateclearanceRequestREGISTRARSTAFF
                : res.data.approvedDateclearanceRequestREGISTRAR,
              deficiencyCount: res.data.countDeficiencyREGISTRARSTAFF,
              deficiency: res.data.deficiencyREGISTRARSTAFF,
              clearanceRequestCount: res.data
                .countClearanceRequestREGISTRARSTAFF
                ? 1
                : 0,
              staff_id: res.data.staffIdREGISTRARSTAFF,
            },
            {
              office: "Cashier",
              designee: res.data.signatoryNameclearanceRequestCASHIER
                ? res.data.signatoryNameclearanceRequestCASHIER.name
                : null,
              status: res.data.clearance.registrar == 0 ? 2 :res.data.clearance.cashier,
              data_approved: res.data.approvedDateclearanceRequestCASHIER,
              deficiencyCount: res.data.countDeficiencyCASHIER,
              deficiency: res.data.deficiencyCASHIER,
              clearanceRequestCount: res.data.countClearanceRequestCASHIER,
              staff_id: res.data.staffIdCASHIER,
            },
            {
              office: "Library",
              designee: res.data.signatoryNameclearanceRequestLIBRARY
                ? res.data.signatoryNameclearanceRequestLIBRARY.name
                : null,
              status: res.data.clearance.library,
              data_approved: res.data.approvedDateclearanceRequestLIBRARY,
              deficiencyCount: res.data.countDeficiencyLIBRARY,
              deficiency: res.data.deficiencyLIBRARY,
              clearanceRequestCount: res.data.countClearanceRequestLIBRARY,
              staff_id: res.data.staffIdLIBRARY,
            },
            {
              office: "OSAS",
              designee: res.data.signatoryNameclearanceRequestOSAS
                ? res.data.signatoryNameclearanceRequestOSAS.name
                : null,
              status: res.data.clearance.osas,
              data_approved: res.data.approvedDateclearanceRequestOSAS,
              deficiencyCount: res.data.countDeficiencyOSAS,
              deficiency: res.data.deficiencyOSAS,
              clearanceRequestCount: res.data.countClearanceRequestOSAS,
              staff_id: res.data.staffIdOSAS,
            },
            {
              office: "Program Director",
              designee: res.data.signatoryNameclearanceRequestPROGRAMDIRECTOR
                ? res.data.signatoryNameclearanceRequestPROGRAMDIRECTOR.name
                : null,
              status: res.data.clearance.program_director,
              data_approved:
                res.data.approvedDateclearanceRequestPROGRAMDIRECTOR,
              deficiencyCount: res.data.countDeficiencyPD,
              deficiency: res.data.deficiencyPD,
              clearanceRequestCount: res.data.countClearanceRequestPD ? 1 : 0,
              staff_id: res.data.staffIdPD,
            },
            {
              office: "Dean",
              designee: res.data.signatoryNameclearanceRequestDEAN
                ? res.data.signatoryNameclearanceRequestDEAN.name
                : null,
              status: res.data.clearance.program_director == 0 ? 2 :res.data.clearance.college_deaan,
              data_approved: res.data.approvedDateclearanceRequestDEAN,
              deficiencyCount: res.data.countDeficiencyDEAN,
              deficiency: res.data.deficiencyDEAN,
              clearanceRequestCount: res.data.countClearanceRequestDEAN ? 1 : 0,
              staff_id: res.data.staffIdDEAN,
            },
          ];
        })
        .catch((err) => {
          console.error(err);
           if (err.response.data.purpose == '0') {
            this.purposeVal = 0;
                  
          }
        });
    },
    sendRequest(item) {
      this.submitRequestItem = Object.assign({}, item);
      console.log(this.submitRequestItem);
    
      axios
        .post("/api/v1/sendRequest", this.submitRequestItem)
        .then((res) => {
          console.log(res.data.approvedDateclearanceRequestSTCOUNCIL);
          this.stad = res.data.stad;
            this.submittedClearance = res.data.submittedClearance;
      this.staffRegistrar_id =  res.data.staffIdREGISTRARSTAFF;
          // this.activeClearancePurpose = res.data.activeClearancePurpose;
           this.clearance = res.data.clearance;
         this.clearances = [ 
           
            {
              office: "Registrar Staff",
              designee: res.data.signatoryNameclearanceRequestREGISTRARSTAFF
                ? res.data.signatoryNameclearanceRequestREGISTRARSTAFF.name
                : null,
              status:  res.data.clearance.registrar,
              data_approved: res.data.approvedDateclearanceRequestREGISTRARSTAFF
                ? res.data.approvedDateclearanceRequestREGISTRARSTAFF
                : res.data.approvedDateclearanceRequestREGISTRAR,
              deficiencyCount: res.data.countDeficiencyREGISTRARSTAFF,
              deficiency: res.data.deficiencyREGISTRARSTAFF,
              clearanceRequestCount: res.data
                .countClearanceRequestREGISTRARSTAFF
                ? 1
                : 0,
              staff_id: res.data.staffIdREGISTRARSTAFF,
            },
            {
              office: "Cashier",
              designee: res.data.signatoryNameclearanceRequestCASHIER
                ? res.data.signatoryNameclearanceRequestCASHIER.name
                : null,
             status: res.data.clearance.registrar == 0 ? 2 :res.data.clearance.cashier,
              data_approved: res.data.approvedDateclearanceRequestCASHIER,
              deficiencyCount: res.data.countDeficiencyCASHIER,
              deficiency: res.data.deficiencyCASHIER,
              clearanceRequestCount: res.data.countClearanceRequestCASHIER,
              staff_id: res.data.staffIdCASHIER,
            },
            {
              office: "Library",
              designee: res.data.signatoryNameclearanceRequestLIBRARY
                ? res.data.signatoryNameclearanceRequestLIBRARY.name
                : null,
              status: res.data.clearance.library,
              data_approved: res.data.approvedDateclearanceRequestLIBRARY,
              deficiencyCount: res.data.countDeficiencyLIBRARY,
              deficiency: res.data.deficiencyLIBRARY,
              clearanceRequestCount: res.data.countClearanceRequestLIBRARY,
              staff_id: res.data.staffIdLIBRARY,
            },
            {
              office: "OSAS",
              designee: res.data.signatoryNameclearanceRequestOSAS
                ? res.data.signatoryNameclearanceRequestOSAS.name
                : null,
              status: res.data.clearance.osas,
              data_approved: res.data.approvedDateclearanceRequestOSAS,
              deficiencyCount: res.data.countDeficiencyOSAS,
              deficiency: res.data.deficiencyOSAS,
              clearanceRequestCount: res.data.countClearanceRequestOSAS,
              staff_id: res.data.staffIdOSAS,
            },
            {
              office: "Program Director",
              designee: res.data.signatoryNameclearanceRequestPROGRAMDIRECTOR
                ? res.data.signatoryNameclearanceRequestPROGRAMDIRECTOR.name
                : null,
              status: res.data.clearance.program_director,
              data_approved:
                res.data.approvedDateclearanceRequestPROGRAMDIRECTOR,
              deficiencyCount: res.data.countDeficiencyPD,
              deficiency: res.data.deficiencyPD,
              clearanceRequestCount: res.data.countClearanceRequestPD ? 1 : 0,
              staff_id: res.data.staffIdPD,
            },
            {
              office: "Dean",
              designee: res.data.signatoryNameclearanceRequestDEAN
                ? res.data.signatoryNameclearanceRequestDEAN.name
                : null,
              status: res.data.clearance.program_director == 0 ? 2 :res.data.clearance.college_deaan,
              data_approved: res.data.approvedDateclearanceRequestDEAN,
              deficiencyCount: res.data.countDeficiencyDEAN,
              deficiency: res.data.deficiencyDEAN,
              clearanceRequestCount: res.data.countClearanceRequestDEAN ? 1 : 0,
              staff_id: res.data.staffIdDEAN,
            },
          ];
        })
        .catch((err) => {
          console.error(err);
        });
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