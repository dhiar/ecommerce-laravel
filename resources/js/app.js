/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router'
import { Form, HasError, AlertError } from 'vform'
import moment from 'moment'
import Swal from 'sweetalert2'
import axios from 'axios'
import VueProgressBar from 'vue-progressbar'
import Vuelidate from 'vuelidate'
import ElementUI from "element-ui";
import i18n from "vue-i18n";
import "element-ui/lib/theme-chalk/index.css";
import locale from "element-ui/lib/locale/lang/en";

Vue.use(i18n);
Vue.use(ElementUI, { locale });
Vue.config.lang = "en";

export default axios.create({
    baseURL: process.env.MIX_APP_URL
})

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

window.Swal = Swal
window.Toast = Toast
window.Form = Form

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component('pagination', require('laravel-vue-pagination'));

Vue.use(VueRouter)
Vue.use(VueProgressBar, {
    color: 'rgba(143, 255, 199)',
    failedColor: 'red',
    height: '3px'
})
Vue.use(Vuelidate)

let cmpAdmin = './components/admin/'
let routes = [
    { path: '/admin', component: require(cmpAdmin + 'Dashboard').default },
    { path: '/admin/dashboard', component: require(cmpAdmin + 'Dashboard').default },
    { path: '/admin/developer', component: require(cmpAdmin + 'Developer').default },
    { path: '/admin/users', component: require(cmpAdmin + 'Users').default },

    { path: '/admin/setting', component: require(cmpAdmin + 'settings/Setting').default },
    { path: '/admin/setting/banner', component: require(cmpAdmin + 'settings/SettingBanner').default },
    { path: '/admin/setting/description', component: require(cmpAdmin + 'settings/SettingDescription').default },
    { path: '/admin/setting/rekening', component: require(cmpAdmin + 'settings/SettingRekening').default },
    { path: '/admin/setting/sosmed', component: require(cmpAdmin + 'settings/SettingSosmed').default },
    { path: '/admin/setting/address', component: require(cmpAdmin + 'settings/SettingAddress').default },
    { path: '/admin/setting/delivery', component: require(cmpAdmin + 'settings/SettingDelivery').default },
    { path: '/admin/setting/cod', component: require(cmpAdmin + 'settings/SettingCOD').default },
    { path: '/admin/setting/footer', component: require(cmpAdmin + 'settings/SettingFooter').default },

    { path: '/admin/profile', component: require(cmpAdmin + 'Profile').default }
]

const router = new VueRouter({
    mode: 'history',
    routes
})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


Vue.filter('upText', function(text) {
    if (text) {
        return text.charAt(0).toUpperCase() + text.slice(1)
    }
})

Vue.filter('myDate', function(created) {
    if (created) {
        return moment(created, "YYYYMMDD").fromNow();
    }
})

window.Fire = new Vue();

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

Vue.component('setting-menu', require('./components/admin/settings/SettingMenu.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.mixin({
    data: function() {
        return {
            get baseURL() {
                return process.env.MIX_APP_URL;
            }
        };
    },
    methods: {
        imgErrorCondition(event){
            event.target.src = process.env.MIX_APP_URL + "/img/layout/no_image.jpg"
        },
        getNumber(currentPage, idx) {
            let number = (currentPage * 10) - 9 + idx
            return number
        },
        formatCurrency(text = 0) {
            if (text) {
                var reverse = text
                    .toString()
                    .split("")
                    .reverse()
                    .join(""),
                    currency = reverse.match(/\d{1,3}/g);
                currency = currency
                    .join(".")
                    .split("")
                    .reverse()
                    .join("");
        
                return "Rp. " + currency;
            }
        },
        prevPage() {
            this.$router.go(-1)
        },
        nextPage() {
            this.$router.go(1)
        },
        yyyymmdd(date, separator) {
            const mm = date.getMonth() + 1; // getMonth() is zero-based
            const dd = date.getDate();

            return [
                date.getFullYear(),
                (mm > 9 ? "" : "0") + mm,
                (dd > 9 ? "" : "0") + dd
            ].join(separator);
        },
        yyyymmddTime(date, separator) {
            const mm = date.getMonth() + 1; // getMonth() is zero-based
            const dd = date.getDate();

            return (
                [
                    date.getFullYear(),
                    (mm > 9 ? "" : "0") + mm,
                    (dd > 9 ? "" : "0") + dd
                ].join(separator) +
                " " + [
                    `${date.getHours()}`.length === 1 ?
                    `0${date.getHours()}` :
                    `${date.getHours()}`,
                    `${date.getMinutes()}`.length === 1 ?
                    `0${date.getMinutes()}` :
                    `${date.getMinutes()}`,
                    `${date.getSeconds()}`.length === 1 ?
                    `0${date.getSeconds()}` :
                    `${date.getSeconds()}`
                ].join(":")
            );
        },
        yyyymmddhhii(date, separator) {
            const mm = date.getMonth() + 1; // getMonth() is zero-based
            const dd = date.getDate();

            return (
                [
                    date.getFullYear(),
                    (mm > 9 ? "" : "0") + mm,
                    (dd > 9 ? "" : "0") + dd
                ].join(separator) +
                " " + [
                    `${date.getHours()}`.length === 1 ?
                    `0${date.getHours()}` :
                    `${date.getHours()}`,
                    `${date.getMinutes()}`.length === 1 ?
                    `0${date.getMinutes()}` :
                    `${date.getMinutes()}`
                ].join(":")
            );
        },
        
        myDate(created) {
            if (created) {
                return moment(created, "YYYYMMDD").fromNow();
            }
        },
    }
});

const app = new Vue({
    el: '#app',
    router,
    data: {
        search: '',
        baseURL: process.env.MIX_APP_URL
    },
    methods: {
        searchit() {
            Fire.$emit('searching')
        }
    }
});


// const app = new Vue({
//     router
//   }).$mount('#app')