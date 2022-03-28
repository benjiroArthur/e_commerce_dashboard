<template>
    <div>
        <v-data-table
            :headers="headers"
            :items="statuses"
            :items-per-page="statuses.length"
            class="elevation-1"
            hide-default-footer
        >
            <template v-slot:top>
                <v-toolbar
                    flat
                >
                    <v-toolbar-title>Progress Status</v-toolbar-title>
                    <v-divider
                        class="mx-4"
                        inset
                        vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <!--<v-btn
                        color="primary"
                        dark
                        class="mb-2"
                        @click="addNewUser"
                    >
                        New Price
                    </v-btn>-->

                </v-toolbar>
            </template>
            <template v-slot:item.id="{ item, index }">
                {{++index}}
            </template>
            <!--<template v-slot:item.actions="{ item }">
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
            </template>-->
            <template v-slot:no-data >
                <div class="my-3 py-3">
                    <h5 class="d-flex justify-content-center text-center">No Data Available</h5>
                    <v-btn
                        color="primary"
                        :href="statusRoute"
                    >
                        Reset
                    </v-btn>
                </div>
            </template>
        </v-data-table>
        <!--<div class="d-flex justify-content-center text-center">
            <slot name="pagination"></slot>
        </div>-->
        <v-dialog
            v-model="addStatusDialog"
            max-width="500px"
            persistent
        >
            <v-card>
                <v-card-title class="d-flex justify-content-center text-center">
                    <span class="text-h5">{{editMode ? 'UPDATE PRICE' : 'ADD PRICE'}}</span>
                </v-card-title>

                <v-form ref="statusForm" :action="editMode ? `${statusRoute}/${statusForm.id}` : statusRoute" method="POST">
                    <input type="hidden" name="_token" :value="csrf_token" />
                    <input type="hidden" name="_method" :value="editMode ? 'PUT' : 'POST'" />
                    <v-card-text>
                        <!-- Product name -->
                        <div class="my-4">
                            <v-text-field
                                label="Amount"
                                name="amount"
                                v-model="statusForm.amount"
                                dense
                                outlined
                                :rules="[required('Amount')]"
                            >
                            </v-text-field>
                            <span role="alert"><slot name="amount-error" class="text-danger"></slot></span>
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
                <v-form :action="`${statusRoute}/${selectedItem.id}`" method="POST">
                    <v-card-title class="text-h6 d-flex justify-content-center text-center">Are you sure you want to delete {{selectedItem.amount}}?</v-card-title>
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
        name: "StatusComponent",
        props: {
            statuses: Array,
            statusRoute: String,
        },
        data() {
            return{
                currentPage: 1,
                headers:[
                    {text: 'ID', value: 'id', sortable: true},
                    {text: 'Name', value: 'name', sortable: true},
                    /*{text: 'Actions', value: 'actions', align: 'end'},*/
                ],
                ...validation,
                statusForm: new Form({
                    id: null,
                    amount: '',
                }),
                addStatusDialog: false,
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
                this.statusForm.clear()
                this.statusForm.reset()
                this.editMode = false
                this.addStatusDialog = true
                setTimeout(() => {
                    this.$refs.statusForm.$el.reset()
                }, 1000)

            },
            editItem(item){
                let data = {
                    id: item.id,
                    amount: item.amount
                }
                this.statusForm.reset()
                this.statusForm.fill(data)
                this.editMode = true
                this.addStatusDialog = true
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
                this.statusForm.reset()
                this.statusForm.clear()
                this.editMode = false
                this.addStatusDialog = false
            },
        },
        created(){
        }
    }
</script>

<style scoped>

</style>
