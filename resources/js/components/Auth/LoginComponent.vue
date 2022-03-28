<template>
    <div class="my-auto">
        <form-layout class="">
            <template #header>
                <v-img
                    position="center center"
                    src="../../assets/brand/fleetgh-logo-horizontal.svg"
                >
                </v-img>
            </template>
            <template #card>
                <v-form :action="loginUrl" method="POST" v-model="valid" ref="loginFormRef">
                    <input type="hidden" name="_token" :value="csrf_token" />
                    <v-card
                        class="pa-4 rounded-3"
                        elevation="3"
                        color="gray"
                    >
                        <v-card-title class="justify-center">
                            <h2 class="font-weight-bold">Login</h2>
                        </v-card-title>
                        <v-card-text>
                                <!-- email or user name -->
                                <div class="my-4">
                                    <v-text-field
                                        label="Email"
                                        name="email"
                                        v-model="loginForm.username"
                                        dense
                                        outlined
                                        append-icon="mdi-email"
                                        :rules="[required('Email'), validEmail()]"
                                    >
                                    </v-text-field>
                                    <span role="alert" class="mt-0 pt-0"><slot name="email-error" class="text-danger"></slot></span>
                                </div>

                                <!-- password  -->
                                <div class="my-4">
                                    <v-text-field
                                        label="Password"
                                        name="password"
                                        v-model="loginForm.password"
                                        :type="showPassword ? 'text' : 'password'"
                                        dense
                                        @click:append="showPassword = !showPassword"
                                        outlined
                                        :append-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
                                        :rules="[required('Password'), minLength('Password', 8)]"
                                    >
                                    </v-text-field>
                                    <span role="alert" class="mt-0 pt-0"><slot name="password-error"></slot></span>
                                </div>
                                <div class="text-right mt-n2">
                                    <a class="my-link" :href="passwordUrl">Forgot password?</a>
                                </div>
                        </v-card-text>
                        <v-card-actions class="mb-3">
                            <v-btn hidden type="submit" ref="submitBtn"> </v-btn>
                            <v-btn
                                @click="login"
                                block
                                color="primary"
                                depressed
                            >{{ processing ? 'Just a moment...' : 'Log in'}}
                            </v-btn>
                        </v-card-actions>
                        <div class="justify-content-center w-100 text-center">
                            <p>
                                Don't have an account? <a :href="registerUrl">Register</a>
                            </p>
                        </div>
                    </v-card>
                </v-form>
            </template>
        </form-layout>
    </div>
</template>

<script>
    import validation from '../../services/validation'
    export default {
        name: "LoginComponent",
        props: {
            loginUrl: String,
            registerUrl: String,
            passwordUrl: String,
        },
        data(){
            return{
                ...validation,
                valid: false,
                loginForm: new Form({
                    username: '',
                    password: ''
                }),
                processing: false,
                showPassword: false
            }
        },
        computed: {
            csrf_token(){
                return this.$root.csrf
            }
        },
        methods: {
            login(){
                if(!this.$refs.loginFormRef.validate()) return
                this.processing = true
                this.$refs.submitBtn.$el.click()
            }
        }
    }
</script>

<style scoped>

</style>
