<template>
    <v-container fluid>
        <v-row align="center" justify="center">
            <v-col cols="12" sm="8" md="4">
                <v-card class="elevation-12">


                    <v-app-bar dark color="primary">
                        <v-toolbar-title>Login</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <router-link :to="{ name: 'Index'}">
                            <v-btn icon>
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                        </router-link>
                    </v-app-bar>

                    <v-card-text>
                        <v-form>

                            <v-alert
                                v-if="this.errors.error"
                              border="top"
                              color="red lighten-2"
                              dark>
                              {{ this.errors.error }}
                              </v-alert>

                            <v-text-field
                              name="username"
                              v-model="username"
                              label="Username or email"
                              required
                            ></v-text-field>

                            <v-text-field
                              name="password"
                              v-model="password"
                              :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                              :type="show1 ? 'text' : 'password'"
                              label="Password"
                              required
                               @click:append="show1 = !show1"
                            ></v-text-field>

                            <v-btn name="login" color="primary" class="mr-4" @click="login">
                                <v-icon>mdi-login</v-icon> Login
                            </v-btn>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import AuthService from '../../services/auth'

    export default {
      data() {
        return {
            show1: false,
            username: null,
            password: null,
            errors: {}
        };
      },
      methods: {
        login() {
            AuthService.login({
                username: this.username,
                password: this.password
            })
            .then(token => {
                this.$store.dispatch('auth/saveToken', token)
                this.goToIndex()
            })
            .catch(error => {
              this.errors = error.response.data;
            });
        },
        goToIndex(){
            this.$router.push({ name: "Index" })
        },
      }
    };
</script>
