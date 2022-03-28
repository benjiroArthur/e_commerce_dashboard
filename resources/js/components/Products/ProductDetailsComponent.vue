<template>
    <div>
        <div class="container-fluid row">
            <div class="col-md-12"><v-btn :href="productsRoute" color="primary"><v-icon>mdi-arrow-left</v-icon>Back</v-btn></div>
            <div class="col-md-6">
                <v-card elevation="5">
                    <v-card-title>Product Details</v-card-title>
                    <v-card-text>
                        <div class="row d-flex">
                            <div class="col-md-6 px-3 text-bold text-black">Product Name</div>
                            <div class="col-md-6 px-3">{{product.name}}</div>
                        </div>
                        <v-divider></v-divider>
                        <div class="row d-flex">
                            <div class="col-md-6 px-3 text-bold text-black">Product Description</div>
                            <div class="col-md-6 px-3">{{product.description}}</div>
                        </div>
                        <v-divider></v-divider>
                        <!--<div class="row d-flex">
                            <div class="col-md-6 px-3 text-bold text-black">Product Discount</div>
                            <div class="col-md-6 px-3">{{product.discounts ? product.discounts.rate : 'No Discount'}} <v-icon class="pl-3" @click="editDiscount">mdi-pencil</v-icon></div>
                        </div>
                        <v-divider></v-divider>-->
                        <div class="row d-flex">
                            <div class="col-md-6 px-3 text-bold text-black">Product Price</div>
                            <div class="col-md-6 px-3">{{product.price_grouping ? product.price_grouping.amount : 'None'}}</div>
                        </div>
                        <v-divider></v-divider>
                        <div class="row d-flex">
                            <div class="col-md-6 px-3 text-bold text-black">Product Category</div>
                            <div class="col-md-6 px-3">{{product.product_category ? product.product_category.name : 'None'}}</div>
                        </div>
                        <v-divider></v-divider>
                        <div class="row d-flex">
                            <div class="col-md-6 px-3 text-bold text-black">Vendor</div>
                            <div class="col-md-6 px-3">{{product.user ? product.user.name : 'None'}}</div>
                        </div>

                    </v-card-text>
                </v-card>
            </div>
            <div class="col-md-6">
                <v-card elevation="5">
                    <v-card-title class="d-flex justify-content-between"><span>Product Discount</span> <v-btn color="primary" @click="addDiscount"><v-icon>mdi-plus-circle</v-icon>Add</v-btn></v-card-title>
                    <v-card-text>
                        <div class="row d-flex" v-for="(discount, index) in product.discounts" :key="index+'-disc'">
                            <div class="col-md-8">Discount Rate: {{discount.rate}}</div>
                            <div class="col-md-4">
                                <v-icon
                                    color="error"
                                    small
                                    @click="deleteDiscount(discount)"
                                >mdi-delete</v-icon>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </div>
            <div class="col-md-12">
                <v-card elevation="5">
                    <v-card-title>Product Image</v-card-title>
                    <v-card-text>

                    </v-card-text>
                </v-card>
            </div>
        </div>
        <v-dialog
            v-model="addImageDialog"
            max-width="500px"
            persistent
        >
            <v-card>
                <v-card-title class="d-flex justify-content-center text-center">
                    <span class="text-h5">ADD IMAGE</span>
                </v-card-title>

                <v-form ref="productForm" :action="productImageRoute" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" :value="csrf_token" />
                    <v-card-text>
                        <!-- Product image -->
                        <div class="my-4">
                            <v-file-input
                                label="Product Images"
                                multiple
                                name="images[]"
                                v-model="productImageForm.images"
                                dense
                                outlined
                                :rules="[required('Product Image')]"
                            >
                            </v-file-input>
                            <span role="alert"><slot name="images-error" class="text-danger"></slot></span>
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
        <v-dialog
            v-model="addDiscountDialog"
            max-width="500px"
            persistent
        >
            <v-card>
                <v-card-title class="d-flex justify-content-center text-center">
                    <span class="text-h5">ADD DISCOUNT</span>
                </v-card-title>

                <v-form ref="discountForm" :action="productDiscountRoute" method="POST">
                    <input type="hidden" name="_token" :value="csrf_token" />
                    <input type="hidden" name="product_id" :value="product.id" />
                    <v-card-text>

                        <!--  Product Discount  -->
                        <div class="my-4">
                            <v-autocomplete
                                v-model="discountForm.discount_id"
                                item-text="rate"
                                item-value="id"
                                :items="discounts"
                                outlined
                                dense
                                :clearable="true"
                                class="text-capitalize"
                                id="price_grouping_id"
                                label="Discount Rate"
                                :rules="[required('Discount Rate')]"
                            >
                            </v-autocomplete>
                            <select
                                hidden
                                v-model="discountForm.discount_id"
                                name="discount_id"
                                id="price_grouping_id">
                                <option v-for="(discount, index) in discounts" :key="index + '-discount-'" :value="discount.id"></option>
                            </select>
                            <span role="alert"><slot name="price-grouping-error" class="text-danger"></slot></span>
                        </div>
                        <div class="my-4 row">
                            <div class="col-md-6">
                                <v-menu
                                    ref="startDateMenu"
                                    v-model="startDateMenu"
                                    :close-on-content-click="false"
                                    :return-value.sync="discountForm.start_date"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="auto"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="discountForm.start_date"
                                            label="Start Date"
                                            name="start_date"
                                            prepend-inner-icon="mdi-calendar"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                            dense
                                            outlined
                                            :rules="[required('Start Date')]"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="discountForm.start_date"
                                        no-title
                                        scrollable
                                    >
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="startDateMenu = false"
                                        >
                                            Cancel
                                        </v-btn>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="$refs.startDateMenu.save(discountForm.start_date)"
                                        >
                                            OK
                                        </v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </div>
                            <div class="col-md-6">
                                <v-menu
                                    ref="endDateMenu"
                                    v-model="endDateMenu"
                                    :close-on-content-click="false"
                                    :return-value.sync="discountForm.end_date"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="auto"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="discountForm.end_date"
                                            name="end_date"
                                            label="End Date"
                                            prepend-inner-icon="mdi-calendar"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                            dense
                                            outlined
                                            :rules="[required('End Date')]"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="discountForm.end_date"
                                        no-title
                                        scrollable
                                    >
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="endDateMenu = false"
                                        >
                                            Cancel
                                        </v-btn>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="$refs.endDateMenu.save(discountForm.end_date)"
                                        >
                                            OK
                                        </v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </div>
                        </div>

                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="error"
                            text
                            @click="closeDiscount"
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
        <!--<v-dialog v-model="dialogDelete" max-width="500px">
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
        </v-dialog>-->
    </div>
</template>

<script>
    import validation from "../../services/validation";
    export default {
        name: "ProductDetailsComponent",
        props: {
            product: Object,
            discounts: Array,
            productImageRoute: String,
            productDiscountRoute: String,
            productsRoute: String
        },
        data() {
            return{
                ...validation,
                addImageDialog: false,
                addDiscountDialog: false,
                editMode: false,
                dialogDelete: false,
                selectedItem: {},
                discountForm: new Form({
                    discount_id: null,
                    start_date: null,
                    end_date: null
                }),
                productImageForm: new Form({
                    images: []
                }),
                startDateMenu: false,
                endDateMenu: false,
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
            deleteDiscount(item){

            },
            deleteItem(item){
                this.selectedItem = item
                this.dialogDelete = true
            },
            closeDelete(){
                this.selectedItem = {}
                this.dialogDelete = false
            },
            addDiscount(){
                this.addDiscountDialog = true
            },
            closeDiscount(){
                this.discountForm.reset()
                this.addDiscountDialog = false
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
        }
    }
</script>

<style scoped>

</style>
