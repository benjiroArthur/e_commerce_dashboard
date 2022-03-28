<template>
    <div>
        <v-data-table
            :headers="headers"
            :items="productCategories.data"
            :items-per-page="productCategories.data.length"
            class="elevation-1"
            hide-default-footer
        >
            <template v-slot:top>
                <v-toolbar
                    flat
                >
                    <v-toolbar-title>Product Categories</v-toolbar-title>
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
                        @click="addNewCategory"
                    >
                        New Product Category
                    </v-btn>

                </v-toolbar>
            </template>
            <template v-slot:item.id="{ item, index }">
                {{++index}}
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
            <template v-slot:no-data>
                <div class="py-3 my-3">
                    <h5 class="d-flex justify-content-center text-center">No Data Available</h5>
                    <v-btn
                        color="primary"
                        :href="productCategoryRoute"
                    >
                        Reset
                    </v-btn>
                </div>
            </template>
        </v-data-table>
        <div class="d-flex justify-content-center text-center">
            <slot name="pagination"></slot>
        </div>
        <v-dialog
            v-model="addProductCategoryDialog"
            max-width="500px"
            persistent
        >
            <v-card>
                <v-card-title class="d-flex justify-content-center text-center">
                    <span class="text-h5">{{editMode ? 'UPDATE USER' : 'ADD PRODUCT'}}</span>
                </v-card-title>

                <v-form ref="productCateForm" :action="editMode ? `${productCategoryRoute}/${productCateForm.id}` : userRoute" method="POST">
                    <input type="hidden" name="_token" :value="csrf_token" />
                    <input type="hidden" name="_method" :value="editMode ? 'PUT' : 'POST'" />
                    <v-card-text>
                        <!-- Product name -->
                        <div class="my-4">
                            <v-text-field
                                label="Product Name"
                                name="name"
                                v-model="productCateForm.name"
                                dense
                                outlined
                                :rules="[required('Product Name')]"
                            >
                            </v-text-field>
                            <span role="alert"><slot name="name-error" class="text-danger"></slot></span>
                        </div>


                        <!-- Product Description -->
                        <div class="my-4">
                            <v-textarea
                                label="Category Description"
                                name="description"
                                v-model="productCateForm.description"
                                dense
                                outlined
                                :rules="[required('Category Description')]"
                            >
                            </v-textarea>
                            <span role="alert"><slot name="description-error" class="text-danger"></slot></span>
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
                <v-form :action="`${productCategoryRoute}/${selectedItem.id}`" method="POST">
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
        name: "ProductCategoryComponent",
        props: {
            productCategories: Object,
            productCategoryRoute: String
        },
        data() {
            return{
                currentPage: 1,
                headers:[
                    {text: 'ID', value: 'id', sortable: true},
                    {text: 'Name', value: 'name', sortable: true},
                    {text: 'Description', value: 'description'},
                    {text: 'Actions', value: 'actions'},
                ],
                ...validation,
                productCateForm: new Form({
                    id: null,
                    name: '',
                    description: '',
                }),
                addProductCategoryDialog: false,
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
            addNewCategory(){
                this.productCateForm.clear()
                this.productCateForm.reset()
                this.editMode = false
                this.addProductCategoryDialog = true
                setTimeout(() => {
                    this.$refs.productCateForm.$el.reset()
                }, 1000)

            },
            editItem(item){
                let data = {
                    id: item.id,
                    name: item.name,
                    description: item.description,
                }
                this.productCateForm.reset()
                this.productCateForm.fill(data)
                this.editMode = true
                this.addProductCategoryDialog = true
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
                this.productCateForm.reset()
                this.productCateForm.clear()
                this.editMode = false
                this.addProductCategoryDialog = false
            },
        },
        created(){
            this.currentPage = this.products.current_page
        }
    }
</script>

<style scoped>

</style>
