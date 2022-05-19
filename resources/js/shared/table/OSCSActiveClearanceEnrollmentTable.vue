<template>
<v-card class="px-6 pb-6  mt-4" elevation="8" rounded="lg">
    <v-card-title>
        Active Clearance  
        <!-- <template v-for="(user, index) in currentUser" >
          <span :key="index">
            {{currentUser}}
          </span>
        </template> -->
    </v-card-title>
  <v-data-table
    :headers="tableHeaders"
    :items="this.$store.state.activeclearance.signatories"
    :single-expand="true"
    :expanded.sync="expanded"
    hide-default-footer
    item-key="user"
    show-expand
  >
 
    <template v-slot:expanded-item="{ headers, item }">
      <td :colspan="headers.length">
        <span v-if="item.deficiencyCount == 0"> No deficiencies </span>
       
          <v-simple-table  v-else class="elevation-0">
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left">
                    Title
                  </th>
                  <th class="text-left">
                    Status
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="item in item.deficiencies"
                  :key="item.title"
                >
                  <td>{{ item.title }}</td>
                  <td>{{ item.completed }}</td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>

      
       
      </td>
    </template>
    <template v-slot:item.designee="{ item }">
      <div class="font-weight-bold">{{ item.designee }}</div>
    </template>
    <template v-slot:item.clearance_request_status="{ item }">
        <template v-if="item.clearance_request_status == 1">
          <v-chip text-color="white" color="success" small>
            <v-avatar left>
              <v-icon small>mdi-check-circle</v-icon>
            </v-avatar>
            Approved on {{ item.date_approved }}
          </v-chip>
        </template>
        <template v-else-if="item.clearance_request_status == 0 || item.clearance_request_status == 2">
          <template v-if="item.clearanceRequestCount == 1">
            <v-chip text-color="white" color="cyan" small v-if="item.clearance_request_status != 2">
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

       <template v-slot:item.actions="{ item }" v-if="stad ==1">
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
              >Requesting for Clearance is Closed!</v-btn
            >
      </template>
  </v-data-table>
</v-card>
</template>
<script>
  export default {
    data () {
      return {
        expanded: [],
        singleExpand: false,
        tableHeaders: [
          {
            text: 'Office',
            value: 'designee',
          },
          { text: 'Designee', value: 'user' },
          { text: 'Status', value: 'clearance_request_status' },
          { text: 'Action', value: 'actions' },
          { text: '', value: 'data-table-expand' },
        ],
      }
    },
    computed: {
      //  currentUser(){
      //   return this.$store.state.currentUser.user.user.name;
      //   },
      //   activeClearance(){
      //     return this.$store.state.setActiveClearance;
      //   }
           signatories(){
             return this.$store.state.activeclearance.signatories;
           },
           stad(){
             return this.$store.state.activeclearance.stad;
           },
          

      
    },
    mounted(){
      this.$store.dispatch("getActiveClearance");
    }

  }
</script>