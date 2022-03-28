<template>
    <div>
        <v-data-table
            :headers="headers"
            :items="users.data"
            :items-per-page="users.data.length"
            class="elevation-1"
            hide-default-footer
        >
            <template v-slot:top>
                <v-toolbar
                    flat
                >
                    <v-toolbar-title>Users</v-toolbar-title>
                    <v-divider
                        class="mx-4"
                        inset
                        vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="primary"
                        dark
                        class="mb-2"
                        @click="addNewUser"
                    >
                        New User
                    </v-btn>

                </v-toolbar>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon
                    color="primary"
                    small
                    class="mr-2"
                    @click="editItem(item)"
                >
                    mdi-pencil
                </v-icon>
                <v-icon
                    color="error"
                    small
                    @click="deleteItem(item)"
                >
                    mdi-delete
                </v-icon>
            </template>
            <template v-slot:item.id="{ item, index }">
               {{++index}}
            </template>
            <template v-slot:no-data>
                <h5 class="d-flex justify-content-center text-center">No Data Available</h5>
                <v-btn
                    color="primary"
                    :href="userRoute"
                >
                    Reset
                </v-btn>
            </template>
        </v-data-table>
        <div class="d-flex justify-content-center text-center">
            <slot name="pagination"></slot>
        </div>
        <v-dialog
            v-model="addUserDialog"
            max-width="500px"
            persistent
        >
            <v-card>
                <v-card-title class="d-flex justify-content-center text-center">
                    <span class="text-h5">{{editMode ? 'UPDATE USER' : 'ADD USER'}}</span>
                </v-card-title>

                <v-form ref="userForm" :action="editMode ? `${userRoute}/${userForm.id}` : userRoute" method="POST">
                    <input type="hidden" name="_token" :value="csrf_token" />
                    <input type="hidden" name="_method" :value="editMode ? 'PUT' : 'POST'" />
                    <v-card-text>
                        <!-- full name -->
                        <div class="my-4">
                            <v-text-field
                                label="Full Name"
                                name="name"
                                v-model="userForm.name"
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
                                v-model="userForm.email"
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
                                v-model="userForm.phone_number"
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
                                v-model="userForm.gender"
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
                                v-model="userForm.gender"
                                name="gender"
                                id="gender" >
                                <option v-for="(gender, index) in genders" :key="index + '-gender-'" :value="gender"></option>
                            </select>
                            <span role="alert"><slot name="gender-error" class="text-danger"></slot></span>
                        </div>

                        <!--  Account Type  -->
                        <div class="my-4">
                            <v-autocomplete
                                v-model="userForm.user_type_id"
                                item-text="title"
                                item-value="id"
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
                                v-model="userForm.user_type_id"
                                name="user_type_id"
                                id="user_type_id">
                                <option v-for="(userType, index) in userTypes" :key="index + '-userType-'" :value="userType.id"></option>
                            </select>
                            <span role="alert"><slot name="user-type-error" class="text-danger"></slot></span>
                        </div>

                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="error"
                            text
                            @click="close"
                        >
                            Cancel
                        </v-btn>
                        <v-btn
                            color="blue darken-1"
                            text
                            type="submit"
                        >
                            Save
                        </v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px">
            <v-card>
                <v-form :action="`${userRoute}/${selectedItem.encrypted_id}`" method="POST">
                    <v-card-title class="text-h6 d-flex justify-content-center text-center">Are you sure you want to delete {{selectedItem.name}}?</v-card-title>
                    <input type="hidden" name="_token" :value="csrf_token" />
                    <input type="hidden" name="_method" value="DELETE" />
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" text @click="closeDelete">Cancel</v-btn>
                        <v-btn color="blue darken-1" text type="submit">OK</v-btn>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import validation from "../../services/validation";
    export default {
        name: "UsersComponent",
        props: {
            users: Object,
            userTypes: Array,
            userRoute: String
        },
        data() {
            return{
                currentPage: 1,
                headers:[
                    {text: 'ID', value: 'id', sortable: true},
                    {text: 'Name', value: 'name', sortable: true},
                    {text: 'Email', value: 'email'},
                    {text: 'Phone Number', value: 'phone_number'},
                    {text: 'Gender', value: 'gender'},
                    {text: 'Account Type', value: 'account_type'},
                    {text: 'Actions', value: 'actions'},
                ],
                ...validation,
                userForm: new Form({
                    id: null,
                    name: '',
                    email: '',
                    gender: '',
                    phone_number: '',
                    user_type_id: '',
                }),
                genders: [
                    'male',
                    'female',
                    'other'
                ],
                addUserDialog: false,
                editMode: false,
                dialogDelete: false,
                selectedItem: {}
            }
        },
        computed: {
            csrf_token(){
                return this.$root.csrf
            }
        },
        methods: {
            addNewUser(){
                this.userForm.clear()
                this.userForm.reset()
                this.editMode = false
                this.addUserDialog = true
                setTimeout(() => {
                    this.$refs.userForm.$el.reset()
                }, 1000)

            },
            editItem(item){
                let data = {
                    id: item.id,
                    name: item.name,
                    email: item.email,
                    gender: item.gender,
                    phone_number: item.phone_number,
                    user_type_id: item.user_type.id,
                }
                this.userForm.reset()
                this.userForm.fill(data)
                this.editMode = true
                this.addUserDialog = true
            },
            deleteItem(item){
                this.selectedItem = item
                this.dialogDelete = true
            },
            closeDelete(){
                this.selectedItem = {}
                this.dialogDelete = false
            },
            deleteItemConfirm(){},
            close(){
                this.userForm.reset()
                this.userForm.clear()
                this.editMode = false
                this.addUserDialog = false
            },
        },
        created(){
            this.currentPage = this.users.current_page
        }
    }
</script>

<style scoped>

</style>
