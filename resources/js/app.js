/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

import VueRouter from "vue-router";
import { Form, HasError, AlertError } from "vform";
import moment from "moment";
import Swal from "sweetalert2";
import axios from "axios";
import VueProgressBar from "vue-progressbar";
import Vuelidate from "vuelidate";
import ElementUI from "element-ui";
import i18n from "vue-i18n";
import "element-ui/lib/theme-chalk/index.css";
import locale from "element-ui/lib/locale/lang/en";
import Multiselect from "vue-multiselect";

import "codemirror/lib/codemirror.css";
import "@toast-ui/editor/dist/toastui-editor.css";
import { Editor } from "@toast-ui/vue-editor";

Vue.use(i18n);
Vue.use(ElementUI, { locale });
Vue.config.lang = "en";

export default axios.create({
	baseURL: process.env.MIX_APP_URL,
});

const Toast = Swal.mixin({
	toast: true,
	position: "top-end",
	showConfirmButton: false,
	timer: 3000,
	timerProgressBar: true,
	onOpen: (toast) => {
		toast.addEventListener("mouseenter", Swal.stopTimer);
		toast.addEventListener("mouseleave", Swal.resumeTimer);
	},
});

window.Swal = Swal;
window.Toast = Toast;
window.Form = Form;

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
Vue.component("pagination", require("laravel-vue-pagination"));
Vue.component("multiselect", Multiselect);
Vue.component("editor", Editor);

Vue.use(VueRouter);
Vue.use(VueProgressBar, {
	color: "rgba(143, 255, 199)",
	failedColor: "red",
	height: "3px",
});
Vue.use(Vuelidate);

let cmpAdmin = "./components/admin/";
let routes = [
	{ path: "/admin", component: require(cmpAdmin + "Dashboard").default },
	{
		path: "/admin/dashboard",
		component: require(cmpAdmin + "Dashboard").default,
	},
	{
		path: "/admin/developer",
		component: require(cmpAdmin + "Developer").default,
	},
	{ path: "/admin/users", component: require(cmpAdmin + "Users").default },

	{
		path: "/admin/setting",
		component: require(cmpAdmin + "settings/Setting").default,
	},
	{
		path: "/admin/setting/banner",
		component: require(cmpAdmin + "settings/SettingBanner").default,
	},
	{
		path: "/admin/setting/description",
		component: require(cmpAdmin + "settings/SettingDescription").default,
	},
	{
		path: "/admin/setting/rekening",
		component: require(cmpAdmin + "settings/SettingRekening").default,
	},
	{
		path: "/admin/setting/socmed",
		component: require(cmpAdmin + "settings/SettingSocmed").default,
	},
	{
		path: "/admin/setting/address",
		component: require(cmpAdmin + "settings/SettingAddress").default,
	},
	{
		path: "/admin/setting/cod",
		component: require(cmpAdmin + "settings/SettingCOD").default,
	},
	{
		path: "/admin/setting/footer",
		component: require(cmpAdmin + "settings/SettingFooter").default,
	},
	{
		path: "/admin/testimony",
		component: require(cmpAdmin + "Testimony").default,
	},
	{
		path: "/admin/category",
		component: require(cmpAdmin + "Category").default,
	},
	{
		path: "/admin/product",
		component: require(cmpAdmin + "products/index").default,
	},
	{
		path: "/admin/product/create",
		component: require(cmpAdmin + "products/create").default,
	},
	{
		path: "/admin/product/:id",
		component: require(cmpAdmin + "products/create").default,
	},

	{ path: "/admin/page", component: require(cmpAdmin + "Page").default },
	{ path: "/admin/profile", component: require(cmpAdmin + "Profile").default },
];

const router = new VueRouter({
	mode: "history",
	routes,
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.filter("upText", function (text) {
	if (text) {
		return text.charAt(0).toUpperCase() + text.slice(1);
	}
});

Vue.filter("yesNo", function (text) {
	if (text == 1 || text == "1") {
		return "Yes";
	} else {
		return "No";
	}
});

Vue.filter("isPublished", function (text) {
	if (text == 1 || text == "1") {
		return "Publish";
	} else {
		return "Draft";
	}
});

Vue.filter("myDate", function (created) {
	if (created) {
		return moment(created, "YYYYMMDD").fromNow();
	}
});

window.Fire = new Vue();

Vue.component(
	"passport-clients",
	require("./components/passport/Clients.vue").default
);

Vue.component(
	"passport-authorized-clients",
	require("./components/passport/AuthorizedClients.vue").default
);

Vue.component(
	"passport-personal-access-tokens",
	require("./components/passport/PersonalAccessTokens.vue").default
);

Vue.component(
	"setting-menu",
	require("./components/admin/settings/SettingMenu.vue").default
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.mixin({
	data: function () {
		return {
			get baseURL() {
				return process.env.MIX_APP_URL;
			},
		};
	},
	methods: {
		clearForm(form) {
			Object.keys(form).forEach(function (key, index) {
				if (
					key !== "busy" &&
					key !== "successful" &&
					key !== "errors" &&
					key !== "originalData"
				) {
					form[key] = "";
				}
			});
		},
		imgErrorCondition(event) {
			event.target.src = process.env.MIX_APP_URL + "/img/no_image.jpg";
		},
		getNumber(currentPage, idx) {
			let number = currentPage * 10 - 9 + idx;
			return number;
		},
		formatCurrency(text = 0) {
			if (text) {
				var reverse = text.toString().split("").reverse().join(""),
					currency = reverse.match(/\d{1,3}/g);
				currency = currency.join(".").split("").reverse().join("");

				return "Rp. " + currency;
			}
		},
		prevPage() {
			this.$router.go(-1);
		},
		nextPage() {
			this.$router.go(1);
		},
		yyyymmdd(date, separator) {
			const mm = date.getMonth() + 1; // getMonth() is zero-based
			const dd = date.getDate();

			return [
				date.getFullYear(),
				(mm > 9 ? "" : "0") + mm,
				(dd > 9 ? "" : "0") + dd,
			].join(separator);
		},
		yyyymmddTime(date, separator) {
			const mm = date.getMonth() + 1; // getMonth() is zero-based
			const dd = date.getDate();

			return (
				[
					date.getFullYear(),
					(mm > 9 ? "" : "0") + mm,
					(dd > 9 ? "" : "0") + dd,
				].join(separator) +
				" " +
				[
					`${date.getHours()}`.length === 1
						? `0${date.getHours()}`
						: `${date.getHours()}`,
					`${date.getMinutes()}`.length === 1
						? `0${date.getMinutes()}`
						: `${date.getMinutes()}`,
					`${date.getSeconds()}`.length === 1
						? `0${date.getSeconds()}`
						: `${date.getSeconds()}`,
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
					(dd > 9 ? "" : "0") + dd,
				].join(separator) +
				" " +
				[
					`${date.getHours()}`.length === 1
						? `0${date.getHours()}`
						: `${date.getHours()}`,
					`${date.getMinutes()}`.length === 1
						? `0${date.getMinutes()}`
						: `${date.getMinutes()}`,
				].join(":")
			);
		},

		myDate(created) {
			if (created) {
				return moment(created, "YYYYMMDD").fromNow();
			}
		},
		sanitizeTitle: function (title) {
			var slug = "";
			// Change to lower case
			var titleLower = title.toLowerCase();
			// Letter "e"
			slug = titleLower.replace(/e|é|è|ẽ|ẻ|ẹ|ê|ế|ề|ễ|ể|ệ/gi, "e");
			// Letter "a"
			slug = slug.replace(/a|á|à|ã|ả|ạ|ă|ắ|ằ|ẵ|ẳ|ặ|â|ấ|ầ|ẫ|ẩ|ậ/gi, "a");
			// Letter "o"
			slug = slug.replace(/o|ó|ò|õ|ỏ|ọ|ô|ố|ồ|ỗ|ổ|ộ|ơ|ớ|ờ|ỡ|ở|ợ/gi, "o");
			// Letter "u"
			slug = slug.replace(/u|ú|ù|ũ|ủ|ụ|ư|ứ|ừ|ữ|ử|ự/gi, "u");
			// Letter "d"
			slug = slug.replace(/đ/gi, "d");
			// Trim the last whitespace
			slug = slug.replace(/\s*$/g, "");
			// Change whitespace to "-"
			slug = slug.replace(/\s+/g, "-");

			return slug;
		},
	},
});

const app = new Vue({
	el: "#app",
	router,
	data: {
		search: "",
		baseURL: process.env.MIX_APP_URL,
	},
	methods: {
		searchit() {
			Fire.$emit("searching");
		},
	},
});

// const app = new Vue({
//     router
//   }).$mount('#app')
