<template>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<span>
							<h5 style="font-size: 14px;">
								Invoice :
								<span style="font-weight: bold; color: green; font-size: 14px;"
									>{{ form.invoice }}

									<button
										class="btn btn-info float-right text-right"
										style="font-size: 10px;"
										@click="toggleAccord"
										v-if="expanded"
									>
										Collapse
									</button>
									<button
										class="btn btn-info float-right text-right"
										style="font-size: 12px;"
										@click="toggleAccord"
										v-if="!expanded"
									>
										Expand
									</button>
								</span>
							</h5>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="accordion">
			<accordioncard
				v-for="kit in kits"
				:card-data="kit"
				:key="kit.code"
				:active="expanded"
				:address="form.relationships.address"
				:admin="
					form.relationships.transaction_details[0].relationships.product
						.relationships.admin
				"
				:payment_image="form.payment_image"
				:order_id="form.id"
				:invoice="form.invoice"
			></accordioncard>
		</div>
	</div>
</template>

<script>
import Accordioncard from "./Accordioncard.vue";
import { required, minLength, maxLength } from "vuelidate/lib/validators";

export default {
	components: {
		accordioncard: Accordioncard,
	},
	data() {
		return {
			expanded: false,
			show: true,
			kits: [],
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
						city_id: "",
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
		async fetchData() {
			const self = this;

			if (localStorage.invoice) {
				await axios
					.get(self.endpoint, {
						params: {
							invoice: localStorage.invoice,
						},
					})
					.then(({ data }) => {
						let result = data.data[0];
						self.form = result;

						self.kits[0] = {
							code: "A",
							name: "Detail Pemesanan",
							status: "complete",
							itemCount: 8,
							items: {
								weight_product: result.total_weight,
								price_product: result.total_price,
							},
						};

						self.kits[1] = {
							code: "B",
							name: "Detail Product",
							status: "complete",
							itemCount: 8,
							items: result.relationships.transaction_details,
						};

						let address = result.relationships.address;
						self.kits[2] = {
							code: "C",
							name: "Detail Penerima",
							status:
								result.shipping_cost == 0 ||
								!address.province ||
								!address.city ||
								!address.district
									? "not complete"
									: "complete",
							itemCount: 1,
							items: {
								invoice: result.invoice,
								phone_number: result.phone_number,
								shipping_cost: result.shipping_cost,
								address: result.relationships.address,
							},
						};

						self.kits[3] = {
							code: "D",
							name: "Detail Pembayaran & Pengiriman",
							status:
								result.shipping_cost == 0 ||
								!result.payment_image ||
								!result.delivery_number ||
								result.relationships.delivery_status.id == 1 || 
								result.relationships.delivery_status.id == 2
									? "not complete"
									: "complete",
							itemCount: 0,
							items: {
								ekspedisi_name: result.ekspedisi_name,
								shipping_cost: result.shipping_cost,
								price_product: result.total_price,
								price_total:
									parseInt(result.total_price) + parseInt(result.shipping_cost),
								payment_image: result.payment_image,
								delivery_status: result.relationships.delivery_status,
								delivery_number: result.delivery_number,
							},
						};
					})
					.catch((error) => {
						this.showErrorMessage(error);
					});
			} else {
				Swal.fire("Error !", "Silakan login terlebih dahulu.", "error");
				window.location.href = this.baseURL + "/login";
			}

			// check address, jika kosong, berikan input untuk update address
		},
		async getDataCity() {
			const self = this;

			if (self.form.relationships.address.province_id) {
				await axios
					.get("/api/list-city/" + self.form.relationships.address.province_id)
					.then(({ data }) => {
						if (data.success) {
							self.data_city = data.data;
							self.city = _.find(self.data_city, function (obj) {
								return obj.id == self.form.relationships.address.city_id;
							});

							if (!self.city) {
								self.city = { id: "", name: "" };
							}
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
							self.data_district = data.data;
							self.district = _.find(self.data_district, function (obj) {
								return obj.id == self.form.relationships.address.district_id;
							});

							if (!self.district) {
								self.district = { id: "", name: "" };
							}
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
						self.data_city = data.data;
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
						self.data_district = data.data;
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
