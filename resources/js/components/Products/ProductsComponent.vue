<template>
    <div>
        <v-data-table
            :headers="headers"
            :items="products.data"
            :items-per-page="products.data.length"
            class="elevation-1"
            hide-default-footer
        >
            <template v-slot:top>
                <v-toolbar
                    flat
                >
                    <v-toolbar-title>Products</v-toolbar-title>
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
                        @click="addNewProduct"
                    >
                        New Product
                    </v-btn>

                </v-toolbar>
            </template>
            <template v-slot:item.id="{ item, index }">
                {{++index}}
            </template>
            <template v-slot:item.product_category="{ item }">
                {{item.product_category.name || ''}}
            </template>
            <template v-slot:item.price_grouping="{ item }">
                {{item.price_grouping.amount || ''}}
            </template>
            <template v-slot:item.actions="{ item }">
                <a :href="`${productRoute}/${item.id}`" class="text-decoration-none">
                    <v-icon
                        color="primary"
                        small
                        class="mr-2"
                    >
                        mdi-eye
                    </v-icon>
                </a>
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
                    :href="productRoute"
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
            v-model="addProductDialog"
            max-width="500px"
            persistent
        >
            <v-card>
                <v-card-title class="d-flex justify-content-center text-center">
                    <span class="text-h5">{{editMode ? 'UPDATE PRODUCT' : 'ADD PRODUCT'}}</span>
                </v-card-title>

                <v-form ref="productForm" :action="editMode ? `${productRoute}/${productForm.id}` : productRoute" method="POST">
                    <input type="hidden" name="_token" :value="csrf_token" />
                    <input type="hidden" name="_method" :value="editMode ? 'PUT' : 'POST'" />
                    <v-card-text>
                        <!-- Product name -->
                        <div class="my-4">
                            <v-text-field
                                label="Product Name"
                                name="name"
                                v-model="productForm.name"
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
                                label="Product Description"
                                name="description"
                                v-model="productForm.description"
                                dense
                                outlined
                                :rules="[required('Product Description')]"
                            >
                            </v-textarea>
                            <span role="alert"><slot name="description-error" class="text-danger"></slot></span>
                        </div>

                        <!--  Category  -->
                        <div class="my-4">
                            <v-autocomplete
                                v-model="productForm.product_category_id"
                                :items="productCategories"
                                outlined
                                item-text="name"
                                item-value="id"
                                dense
                                :clearable="true"
                                class="text-capitalize"
                                id="product_category_id"
                                label="Product Category"
                                :rules="[required('Product Category')]"
                            >
                            </v-autocomplete>
                            <select
                                hidden
                                v-model="productForm.product_category_id"
                                name="product_category_id"
                                id="product_category_id" >
                                <option v-for="(productCategory, index) in productCategories" :key="index + '-product_category_id-'" :value="productCategory.id"></option>
                            </select>
                            <span role="alert"><slot name="product-category-error" class="text-danger"></slot></span>
                        </div>

                        <!--  Price Group  -->
                        <div class="my-4">
                            <v-autocomplete
                                v-model="productForm.price_grouping_id"
                                item-text="amount"
                                item-value="id"
                                :items="priceGroupings"
                                outlined
                                dense
                                :clearable="true"
                                class="text-capitalize"
                                id="price_grouping_id"
                                label="Price Group"
                                :rules="[required('Price Group')]"
                            >
                            </v-autocomplete>
                            <select
                                hidden
                                v-model="productForm.price_grouping_id"
                                name="price_grouping_id"
                                id="price_grouping_id">
                                <option v-for="(priceGrouping, index) in priceGroupings" :key="index + '-priceGroupings-'" :value="priceGrouping.id"></option>
                            </select>
                            <span role="alert"><slot name="price-grouping-error" class="text-danger"></slot></span>
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
                <v-form :action="`${productRoute}/${selectedItem.id}`" method="POST">
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
        name: "ProductsComponent",
        props: {
            products: Object,
            productCategories: Array,
            priceGroupings: Array,
            productRoute: String
        },
        data() {
            return{
                currentPage: 1,
                headers:[
                    {text: 'ID', value: 'id', sortable: true},
                    {text: 'Name', value: 'name', sortable: true},
                    {text: 'Product Category', value: 'product_category'},
                    {text: 'Description', value: 'description'},
                    {text: 'Price Grouping', value: 'price_grouping'},
                    {text: 'Actions', value: 'actions'},
                ],
                ...validation,
                productForm: new Form({
                    id: null,
                    product_category_id: '',
                    name: '',
                    description: '',
                    price_grouping_id: '',
                }),
                addProductDialog: false,
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
            addNewProduct(){
                this.productForm.clear()
                this.productForm.reset()
                this.editMode = false
                this.addProductDialog = true
                setTimeout(() => {
                    this.$refs.productForm.$el.reset()
                }, 1000)

            },
            editItem(item){
                let data = {
                    id: item.id,
                    product_category_id: item.product_category_id,
                    name: item.name,
                    description: item.description,
                    price_grouping_id: item.price_grouping_id
                }
                this.productForm.reset()
                this.productForm.fill(data)
                this.editMode = true
                this.addProductDialog = true
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
                this.productForm.reset()
                this.productForm.clear()
                this.editMode = false
                this.addProductDialog = false
            },
        },
        created(){
            this.currentPage = this.products.current_page
        }
    }
</script>

<style scoped>

</style>
