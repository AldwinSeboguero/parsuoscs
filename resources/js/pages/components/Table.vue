<template>
  <div>
  <!-- <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 text-right bg-title-right">
  <div id="clients-table_filter" class="dataTables_filter">
   
    <v-row class="grey lighten-4"
     justify="start">
        <v-col
          cols="12"
          md="4"
          class="pull-left ml-0 pl-4"
        >
          <v-text-field
            v-model="search"
            prepend-inner-icon="mdi-magnify"
          
            class="rounded-lg"
            dense
            label="Search"
            outlined
            hide-details
            required
            background-color="white"
          ></v-text-field>
        </v-col>
    </v-row>
    </div>
  </div> -->
  <v-card class="pt-1">
        <v-card-title class="align-end pl-4 pa-2 mt-2 mb-3 rounded white--text elevation-1" style=" margin-left: -8px; margin-right: -8px; max-height: 50px; background: linear-gradient(to right, #0d47a1, #0d47a1, #1A237E);">
                <span class="font-semibold"><v-icon  dark left>mdi-account-details</v-icon>SIGNATORIES <i class="overline">({{semester_name}})</i></span>
            </v-card-title>
    <v-data-table
        :headers="headers"
        :items="table_data"
        :items-per-page="10"
        :page="current_page"
        :pageCount="total_pages"
        :server-items-length="total"
        :options.sync="options"
        dense
        class="elevation-0  mt-1 font-light pa-0 font-sans text-uppercase mx-4"
    >
    
    <template v-slot:item="{ item, index }">
      <tr :class="{ 'blue darken-3 white--text elevation-11 flex px-4': editingRow === item } " >
        <td v-for="header in headers" :key="header.text" style="font-size:12px">
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
               class="text--black darken-3 font-weight-light"
               
               v-bind="attrs"
               v-on="on"
               depressed

               icon
               >
               <v-icon
               :class="{ 'white--text': editingRow === item }" 
               x-small
               >
               mdi-cogs
               </v-icon>
               </v-btn>
               </template>  

               <v-list width="180" nav> 
               <v-list-item
               link
               @click="editData(item,index)"

               class="blue--text"
               active-class="blue--text text--accent-4 font-weight-bold"
               >
               <v-icon small class="mr-2 blue--text">mdi-account-edit</v-icon>Edit
               </v-list-item>
               <v-list-item
               link
               @click="confirmDelete(item,index)"
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
    </v-data-table>
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
        isEditMode:'',
        forms: {
          semester: '',
          campus:'',
          program: '',
          designation: '',
          signatory: '',
        },
      },
      watch: {
       
        options: {
          handler() {
            this.nextPage();
          },
          deep: true,
        },
        'forms.signatory': debounce(function (val) {
          console.log(val)
          const { page, itemsPerPage } = this.options;
          let pageNumber = page;
           axios
            .get(`/api/v1/getStaff?page=` + pageNumber,{
              params: { 
                'per_page': itemsPerPage,
                'semester': this.forms.semester,
                'program': this.forms.program,
                'campus': this.forms.campus,
                'designation': this.forms.designation,
                'signatory': val,
              },
            })
            .then((response) => {
              // this.semester = response.data.semester.semester;
                this.headers = response.data.headers;
                this.table_data = response.data.table_data.data ;
                this.current_page= response.data.table_data.current_page;
                this.total_pages= response.data.table_data.total_pages;
                this.total= response.data.table_data.total;
            });

        }, 300),

        'forms.designation': debounce(function (val) {
          console.log(val)
          const { page, itemsPerPage } = this.options;
          let pageNumber = page;
           axios
            .get(`/api/v1/getStaff?page=` + pageNumber,{
              params: { 
                'per_page': itemsPerPage,
                'semester': this.forms.semester,
                'program': this.forms.program,
                'campus': this.forms.campus,
                'designation': val,
                'signatory': this.forms.signatory,
              },
            })
            .then((response) => {
              // this.semester = response.data.semester.semester;
                this.headers = response.data.headers;
                this.table_data = response.data.table_data.data ;
                this.current_page= response.data.table_data.current_page;
                this.total_pages= response.data.table_data.total_pages;
                this.total= response.data.table_data.total;
            });
        }, 300),

        'forms.campus': debounce(function (val) {
          console.log(val)
          const { page, itemsPerPage } = this.options;
          let pageNumber = page;
           axios
            .get(`/api/v1/getStaff?page=` + pageNumber,{
              params: { 
                'per_page': itemsPerPage,
                'semester': this.forms.semester,
                'program': this.forms.program,
                'campus': val,
                'designation': this.forms.designation,
                'signatory': this.forms.signatory,
              },
            })
            .then((response) => {
              // this.semester = response.data.semester.semester;
              // this.semester_name = response.data.semester_name;

                this.headers = response.data.headers;
                this.table_data = response.data.table_data.data ;
                this.current_page= response.data.table_data.current_page;
                this.total_pages= response.data.table_data.total_pages;
                this.total= response.data.table_data.total;
            });
        }, 300),

        'forms.semester': debounce(function (val) {
          console.log(val)
          const { page, itemsPerPage } = this.options;
          let pageNumber = page;
           axios
            .get(`/api/v1/getStaff?page=` + pageNumber,{
              params: { 
                'per_page': itemsPerPage,
                'semester': val,
                'program': this.forms.program,
                'campus': this.forms.campus,
                'designation': this.forms.designation,
                'signatory': this.forms.signatory,
              },
            })
            .then((response) => {
              // this.semester = response.data.semester.semester;
              this.semester_name = response.data.semester_name;

                this.headers = response.data.headers;
                this.table_data = response.data.table_data.data ;
                this.current_page= response.data.table_data.current_page;
                this.total_pages= response.data.table_data.total_pages;
                this.total= response.data.table_data.total;
            });
        }, 300),

        'forms.program': debounce(function (val) {
          console.log(val)
          const { page, itemsPerPage } = this.options;
          let pageNumber = page;
           axios
            .get(`/api/v1/getStaff?page=` + pageNumber,{
              params: { 
                'per_page': itemsPerPage,
                'semester': this.forms.semester,
                'program': val,
                'campus': this.forms.campus,
                'designation': this.forms.designation,
                'signatory': this.forms.signatory,
              },
            })
            .then((response) => {
              // this.semester = response.data.semester.semester;
              this.semester_name = response.data.semester_name;
                this.headers = response.data.headers;
                this.table_data = response.data.table_data.data ;
                this.current_page= response.data.table_data.current_page;
                this.total_pages= response.data.table_data.total_pages;
                this.total= response.data.table_data.total;
            });
        }, 300),

      },
      methods: {
        
        editRow(item) {
          this.editingRow = item
        },
        editRowReset() {
          this.editingRow = null
        },
        async editData(val,index) {

          this.editingRow = val;
          console.log(val.semester);
          console.log(this.editingRow == val );

          this.isEditMode = true;
          this.$emit('childEvent', val,this.isEditMode) // Emit the childEvent event with the value
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
        async deleteData(val) {
          try {
            await axios.delete('/api/v1/staffs/'+ val, {
              // Optional config object

            });
          this.showDeleteDialog = false;
          this.itemToDelete = null;
          this.nextPage();

          } catch (error) {
            // Handle the error
          }
        },
         async nextPage() {
          const { page, itemsPerPage } = this.options;
          let pageNumber = page;
           await axios
            .get(`/api/v1/getStaff?page=` + pageNumber,{
              params: { 
                'per_page': itemsPerPage,
                'semester': this.forms.semester,
                'program': this.forms.program,
                'campus': this.forms.campus,
                'designation': this.forms.designation,
                'signatory': this.forms.signatory,
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
      },

      data () {
      return {
       
        page: 1,
        semester:'',
        semester_name:'',
        forms_data : this.forms,
        table_data: [],
        options: {},
        search: '',
        editingRow: null,

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

</style> -->
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
.v-pagination.v-pagination--flat .v-pagination__item {
  box-shadow: none;
}
</style>