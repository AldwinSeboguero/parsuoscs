<template>
<v-app id="inspire">
    <v-main>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="6" md="6">
            <v-card class="elevation-2">
  <v-row class="fill-height">
                   
                    <v-col cols="12" md="12">
                      <v-card-text class="mt-12">
                        <h1
                          class="text-center desplay-2 primary--text text--accent4"
                        >
                          Reset Password
                        </h1>

                        <h4 class="text-center mt-4">
                          Enter your registered email 
                        </h4>
                        <v-form autocomplete="off"
                        v-model="valid" 
                         v-on:submit.stop.prevent="requestResetPassword" method="post">
                          <v-text-field 
                            label="Email"
                            name="Email"
                            prepend-icon="mdi-email" 
                            color="teal accent-4"
                            :success-messages="success"
                          :error-messages="error"
                          :rules="[rules.required, rules.validEmail]"
                            required
                           type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email"
                          />
                          <div class="text-center mt-3 pb-3">
                    
                        <v-btn
                          color="primary"
                          depressed
                          :disabled ="!valid"
                          elevation="0"
                          rounded
                          type="submit"
                          :loading = "loading"
                        >Send Password Reset Link</v-btn>
                      </div>
                        </v-form>
                      </v-card-text>
                      
                     
                    </v-col>
                  </v-row>

            </v-card>
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

  <!-- <div class="container">
    <div class="row justify-content-center">
      <div class="col-6">
        <div class="card card-default">
          <div class="card-header">Reset Password</div>
          <div class="card-body">
            <form autocomplete="off" @submit.prevent="requestResetPassword" method="post">
              <div class="form-group">
                  <label for="email">E-mail</label>
                  <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
              </div>
              <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
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
        email: null,
        has_error: false,
          success: "",
          loading: false,
    error: "",
    snackbar: false,
    valid:true,
      rules: {
      required: (v) => !!v || "This Field is Required",
      min: (v) => v.length >= 5 || "Minimum 5 Characters Required",
      validEmail: (v) => /.+@.+\..+/.test(v) || "Email must be valid",
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
   
  },
    methods: {
        requestResetPassword() {
          this.loading = true;
            axios.post("/api/v1/create", {email: this.email}).then(result => {
              this.email =null;
                this.response = result.data; 
                this.snackbar = true;
                this.text = result.data.message;
                this.loading = false;
            }, error => {
              this.loading = false;
                console.error(error);
                this.success = "";
                this.snackbar = true;
                this.text = error.response.data.message;
            });
        }
    },
    props: {
    source: String,
  },
  name: "App",
}
</script>