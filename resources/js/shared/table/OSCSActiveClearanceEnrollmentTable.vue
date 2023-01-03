<template>
<!-- px-6 pb-6  mt-4 -->
<v-container  v-if="!isPurposeSetup && isPurposeSetup !=null">
    
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
          <v-btn class="primary" href="/#/student/settings" elevation="0">Redirect to Settings</v-btn>
        </v-col>
      </v-row>
    </v-alert>
     </v-row>
  </v-container>
  
<v-container v-else-if="isPurposeSetup  && isPurposeSetup !=null">
          <v-alert
      border="top"
      colored-border
      type="info"
      style="border-color: linear-gradient(to left, #1A237E, #1A237E, #0D47A1) !important;"
      dismissible
      dense
      elevation="0"
      v-if="(countApproved == countSignatory) && (countApproved != 0) && (countSignatory != 0) && !isSubmitted && isOpen"

    >
     <span class="font-weight-bold">Note:</span> To complete the proccess. Please click the submit button to send your clearance to the Office of the Registrar.
    </v-alert>     
 </v-alert>

       <v-alert
      border="top"
      colored-border
      type="success"
      elevation="0"
      dismissible
      dense
      v-if="(countApproved == countSignatory) && (countApproved != 0) && (countSignatory != 0) && isSubmitted"

    >
      <span class="font-weight-bold">Note:</span>You have successfully submitted your clearance to the Office of the Registrar (<i> {{
                dateSubmitted 
                }} </i>).
 </v-alert>
    
<v-card class="" elevation="0" rounded="md">
   <v-card-title class="white--text text-uppercase elevation-2 mb-0 pb-0"  style="background: linear-gradient(to left, #1A237E, #1A237E, #0D47A1);">
       
            <span class="font-italic caption mt-1 pl-2"
              ></span
            >
            <v-spacer></v-spacer>
            <span class="font-normal font-weight-medium text-caption">Status: </span> 
                <v-chip   text-color="white" style="margin-left: 4px; border-color: white 2px !important;"
                  color="success"
                  x-small
                  v-if="(countApproved == countSignatory) && (countApproved != 0) && (countSignatory != 0) && isOpen"

                >
                 Completed
                </v-chip> 
                <v-chip   text-color="white" style="margin-left: 4px;"
                  :color="isOpen == true ? 'info' : 'red'"
                  x-small
                  v-if="(countApproved != countSignatory)"
                  
                >
                 {{isOpen == true ? "On-Going" : "Closed"}}
                </v-chip> 
                 
                 <v-btn :loading="downloadLoading" @click="generatePDF" class="elevation-0 error lighten-1 ml-2" rounded x-small dark  
                 v-if="(countApproved == countSignatory) && (countApproved != 0) && (countSignatory != 0)">
                  <v-icon x-small>mdi-file-pdf</v-icon>
                  Download</v-btn>
                   
                 <v-btn :loading="submitLoading" @click="submitClearance" class="elevation-8 blue ml-2" rounded x-small dark  
                v-if="(countApproved == countSignatory) && (countApproved != 0) && (countSignatory != 0) && !isSubmitted && isOpen">
                  <v-icon x-small>mdi-file-send</v-icon>
                  Submit</v-btn>
    </v-card-title>
    <v-card-title class="white--text text-uppercase elevation-2 pb-1 mt-0 pt-0"  style="background: linear-gradient(to left, #1A237E, #1A237E, #0D47A1);">
        <span> Active Clearance </span>
            <span class="font-italic caption mt-1"
              > ( {{ purpose }} )</span
            >
           
    </v-card-title>
   

    
  <v-data-table
  class="px-6 pb-6  mt-4"
    :headers="tableHeaders"
    :items="signatoriesList"
    
    hide-default-footer
    item-key="user"
  >
 
   
    <template v-slot:item.designee="{ item }">
      <div class="font-weight-bold">{{ item.designee }}</div>
    </template>
    <template v-slot:item.clearance_request_status="{ item }">
        <template v-if="item.status == 1">
          <v-chip class="white--text success elevation-1" small >
            <v-avatar left>
              <v-icon class="white--text" small>mdi-check-circle</v-icon>
            </v-avatar>
            Approved on {{ item.approved_at }}
          </v-chip>
        </template>
        <template v-else-if="item.status == 0 || item.status == 2">
          <template v-if="item.requestCount != 0">
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
          <template v-if="item.requestCount == 0">
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
                @click="viewDeficiency(item)"

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

       <template v-slot:item.actions="{ item }">
          
        <template v-if="item.status == 0 && item.designee">
          <template v-if="item.requestCount == 0 && item.deficiencyCount == 0">
            
            <v-btn
              class="ma-2"
              dark
              color="indigo darken-4"
              depressed
              small
              @click="sendRequest(item)"
              :loading ="item.loadingBtn"
              v-if="isOpen == true"
              >Send Request</v-btn
            >

            <v-btn
              class="ma-2"
              color="red darken-4"
              depressed
              small 
             dark
              v-else
              >Clearance is Closed!</v-btn
            >
             
          </template>
            
        </template>
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
     
  </v-data-table>
</v-card>
<v-dialog v-model="deficiencydialog" scrollable max-width="500px">
      <v-card>
        <v-card-title class="headline white--text" style="background: linear-gradient(to left, #1A237E, #1A237E, #0D47A1);"
          >Deficiency List
          
        </v-card-title>
        <v-card-subtitle class="mb-0 pb-1" style="background: linear-gradient(to left, #1A237E, #1A237E, #0D47A1);">
          <span class="font-italic subtitle-2 white--text">
            (Designee: {{ deficiency_designee }})</span
          >
        </v-card-subtitle>
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
</v-container>
</template>
<script>
  export default {
    data () {
      return {
        downloadLoading : false,
        submitLoading : false,

        student_name: '',
        clearance_id: '',
        deficiency_designee: "",
        deficiencyheaders: [
          { text: "Deficiency", value: "title" },
          { text: "Note", value: "note" },
          { text: "Status", value: "completed" },
        ],  
        deficiencies: [],
        deficiencySignatory: "",
        deficiencydialog: false,
        signatoriesList: [],
        countApproved: 0,
        countSignatory: 0,
        purpose: '',
        isOpen: false,
        isSubmitted: false,
        dateSubmitted : '',
        isPurposeSetup: null,
        expanded: [],
        singleExpand: false,
        tableHeaders: [
          { text: 'Designee', value: 'designee' },
          {
            text: 'Office',
            value: 'office',
          },
          { text: 'Status', value: 'clearance_request_status' },
          { text: 'Action', value: 'actions' },
          // { text: '', value: 'data-table-expand' },
        ],
      }
    },
    computed: {
    },
    methods:{
       submitClearance(){ 
          this.submitLoading=true;
          axios
            .post("/api/v1/active-clearance/submit",
            {
              clearance_id: this.clearance_id, 

            }
            )
            .then((res) => {
              this.isSubmitted = res.data.isSubmitted;
              this.dateSubmitted = res.data.dateSubmitted;
              this.submitLoading=false;
              
            })
            .catch((err) => {
              console.error(err);
              this.submitLoading=false;

            });
        },
      generatePDF() { 
        if((this.countApproved == this.countSignatory) && (this.countApproved != 0) && (this.countSignatory != 0)){
            this.downloadLoading = true;
            axios.get('/api/v1/active-clearance/pdf',{responseType: 'blob'
            ,params: { 'clearance_id': this.clearance_id }

            }).then((response) => {
              this.student_name = this.student_name.replace(/[&\/\\#,+()$~%.'":*?<>{}]/g, "_"); // Replaces all spaces with hyphens.
              this.student_name = this.student_name.replace(/ +(?= )/g, "");
              
                var fileURL = window.URL.createObjectURL(new Blob([response.data], {type: 'application/pdf'}));
                var fileLink = document.createElement('a');
                fileLink.href = fileURL;
                fileLink.setAttribute('download', this.student_name+'-'+this.clearance_id+'.pdf');
                document.body.appendChild(fileLink);
                fileLink.click();
                this.downloadLoading = false;
              });
              
        }
        
    },
        viewDeficiency(item) {
          this.deficiencydialog = true;
          this.deficiency_designee = item.designee;
          this.deficiencies = Object.assign({}, item.defieciencyList);
          
        },
        signatories(){
            axios.get("/api/v1/active-clearance")
            .then(response => {
              this.signatoriesList = response.data.signatories;
              this.countApproved = response.data.countApproved;
              this.countSignatory = response.data.countSignatory;
              this.purpose = response.data.purpose;
              this.isOpen = response.data.isOpen;
              this.isSubmitted = response.data.isSubmitted;
              this.dateSubmitted = response.data.dateSubmitted;
              this.isPurposeSetup = response.data.isPurposeSetup;
              this.clearance_id = response.data.clearance_id;
              this.student_name = response.data.student_name;

            });
          
          },
           sendRequest(item){
            item.loadingBtn = true;
            console.log( item.loadingBtn);
            this.submitRequestItem = Object.assign({}, item);
            axios.post("/api/v1/active-clearance/send-request", this.submitRequestItem)
              .then((res) => {
                  item.loadingBtn = false;
                  this.signatoriesList = res.data.signatories;
                  this.countApproved = response.data.countApproved;
                  this.countSignatory = response.data.countSignatory;
              })
              .catch((err) => {
                console.error(err);
                  item.loadingBtn = false;

              });
           },
    },
    created(){
      this.signatories();
    }

  }
</script>