<template>
	<div class="container">
		<button class="btn btn-info" @click="toggleAccord" v-if="expanded">
			Collapse all
		</button>
		<button class="btn btn-info" @click="toggleAccord" v-if="!expanded">
			Expand all
		</button>
		<div class="accordion">
			<accordioncard
				v-for="kit in kits"
				:card-data="kit"
				:key="kit.code"
				:active="expanded"
			></accordioncard>
		</div>

		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<h5>
							Invoice :
							<span style="font-weight: bold; color: green; font-size: 14px;">{{
								form.invoice
							}}</span>
						</h5>
					</div>

					<div class="card-body">
						<!-- <multiselect
											v-model="province"
											:options="data_province"
											placeholder="Select one"
											label="name"
											track-by="id"
											:searchable="true"
											:max-height="150"
											:max="3"
											@select="onSelectProvince"
										></multiselect> -->
						<vsa-list>
							<!-- Here you can use v-for to loop through items  -->
							<vsa-item v-for="(item, idx) in ordersAddress" :key="idx">
								<vsa-heading v-if="item.id < 5">
									{{ item.title }}
								</vsa-heading>
								<vsa-content v-if="item.id == 0">
									<multiselect
										v-if="data_province.length > 0"
										v-model="province"
										:options="data_province"
										placeholder="Select one"
										label="name"
										track-by="id"
										:searchable="true"
										:max-height="150"
										:max="3"
										@select="onSelectProvince"
									></multiselect>
									<table class="table table-hover">
										<tr
											v-for="(productDetail, idDetail) in item.data"
											:key="idDetail"
										>
											<td class="vertical-align:middle;">-</td>
											<td>{{ productDetail.relationships.product.name }}</td>
											<td>
												<img
													v-if="productDetail.relationships.product.image"
													:src="productDetail.relationships.product.image"
													class="img-fluid text-center"
													@error="imgErrorCondition"
													style="width: 250px; height: 250px;"
												/>
											</td>
										</tr>
									</table>
								</vsa-content>
								<vsa-content v-if="item.id == 1">
									<table class="table table-hover">
										<tr>
											<th style="width: 30%;">Total Berat</th>
											<td>{{ formatWeight(item.data.total_weight) }}</td>
										</tr>
										<tr>
											<th style="width: 30%;">Total Bea Produk</th>
											<td>{{ formatCurrency(item.data.total_price) }}</td>
										</tr>
									</table>
								</vsa-content>
								<vsa-content v-if="item.id == 2">
									<table class="table table-hover">
										<tr>
											<th style="width: 30%;">Nama Penerima</th>
											<td>{{ formatName(item.data.invoice) }}</td>
										</tr>
										<tr class="card-header">
											<th style="width: 30%;">Alamat Penerima</th>
											<td></td>
										</tr>
										<tr>
											<th style="width: 30%;">
												Nama Desa RT.RW. / Jalan / Nomor
											</th>
											<td>{{ item.data.relationships.address.name }}</td>
										</tr>
										<tr>
											<th style="width: 30%;">Propinsi</th>
											<td
												id="province"
												v-if="item.data.relationships.address.province"
											>
												{{ item.data.relationships.address.province }}
											</td>
											<td v-else>
												<!-- coba province di ganti dengan province -->
											</td>
										</tr>
										<tr>
											<th style="width: 30%;">Kabupaten</th>
											<td id="city">
												{{
													item.data.relationships.address.city
														? item.data.relationships.address.city
														: "-"
												}}
											</td>
										</tr>
										<tr>
											<th style="width: 30%;">Kecamatan</th>
											<td id="district">
												{{
													item.data.relationships.address.district
														? item.data.relationships.address.district
														: "-"
												}}
											</td>
										</tr>
										<tr>
											<th style="width: 30%;"></th>
											<td>
												<button class="btn btn-success">
													Update Area Penerima
												</button>
											</td>
										</tr>
									</table>
								</vsa-content>
								<vsa-content v-if="item.id == 3">
									<table class="table table-hover">
										<tr>
											<th style="width: 30%;">Bea Kirim</th>
											<td>{{ formatCurrency(item.data.shipping_cost) }}</td>
										</tr>
										<tr>
											<th style="width: 30%;">Status Kirim</th>
											<td>
												{{ item.data.relationships.delivery_status.name }}
											</td>
										</tr>
									</table>
								</vsa-content>
							</vsa-item>
						</vsa-list>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import Accordioncard from "./Accordioncard.vue";
import { required, minLength, maxLength } from "vuelidate/lib/validators";

import {
	VsaList,
	VsaItem,
	VsaHeading,
	VsaContent,
	VsaIcon,
} from "vue-simple-accordion";
import "vue-simple-accordion/dist/vue-simple-accordion.css";

export default {
	components: {
		VsaList,
		VsaItem,
		VsaHeading,
		VsaContent,
		VsaIcon,
		accordioncard: Accordioncard,
	},
	data() {
		return {
			expanded: false,
			show: true,
			kits: [
				{
					code: "A",
					name: "Campaign kit: 000002",
					status: "active",
					itemCount: 8,
					items: [
						{ id: "R1234_001", name: "Barker Card- No Frills" },
						{ id: "R1234_002", name: "Barker Card- No Frills" },
						{ id: "R1234_005", name: "Barker Card with flag - Clear PVC" },
						{ id: "R1234_002", name: "Acetate - Large" },
						{ id: "R1234_002", name: "Hardware - PRODUCE POD- DISCOUNT" },
						{ id: "R1234_002", name: "Barker Card- No Frills" },
						{ id: "R1234_002", name: "Meat Divider - Short" },
					],
					destinations: [
						{ code: "YIG", addresss: "31 NINTH ST E	ON", lang: "English" },
						{ code: "YIG", addresss: "31 NINTH ST E	ON", lang: "English" },
						{ code: "YIG", addresss: "31 NINTH ST E	ON", lang: "English" },
						{ code: "YIG", addresss: "31 NINTH ST E	ON", lang: "English" },
					],
				},
				{
					code: "B",
					name: "RCSS ON",
					status: "active",
					itemCount: 12,
					items: [
						{ id: "R1234_001", name: "Barker Card- No Frills" },
						{ id: "R1234_002", name: "Barker Card- No Frills" },
						{ id: "R1234_005", name: "Barker Card with flag - Clear PVC" },
						{ id: "R1234_002", name: "Acetate - Large" },
						{ id: "R1234_002", name: "Hardware - PRODUCE POD- DISCOUNT" },
						{ id: "R1234_002", name: "Barker Card- No Frills" },
						{ id: "R1234_002", name: "Meat Divider - Short" },
						{ id: "R1234_002", name: "Hardware - PRODUCE POD- DISCOUNT" },
						{ id: "R1234_002", name: "Barker Card- No Frills" },
						{ id: "R1234_002", name: "Meat Divider - Short" },
						{ id: "R1234_002", name: "Barker Card- No Frills" },
					],
					destinations: [
						{ code: "YIG", addresss: "31 NINTH ST E	ON", lang: "English" },
						{ code: "YIG", addresss: "31 NINTH ST E	ON", lang: "English" },
						{ code: "YIG", addresss: "31 NINTH ST E	ON", lang: "English" },
						{ code: "YIG", addresss: "31 NINTH ST E	ON", lang: "English" },
					],
				},
				{
					code: "C",
					name: "RCSS W",
					status: "inactive",
					itemCount: 1,
					items: [
						{ id: "R1234_001", name: "Barker Card- No Frills" },
						{ id: "R1234_002", name: "Barker Card- No Frills" },
					],
				},
				{
					code: "D",
					name: "Campaign kit: 000008",
					status: "active",
					itemCount: 0,
				},
				{
					code: "AA",
					name: "Custom kit name",
					status: "active",
					itemCount: 8,
					items: [
						{ id: "R1234_001", name: "Barker Card- No Frills" },
						{ id: "R1234_002", name: "Barker Card- No Frills" },
						{ id: "R1234_005", name: "Barker Card with flag - Clear PVC" },
						{ id: "R1234_002", name: "Acetate - Large" },
						{ id: "R1234_002", name: "Hardware - PRODUCE POD- DISCOUNT" },
						{ id: "R1234_002", name: "Barker Card- No Frills" },
						{ id: "R1234_002", name: "Meat Divider - Short" },
						{ id: "R1234_002", name: "Hardware - PRODUCE POD- DISCOUNT" },
						{ id: "R1234_002", name: "Barker Card- No Frills" },
						{ id: "R1234_002", name: "Meat Divider - Short" },
						{ id: "R1234_002", name: "Barker Card- No Frills" },
					],
				},
			],

			orders: [],
			ordersAddress: [],
			submitted: false,
			endpoint: "/api/transactions-by-invoice",
			page: "order",
			list_delivery_status: [
				{ id: 1, name: "Verifikasi" },
				{ id: 2, name: "Dikemas" },
				{ id: 3, name: "Dikirim" },
				{ id: 4, name: "Diterima" },
				{ id: 5, name: "Cancel" },
			],
			form: new Form({
				id: "",
				invoice: "",
				shipping_cost: 0,
				id_delivery_status: "1",
				total_weight: 0,
				total_price: 0,
				id_admin_owner: "",
				token: null,
				token_created_at: null,
				relationships: {
					address: {
						id: "",
						name: "",
						province_id: "",
						district_id: "",
						province: "",
						city: "",
						district: "",
					},
					delivery_status: {},
					transaction_details: [],
				},
			}),

			province: { id: null, name: "" },
			data_province: [],
			city: { id: null, name: "" },
			data_city: [],
			district: { id: null, name: "" },
			data_district: [],
		};
	},
	mounted() {
		this.fetchData();
		this.fetchData(true);
	},
	methods: {
		toggleAccord: function () {
			if (this.expanded) {
				this.$emit("activeFalse");
			} else {
				this.$emit("activeTrue");
			}
			this.expanded = !this.expanded;
		},
		async fetchData(show_address = null) {
			const self = this;
			let tempData = [];

			if (localStorage.invoice) {
				await axios
					.get(self.endpoint, {
						params: {
							invoice: localStorage.invoice,
							show_address: show_address,
						},
					})
					.then(({ data }) => {
						if (show_address == true) {
							tempData = data;
							let address = data.data[0].relationships.address;
							this.form.relationships.address = address;
							// this.form.relationships.address.province = address.province;
							// this.form.relationships.address.city = address.city;
							// this.form.relationships.address.district = address.district;
							// $("#province").html(address.province);
							// $("#city").html(address.city);
							// $("#district").html(address.district);
						} else {
							self.orders = [
								{
									id: 0,
									title: "Detail Produk",
									data: data.data[0].relationships.transaction_details,
								},
								{
									id: 1,
									title: "Total Produk",
									data: data.data[0],
								},
								{
									id: 2,
									title: "Detail Penerima",
									data: data.data[0],
								},
								{
									id: 3,
									title: "Detail Pembayaran & Pengiriman",
									data: data.data[0],
								},
							];

							this.form = data.data[0];
						}
					})
					.catch((error) => {
						this.showErrorMessage(error);
					});
			} else {
				Swal.fire("Error !", "Silakan login terlebih dahulu.", "error");
				window.location.href = this.baseURL + "/login";
			}

			// check address, jika kosong, berikan input untuk update address
			if (
				show_address &&
				!this.form.relationships.address.province_id &&
				!this.form.relationships.address.city_id &&
				!this.form.relationships.address.district_id
			) {
				console.log("2");
				// console.log('aaa')
				// console.log('2.data = ' + JSON.stringify(tempData))
				await axios
					.get("/api/list-province")
					.then(({ data }) => {
						if (data.success) {
							self.data_province = data.data.data.results;

							console.log(
								"address.province_id = " +
									self.form.relationships.address.province_id
							);
							self.province = _.find(self.data_province, function (obj) {
								console.log("obj.id = " + obj.id);
								return obj.id == self.form.relationships.address.province_id;
							});

							if (!self.province) {
								self.province = { id: null, name: "" };
							}

							console.log("self.province = " + self.province);

							// console.log('data_province = ' + 	self.data_province)

							let orders3 = tempData.data[0];
							orders3 = {
								...orders3,
								data_province: self.data_province,
								province: self.province,
							};

							self.ordersAddress = [
								self.orders[0],
								self.orders[1],
								{
									id: 2,
									title: "Detail Penerima",
									data: orders3,
								},
								self.orders[3],
							];

							console.log("orders3 = " + JSON.stringify(orders3));
							// self.orders[3] = { ...orders3, data_province: 'data_province', province: 'province'}

							// console.log('3 = ' + JSON.stringify(self.orders[3]))

							// let orders2 = self.orders[2].data
							// self.orders[2] = { ...orders2, secondName: 'Fogerty'}

							// let orders3 = self.orders[3].data
							// self.orders[3] = { ...orders3, secondName: 'Fogerty'}

							// let orders4 = self.orders[4].data
							// self.orders[3] = { ...orders3, secondName: 'Fogerty'}

							self.getDataCity();
							self.getDataDistrict();
						} else {
							// Swal.fire("Failed !", data.message, "error");
						}
					})
					.catch((error) => {
						// this.showErrorMessage(error);
					});
			}
			// this.form.relationships.address.province
			// this.form.relationships.address.city
			// this.form.relationships.address.district
			// console.log( 'relationship = ' + JSON.stringify(this.form.relationships.address))
		},
		async getDataCity() {
			const self = this;

			if (self.form.relationships.address.province_id) {
				await axios
					.get("/api/list-city/" + self.form.relationships.address.province_id)
					.then(({ data }) => {
						if (data.success) {
							self.data_city = data.data.data.results;
							self.city = _.find(self.data_city, function (obj) {
								return obj.id == self.form.relationships.address.city_id;
							});
						} else {
							Swal.fire("Failed !", data.message, "error");
						}
					})
					.catch((error) => {
						this.showErrorMessage(error);
					});
			}
		},
		async getDataDistrict() {
			const self = this;

			if (self.form.relationships.address.city_id) {
				await axios
					.get("/api/list-district/" + self.form.relationships.address.city_id)
					.then(({ data }) => {
						if (data.success) {
							self.data_district = data.data.data.results;
							self.district = _.find(self.data_district, function (obj) {
								return obj.id == self.form.relationships.address.district_id;
							});
						} else {
							Swal.fire("Failed !", data.message, "error");
						}
					})
					.catch((error) => {
						this.showErrorMessage(error);
					});
			}
		},
		async onSelectProvince(option) {
			const self = this;
			self.form.relationships.address.province_id = option.id;

			self.city = { id: "", name: "" };
			self.data_city = [];
			self.form.relationships.address.city_id = "";

			self.district = { id: "", name: "" };
			self.data_district = [];
			self.form.relationships.address.district_id = "";

			await axios
				.get("/api/list-city/" + option.id)
				.then(({ data }) => {
					if (data.success) {
						self.data_city = data.data.data.results;
					} else {
						Swal.fire("Failed !", data.message, "error");
					}
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});
		},
		async onSelectCity(option) {
			const self = this;
			self.form.relationships.address.city_id = option.id;

			// clear district
			self.district = { id: "", name: "" };
			self.data_district = [];
			self.form.relationships.address.district_id = "";

			// select district
			axios
				.get("/api/list-district/" + option.id)
				.then(({ data }) => {
					if (data.success) {
						self.data_district = data.data.data.results;
					} else {
						Swal.fire("Failed !", data.message, "error");
					}
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});
		},
		async onSelectDistrict(option) {
			const self = this;
			self.form.relationships.address.district_id = option.id;
		},
	},
};
</script>
