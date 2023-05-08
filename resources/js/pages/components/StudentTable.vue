<template>
  <div>

  <v-card class="pt-1">
        <v-card-title class="align-end pl-4 pa-2 mt-2 mb-3 rounded white--text elevation-1" style=" margin-left: -8px; margin-right: -8px; max-height: 50px; background: linear-gradient(to right, #0d47a1, #0d47a1, #1A237E);">
                <span class="font-semibold"><v-icon  dark left>mdi-list-box</v-icon>Students</span>
            </v-card-title>
    <v-data-table
        :headers="headers"
        :items="table_data"
        :items-per-page="10"
        :page="current_page"
        :pageCount="total_pages"
        :server-items-length="total"
        :options.sync="options"
        responsive
        class=" elevation-0  mt-1 font-weight-normal pa-0 font-sans text-uppercase mx-4"
    >
    <template v-slot:item.id="{ item }">
     <span class="font-weight-bold" >{{item.prefix}}{{pad(item.id,5)}}</span>
    </template>
    
    <template v-slot:item="{ item, index }">
      
    <tr :class="{ 'blue darken-3 white--text elevation-11 flex px-4': editingRow === item } " >
        <td v-for="header in headers" 
        style="font-size:12px !important;"
         :key="header.text"
         class="d-block d-sm-table-cell">
        <v-menu
           transition="slide-y-transition"
           v-if="header.value === 'actions'"
             left
           content-class="elevation-1 subtitle-2 text-xs"
           class="mr-12"
           :offset-y="offSet"
           >
               <template v-slot:activator="{ on, attrs }">
               <v-btn
               class="blue lighten-4 blue--text text--darken-4 font-weight-light"
               small
               v-bind="attrs"
               v-on="on"
               depressed
               icon
               >
               <v-icon
               :class="{ 'white--text': editingRow === item }" 
               small
               >
               mdi-cogs
               </v-icon>
               </v-btn>
               </template>  

               <v-list width="180" nav> 
               <v-list-item
               link
               @click="editData(item)"

               class="blue--text"
               active-class="blue--text text--accent-4 font-weight-bold"
               >
               <v-icon small class="mr-2 blue--text">mdi-account-edit</v-icon>Edit
               </v-list-item>
               <v-list-item
               link
               @click="confirmDelete(item)"
               class="red--text"
               active-class="orange--text text--accent-4 font-weight-bold"
               >
              <v-icon small class="mr-2 red--text">mdi-delete-circle-outline</v-icon>Delete
               </v-list-item>
               </v-list>
           </v-menu>
           
        <span class="font-weight-bold" v-if="header.value === 'id'">{{item.prefix}}{{pad(item.id,5)}}</span>

        <span v-else>{{ item[header.value] }}</span></td>
      </tr>
      
    </template>
    <!-- <template v-slot:header.name="{ headers }">
      {{ header.text.toUpperCase() }}
    </template>
    <template
        v-slot:body="{ items }"
      >
        <tbody  v-for="item in items" 
            :key="item.name">
          
            <tr
            v-for="header in headers" 
            :key="header.text"
            style="font-size:12px !important;"
            >
              <td>{{ item[header.value] }}</td>
            </tr>
</span>
         
        </tbody>
      </template> -->
    </v-data-table>
    <v-dialog v-model="showDeleteDialog" persistent width="400">
      <v-card class="pt-1">
      <v-card-title class="align-center ma-2 mt-0 pa-2 rounded white--red elevation-0" >

         <v-icon left class="red--text text--lighten-1">mdi-delete-circle</v-icon> Delete Item
        </v-card-title>
        <v-card-text>
          Are you sure you want to delete this item?<br/>
          (<i>{{itemToDelete ? 'Student ID: '+itemToDelete.student_id+' Name: '+itemToDelete.name+' Program: '+itemToDelete.program : ''}}</i>)
        </v-card-text>
        <v-card-actions>
          <v-spacer/>
          <v-btn elevation-0 color="primary" outlined  @click="showDeleteDialog = false">Cancel</v-btn>
          <v-btn elevation-0 color="red" dark @click="deleteData">Delete</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    </v-card-text>
        </v-card>
</div>
</template>


<script>
  
  import debounce from "lodash/debounce";
    export default {
      props:{

        barangay: Array,
        sitio:Array,
        min_age: 0,
        max_age: 0,
        sx: String,
        birthday:Array,
        pension:Array,
        annualincome: 0,
        forms: {
          id: '',
          name: '',
          student_id:'',
          campus_id:'',
          college_id:'',
          email: '',
          program_id:'',
        },
      },
      watch: {
       
        options: {
          handler() {
            this.nextPage();
          },
          deep: true,
        },
        

      },
      methods: {
        editRow(item) {
          this.editingRow = Object.assign({}, item);

        },
        editRowReset() {
           this.editingRow = Object.assign({}, this.defaultForms);
        },
         editData(val) {
          console.log(val)
          this.editingRow = val;
          console.log(this.editingRow == val );

          this.$emit('childEvent', val) // Emit the childEvent event with the value
        },
        confirmDelete(item) {
          this.showDeleteDialog = true;
          this.itemToDelete = item;
        },
        async deleteData(val) {
          try {
            await axios.delete('/api/v1/adminstudents/'+ this.itemToDelete.id, {
              // Optional config object

            });
          this.showDeleteDialog = false;
          this.itemToDelete = null;
          this.nextPage();

          } catch (error) {
            // Handle the error
          }
        },
        pad(num, size) {
            num = num.toString();
            while (num.length < size) num = "0" + num;
            return num;
        },
       
        clean($val) {
          if($val){$val = $val.replace(/ +(?= )/g, "");
          $val = $val.replace(/[&\/\\#,+()$~%.'":*?<>{}]/g, " "); // Replaces all spaces with hyphens.
          $val = $val.replace(/ +(?= )/g, "");
          
          return $val;
          }
          // Removes special chars.
        },
       
         async nextPage() {
          const { page, itemsPerPage } = this.options;
          let pageNumber = page;
           await axios
            .get(`/api/v1/getStudents?page=` + pageNumber,{
              params: { 
                'per_page': itemsPerPage,
                'name': this.forms.name,
                'student_id': this.forms.student_id,
                'email': this.forms.email,
                'campus_id': this.forms.campus_id,
                'college_id': this.forms.college_id,
                'program_id': this.forms.program_id,
              },
            })
            .then((response) => {
                this.semester_name = response.data.semester_name;
                this.headers = response.data.headers;
                this.table_data = response.data.table_data.data ;
                this.current_page= response.data.table_data.current_page;
                this.total_pages= response.data.table_data.total_pages;
                this.total= response.data.table_data.total;
            });
        },

         filter(val) {
          const { page, itemsPerPage } = this.options;
          let pageNumber = page;
            axios
            .get(`/api/v1/getStudents?page=` + pageNumber,{
              params: { 
                'per_page': itemsPerPage,
                'name': this.forms.name,
                'student_id': this.forms.student_id,
                'email': this.forms.email,
                'campus_id': this.forms.campus_id,
                'college_id': this.forms.college_id,
                'program_id': this.forms.program_id,

              },
            })
            .then((response) => {
                // this.semester_name = response.data.semester_name;
                this.headers = response.data.headers;
                this.table_data = response.data.table_data.data ;
                this.current_page= response.data.table_data.current_page;
                this.total_pages= response.data.table_data.total_pages;
                this.total= response.data.table_data.total;
            });
        },
      },

      data () {
      return {
        showDeleteDialog: false,
        itemToDelete: null,
        editingRow: {
          id: '',
          name: '',
          student_id:'',
          campus_id:'',
          college_id:'',
          email: '',
          program_id:'',
        },
        defaultForms: {
          id: '',
          name: '',
          student_id:'',
          campus_id:'',
          college_id:'',
          email: '',
          program_id:'',
        },  
        semester:'',
        semester_name:'',
        forms_data : this.forms,
        table_data: [],
        options: {},
        search: '',
        // barangay: this.barangay,
        offSet: true,
        constituents: Array,
        barangays: [],
        sitios: [],
        filtersBarangay: Array,
        filtersSitio: Array,
        current_page: 0,
        total_pages: 0,
        total: 0,
        exportList: [
            { title: 'Excel' },
            { title: 'PDF' },
        ],
        
        headers: [
        ],

        constituents: [
        ],
      }
    },
    }
</script>


<style scoped>

.v-text-field--outlined >>> fieldset {
  border-color: #e4e7ea;
  

}
.v-text-field >>> input {
    height: 32px;


}

.form-control{
    height: 36px;
}
.edited-row {
  background-color: #eeeeee;
}
</style>
<style scoped>
  .large-font {
  font-size: 128px;
}
</style>