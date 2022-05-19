<template>
    <v-pagination
    v-model="currentPage"
    :length="lastPage"
    :total-visible="8"
    ></v-pagination>
</template>
<script>
export default {
    props: ['store','collection'],
    watch:{
         currentPage(newVal,oldVal){
             if (oldVal > 0) {
                 
            this.paginatePage(newVal);
             }
        },deep: true
    },
    computed: {
       
        currentPage: {
            get(){
                return this.$store.state[this.store][this.collection].current_page;
            },
            set(value){
                this.$store.commit(this.store + '/setCurrentPage', value);
            }
        },
        lastPage: {
            get(){
                return this.$store.state[this.store][this.collection].last_page;
            }
        }

    },
    methods: {
        paginatePage(pageNumber){
            this.$store.dispatch(this.store + '/getList',pageNumber);
           // this.$store.dispatch('user/getList',pageNumber);
        },
        
    }
}
</script>