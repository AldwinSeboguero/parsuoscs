<template>
<v-app id="inspire">
    <v-main>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="6" md="6">
            <v-alert
      prominent
      :type="type"
    >
      <v-row align="center">
        <v-col class="grow">
         {{message}}
        </v-col>
        <v-col class="shrink">
          <v-btn class="primary" @click="login">Back to Login</v-btn>
        </v-col>
      </v-row>
    </v-alert>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
     <v-snackbar v-model="snackbar" top>
              {{ text }}

              <template v-slot:action="{ attrs }">
                <v-btn
                  color="pink"
                  text
                  v-bind="attrs"
                  @click="snackbar = false"
                >
                  Close
                </v-btn>
              </template>
            </v-snackbar>
</v-app>


<!-- 
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-6">
        <div class="card card-default">
          <div class="card-header">New Password</div>
          <div class="card-body">
            <ul v-if="errors">
              <li v-for="error in errors" v-bind:key="error">{{ msg }}</li>
            </ul>
            <form autocomplete="off" @submit.prevent="resetPassword" method="post">
              <div class="form-group">
                  <label for="email">E-mail</label>
                  <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
              </div>
              <div class="form-group">
                  <label for="email">Password</label>
                  <input type="password" id="password" class="form-control" placeholder="" v-model="password" required>
              </div>
              <div class="form-group">
                  <label for="email">Confirm Password</label>
                  <input type="password" id="password_confirmation" class="form-control" placeholder="" v-model="password_confirmation" required>
              </div>
              <button type="submit" class="btn btn-primary">Update</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div> -->
</template>

<script>
export default {
    data() {
      return { 
          success: "",
          loading: false,
          message:"",
          type:"",
    error: "",
    snackbar: false,
    valid:true,
        rules: {
      required: (v) => !!v || "This Field is Required",
      // min: (v) => v.length >= 5|| "Minimum 5 Characters Required",
      min(min, v) {
        return (v || "").length >= min || `Minimum 5 Characters Required`;
      },
      validEmail: (v) => /.+@.+\..+/.test(v) || "Email must be valid",
    },
        token: null,
        email: null,
        password: null,
        password_confirmation: null,
        has_error: false,
        showPassword: false,
    showPasswordc: false,
      editedItem: {
      id: "",
      name: "",
      email: "",
      role: "",
      password: "",
      rpassword: "",
      created_at: "",
    },
      }
    },
    computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New User" : "Edit User";
    },
    formIcon() {
      return this.editedIndex === -1 ? "mdi-account-plus" : "mdi-account-edit";
    },
    passwordmatch() {
      return this.password != this.password_confirmation
        ? "Password Does Not Match"
        : true;
    },
    },

    checkEmail() {
      if (/.+@.+\..+/.test(this.editedItem.email)) {
        axios
          .post("/api/v1/email/verify", { email: this.editedItem.email })
          .then((res) => {
            this.success = res.data.message;
            this.error = "";
          })
          .catch((err) => {
            this.success = "";
            this.error = "Email Already Exist";
          });
      }
    },
    created() {
          axios.post("/api/v1/emailChangeFind" ,{
          token: this.$route.params.token ,
        })
            .then(result => {
                console.log(result.data);
                this.message = "Email Successfully Change!";
                // this.email = result.data.email;
                // this.$router.push({name: 'login'})
                this.type ="success";
            
            }).catch(err =>{
                
                this.type ="error";
                                this.message = err.response.data.message;
            });
    },
    methods: {
      login(){
  window.location.replace("/#/");
      },
        resetPassword() {
          this.loading = true;
            axios.post("/api/v1/reset", {
                token: this.$route.params.token,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation
            })
            .then(result => {
              
                console.log(result.data);
               
                 axios
        .post("/api/v1/user/login", {
          email: this.email,
          password: this.password,
        })
        .then((response) => {
          localStorage.setItem("token", this.$route.params.token);

          window.location.replace("/#/");
          this.loading = false;
        })
        .catch((err) => {
          if (err.response.status == 419) {
            this.$router.push("/");
          } else {
            this.snackbar = true;
            this.text = err.response.data.message;
            console.log(err.response.data.message);
          }
          this.loading = false;
        });
   this.loading = false;
            }, error => {
              this.loading = false;
                console.error(error);
            });
        }
    },
     props: {
    source: String,
  },
  name: "App",
}
</script>