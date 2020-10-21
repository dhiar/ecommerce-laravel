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

let cmpAdmin = './components/admin/'
let routes = [
	{ path : '/admin', component : require(cmpAdmin + 'Dashboard').default },
	{ path : '/admin/dashboard', component : require(cmpAdmin + 'Dashboard').default },
	{ path : '/admin/developer', component : require(cmpAdmin + 'Developer').default },
	{ path : '/admin/users', component : require(cmpAdmin + 'Users').default },
	{ path : '/admin/setting', component : require(cmpAdmin + 'Setting').default },
	{ path : '/admin/profile', component : require(cmpAdmin + 'Profile').default }
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


Vue.filter('upText', function (text) {
	if (text) {
		return text.charAt(0).toUpperCase() + text.slice(1)
	}
})

Vue.filter('myDate', function (created) {
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

Vue.component('setting-menu', require('./components/admin/SettingMenu.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
	el: '#app',
	router,
	data: {
		search: '',
		baseURL: process.env.MIX_APP_URL
	},
	methods:
	{
		searchit() {
			Fire.$emit('searching')
		}
	}
});


// const app = new Vue({
//     router
//   }).$mount('#app')
