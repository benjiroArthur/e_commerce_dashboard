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
                <v-form :action="registerUrl" method="POST" v-model="valid" ref="registerFormRef">
                    <input type="hidden" name="_token" :value="csrf_token" />
                    <v-card
                        class="pa-4 rounded-3"
                        elevation="3"
                        color="gray"
                    >
                        <v-card-title class="justify-center">
                            <h2 class="font-weight-bold">Register</h2>
                        </v-card-title>
                        <v-card-text>
                                <!-- full name -->
                                <div class="my-4">
                                    <v-text-field
                                        label="Full Name"
                                        name="name"
                                        v-model="registerForm.name"
                                        dense
                                        outlined
                                        :rules="[required('Full Name')]"
                                    >
                                    </v-text-field>
                                    <span role="alert"><slot name="name-error" class="text-danger"></slot></span>
                                </div>


                                <!-- email or user name -->
                                <div class="my-4">
                                    <v-text-field
                                        label="Email"
                                        name="email"
                                        v-model="registerForm.email"
                                        dense
                                        outlined
                                        append-icon="mdi-email"
                                        :rules="[required('Email'), validEmail()]"
                                    >
                                    </v-text-field>
                                    <span role="alert"><slot name="email-error" class="text-danger"></slot></span>
                                </div>

                            <!-- phone number -->
                            <div class="my-4">
                                <v-text-field
                                    label="Phone Number"
                                    name="phone_number"
                                    v-model="registerForm.phoneNumber"
                                    dense
                                    outlined
                                    :rules="[required('Phone Number')]"
                                >
                                </v-text-field>
                                <span role="alert"><slot name="phone-error" class="text-danger"></slot></span>
                            </div>

                            <!--  gender  -->
                            <div class="my-4">
                            <v-autocomplete
                                v-model="registerForm.gender"
                                :items="genders"
                                outlined
                                dense
                                :clearable="true"
                                class="text-capitalize"
                                id="gender"
                                label="Gender"
                                :rules="[required('Gender')]"
                            >
                            </v-autocomplete>
                            <select
                                hidden
                                v-model="registerForm.gender"
                                name="gender"
                                id="gender" >
                                <option v-for="(gender, index) in genders" :key="index + '-gender-'" :value="gender"></option>
                            </select>
                            <span role="alert"><slot name="gender-error" class="text-danger"></slot></span>
                            </div>

                            <!--  Account Type  -->
                            <div class="my-4">
                            <v-autocomplete
                                v-model="registerForm.user_type_id"
                                item-text="description"
                                item-value="encrypted_id"
                                :items="userTypes"
                                outlined
                                dense
                                :clearable="true"
                                class="text-capitalize"
                                id="user_type_id"
                                label="Account Type"
                                :rules="[required('Account Type')]"
                            >
                            </v-autocomplete>
                            <select
                                hidden
                                v-model="registerForm.user_type_id"
                                name="user_type_id"
                                id="user_type_id">
                                <option v-for="(userType, index) in userTypes" :key="index + '-userType-'" :value="userType.encrypted_id"></option>
                            </select>
                            <span role="alert"><slot name="user-type-error" class="text-danger"></slot></span>
                            </div>

                                <!-- password  -->
                                <div class="my-4">
                                    <v-text-field
                                        label="Password"
                                        name="password"
                                        v-model="registerForm.password"
                                        :type="showPassword ? 'text' : 'password'"
                                        dense
                                        @click:append="showPassword = !showPassword"
                                        outlined
                                        :append-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
                                        :rules="[required('Password'), minLength('Password', 8)]"
                                    >
                                    </v-text-field>
                                    <span role="alert"><slot name="password-error"></slot></span>
                                </div>

                            <!-- Confirm password  -->
                                <div class="my-4">
                                    <v-text-field
                                        label="Confirm Password"
                                        name="password_confirmation"
                                        v-model="registerForm.passwordConfirmation"
                                        :type="showPasswordCon ? 'text' : 'password'"
                                        dense
                                        @click:append="showPasswordCon = !showPasswordCon"
                                        outlined
                                        :append-icon="showPasswordCon ? 'mdi-eye-off' : 'mdi-eye'"
                                        :rules="[required('Confirmation Password'), minLength('Password', 8), passwordMatch(registerForm.password)]"
                                    >
                                    </v-text-field>
                                    <span role="alert"><slot name="password-confirm-error"></slot></span>
                                </div>

                        </v-card-text>
                        <v-card-actions class="mb-3">
                            <v-btn hidden type="submit" ref="submitBtn"> </v-btn>
                            <v-btn
                                @click="login"
                                block
                                color="primary"
                                depressed
                            >{{ processing ? 'Just a moment...' : 'Register'}}
                            </v-btn>
                        </v-card-actions>
                        <div class="justify-content-center w-100 text-center">
                            <p>
                                Already have an account? <a :href="loginUrl">Login</a>
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
        name: "RegisterComponent",
        props: {
            loginUrl: String,
            registerUrl: String,
            userTypes: Array
        },
        data(){
            return{
                ...validation,
                valid: false,
                registerForm: new Form({
                    name: '',
                    password: '',
                    confirmPassword: '',
                    user_type_id: '',
                    email: '',
                    gender: '',
                    phoneNumber: '',
                    passwordConfirmation: ''
                }),
                processing: false,
                showPassword: false,
                showPasswordCon: false,
                genders: [
                    'male',
                    'female',
                    'other'
                ]
            }
        },
        computed: {
            csrf_token(){
                return this.$root.csrf
            }
        },
        methods: {
            login(){
                if(!this.$refs.registerFormRef.validate()) return
                this.processing = true
                this.$refs.submitBtn.$el.click()
            }
        }
    }
</script>

<style scoped>

</style>
