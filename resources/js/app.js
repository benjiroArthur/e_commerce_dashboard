/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import vuetify from "./plugins/vuetify";
require('./bootstrap');

window.Vue = require('vue').default;
window.$ = window.jQuery = require('jquery');

import { Form, HasError, AlertError, AlertErrors, AlertSuccess } from "vform";

window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
Vue.component(AlertErrors.name, AlertErrors);
Vue.component(AlertSuccess.name, AlertSuccess);
window.Fire = new Vue();

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('admin-user-layout', require('./components/Layout/AdminUserLayout').default);
Vue.component('regular-user-layout', require('./components/Layout/RegularUserLayout').default);
Vue.component('form-layout', require('./components/Layout/FormLayout').default);
Vue.component('login-component', require('./components/Auth/LoginComponent').default);
Vue.component('register-component', require('./components/Auth/RegisterComponent').default);
Vue.component('users-component', require('./components/Users/UsersComponent').default);
Vue.component('products-component', require('./components/Products/ProductsComponent').default);
Vue.component('product-details-component', require('./components/Products/ProductDetailsComponent').default);
Vue.component('price-grouping-component', require('./components/PriceGrouping/PriceGroupingComponent').default);
Vue.component('products-category-component', require('./components/ProductCategory/ProductCategoryComponent').default);
Vue.component('status-component', require('./components/Status/StatusComponent').default);
Vue.component('payment-type-component', require('./components/PaymentType/PaymentTypeComponent').default);
Vue.component('discount-component', require('./components/Discount/DiscountComponent').default);
Vue.component('orders-component', require('./components/Orders/OrdersComponent').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const app = new Vue({
    el: '#app',
    vuetify,
    data: () => ({
        csrf: document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),

        authUser: JSON.parse(document
            .querySelector('meta[name="authUser"]')
            .getAttribute("content"))
    }),
    created() {}
});
