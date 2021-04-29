<template>
	<div class="card">
		<div class="card-header p-0">
			<a
				class="kit-toggle"
				href="#"
				@click.prevent="mutableActive = !mutableActive"
			>
				<div>
					<span class="kit-code">{{ cardData.code }}</span>
					{{ cardData.name }}
					<span :class="kitStatus">{{ cardData.status }}</span>
				</div>

				<div>
					<!-- <span :class="kitItem"> -->
					<span>
						<i
							v-if="mutableActive"
							class="fa fa-chevron-up"
							aria-hidden="true"
						></i>
						<i v-else class="fa fa-chevron-down" aria-hidden="true"></i>
					</span>
				</div>
			</a>
		</div>
		<transition name="collapse">
			<div v-show="mutableActive">
				<div
					v-if="cardData.code == 'A'"
					class="kit-contents card-body"
					style="height: 100% !important;"
				>
					<table class="table table-hover">
						<tr>
							<th style="width: 30% !important;">Berat Produk</th>
							<td>:</td>
							<td>{{ formatWeight(cardData.items.weight_product) }}</td>
						</tr>
						<tr>
							<th>Harga Produk</th>
							<td>:</td>
							<td>{{ formatCurrency(cardData.items.price_product) }}</td>
						</tr>
					</table>
				</div>
				<div v-else-if="cardData.code == 'B'" class="kit-contents card-body">
					<table style="width: 100% !important;">
						<tr
							v-for="item in cardData.items"
							:key="item.id"
							style="width: 100% !important;"
						>
							<td>{{ item.relationships.product.name }}</td>
							<td
								style="text-align: center !important; width: 50% !important;"
								class="text-center"
							>
								<img
									v-if="item.relationships.product.image"
									:src="item.relationships.product.image"
									class="img-fluid text-center"
									@error="imgErrorCondition"
									style="width: 250px; height: 250px;"
								/>
							</td>
						</tr>
					</table>
				</div>
				<div v-else-if="cardData.code == 'C'" class="kit-contents card-body">
					<table class="table table-hover">
						<tr>
							<th style="width: 30% !important;">Penerima</th>
							<td>:</td>
							<td>{{ formatName(cardData.items.invoice) }}</td>
						</tr>
						<tr>
							<th>Phone Penerima</th>
							<td>:</td>
							<td>{{ cardData.items.phone_number }}</td>
						</tr>
						<tr>
							<th>Alamat</th>
							<td>:</td>
							<td>
								<span>{{
									cardData.items.address.name
										? cardData.items.address.name + " , "
										: ""
								}}</span>
								<span>{{
									cardData.items.address.district
										? "Kec." + cardData.items.address.district + " , "
										: ""
								}}</span>
								<span>{{
									cardData.items.address.city
										? "Kab/Kota." + cardData.items.address.city + " , "
										: ""
								}}</span>
								<span>{{
									cardData.items.address.province
										? "Propinsi." + cardData.items.address.province + " , "
										: ""
								}}</span>
							</td>
							<!-- { "id": "Rd", "name": "Soloraya", "province_id": 12, "city_id": 1202, "district_id": 1202011, "province": "SUMATERA UTARA", "city": "KABUPATEN MANDAILING NATAL", "district": "SINUNUKAN" } -->
						</tr>
						<tr
							v-if="
								!cardData.items.address.district &&
								!cardData.items.address.city &&
								!cardData.items.address.province
							"
						>
							<th>Propinsi</th>
							<td>:</td>
							<td>
								<multiselect
									v-model="province"
									:options="data_province"
									placeholder="Select one"
									label="name"
									track-by="id"
									:searchable="true"
									:max-height="150"
									:max="3"
									@select="onSelectProvince"
									:class="{
										'is-invalid':
											submitted && $v.formAddress.province_id.$error,
										'is-valid': !$v.formAddress.province_id.$invalid,
									}"
								></multiselect>
								<div class="valid-feedback">Propinsi is valid.</div>
								<div
									v-if="submitted && !$v.formAddress.province_id.required"
									class="invalid-feedback"
								>
									Propinsi harus diisi
								</div>
							</td>
						</tr>
						<tr
							v-if="
								!cardData.items.address.district &&
								!cardData.items.address.city &&
								!cardData.items.address.province
							"
						>
							<th>Kab./Kota</th>
							<td>:</td>
							<td>
								<multiselect
									v-model="city"
									:options="data_city"
									placeholder="Select one"
									label="name"
									track-by="id"
									:searchable="true"
									:max-height="150"
									:max="3"
									@select="onSelectCity"
									:class="{
										'is-invalid': submitted && $v.formAddress.city_id.$error,
										'is-valid': !$v.formAddress.city_id.$invalid,
									}"
								></multiselect>
								<div class="valid-feedback">Kabupaten is valid.</div>
								<div
									v-if="submitted && !$v.formAddress.city_id.required"
									class="invalid-feedback"
								>
									Kabupaten harus diisi
								</div>
							</td>
						</tr>
						<tr
							v-if="
								!cardData.items.address.district &&
								!cardData.items.address.city &&
								!cardData.items.address.province
							"
						>
							<th>Kecamatan</th>
							<td>:</td>
							<td>
								<multiselect
									v-model="district"
									:options="data_district"
									placeholder="Select one"
									label="name"
									track-by="id"
									:searchable="true"
									:max-height="150"
									:max="3"
									@select="onSelectDistrict"
									:class="{
										'is-invalid':
											submitted && $v.formAddress.district_id.$error,
										'is-valid': !$v.formAddress.district_id.$invalid,
									}"
								></multiselect>
								<div class="valid-feedback">Kecamatan is valid.</div>
								<div
									v-if="submitted && !$v.formAddress.district_id.required"
									class="invalid-feedback"
								>
									Kecamatan harus diisi
								</div>
							</td>
						</tr>
						<tr
							v-if="
								!cardData.items.address.district &&
								!cardData.items.address.city &&
								!cardData.items.address.province
							"
						>
							<td></td>
							<td></td>
							<td>
								<button class="btn btn-primary" @click="updateAddress">
									Update Address
								</button>
							</td>
						</tr>
						<tr>
							<th>Ongkir</th>
							<td>:</td>
							<td>{{ formatCurrency(cardData.items.shipping_cost) }}</td>
						</tr>
						<tr
							v-if="
								cardData.items.address.district &&
								cardData.items.address.city &&
								cardData.items.address.province &&
								parseInt(cardData.items.shipping_cost) <= 0
							"
						>
							<td></td>
							<td></td>
							<td>
								<button
									class="btn btn-primary"
									@click="checkOngkir(cardData.items.invoice)"
								>
									Check Ongkir
								</button>
							</td>
						</tr>
					</table>
				</div>
				<div
					v-else
					class="kit-contents card-body"
					style="height: 100% !important;"
				>
					<table class="table table-hover">
						<tr>
							<th style="width: 30% !important;">Ekspedisi</th>
							<td>:</td>
							<td>{{ cardData.items.ekspedisi_name }}</td>
						</tr>
						<tr>
							<th>Harga Produk</th>
							<td>:</td>
							<td>{{ formatCurrency(cardData.items.price_product) }}</td>
						</tr>
						<tr>
							<th>Ongkir</th>
							<td>:</td>
							<td>{{ formatCurrency(cardData.items.shipping_cost) }}</td>
						</tr>
						<tr>
							<th>Total Bayar</th>
							<td>:</td>
							<td>
								{{
									formatCurrency(
										parseInt(cardData.items.price_product) +
											parseInt(cardData.items.shipping_cost)
									)
								}}
							</td>
						</tr>
						<tr v-if="cardData.items.payment_image">
							<th>Bukti Bayar</th>
							<td>:</td>
							<td>
								<img
									:src="cardData.items.payment_image"
									class="img-fluid text-center"
									@error="imgErrorCondition"
									style="width: 250px; height: 250px;"
								/>
							</td>
						</tr>
						<tr v-if="!cardData.items.payment_image">
							<th colspan="3">
								<div class="row">
									<div class="col-12 col-md-5 col-sm-5 themed-grid-col">
										Unggah Bukti Transfer :
									</div>
									<div
										class="col-12 col-md-7 col-sm-7 themed-grid-col"
										style=""
									>
										<span>
											<el-upload
												:action="baseURL + '/api/upload-category'"
												style="
													border-style: dashed;
													border-width: 1px;
													border-color: gray;
													width: 100%;
												"
												class="img-fluid text-center"
												:show-file-list="false"
												:on-success="handleImageSuccess"
												:before-upload="beforeImageUpload"
											>
												<img
													v-if="formPaymentImage.payment_image"
													:src="formPaymentImage.payment_image"
													class="img-fluid text-center"
													@error="imgErrorCondition"
													:class="{
														'is-invalid':
															submitted &&
															$v.formPaymentImage.payment_image.$error,

														'is-valid': !$v.formPaymentImage.payment_image
															.$invalid,
													}"
												/>
												<i v-else class="el-icon-plus avatar-uploader-icon"></i>
											</el-upload>
											<small class="text-muted"
												>Pastikan gambar berukuran maksimal 2mb, berformat png,
												jpg, jpeg. Dan berukuran 1600x400px</small
											>
											<div class="valid-feedback">Icon is valid.</div>
											<div
												v-if="
													submitted &&
													!$v.formPaymentImage.payment_image.required
												"
												class="invalid-feedback"
											>
												Icon harus diisi
											</div>
										</span>
										<span>
											<button
												class="btn btn-primary"
												@click="updateBuktiTransfer"
											>
												Update Bukti Transfer
											</button>
										</span>
									</div>
								</div>
							</th>
						</tr>
						<tr>
							<th>Ekspedisi</th>
							<td>:</td>
							<td>{{ cardData.items.ekspedisi_name }}</td>
						</tr>
						<tr>
							<th>Status Kirim</th>
							<td>:</td>
							<td>{{ cardData.items.delivery_status.name }}</td>
						</tr>
						<tr>
							<th>No.Pengiriman</th>
							<td>:</td>
							<td>{{ cardData.items.delivery_number }}</td>
						</tr>
					</table>
				</div>
			</div>
		</transition>
	</div>
</template>

<script>
import { required, numeric } from "vuelidate/lib/validators";

export default {
	name: "accordioncard",
	template: "#accordioncards",
	props: {
		order_id: {
			type: [Number, String],
		},
		active: {
			type: Boolean,
		},
		cardData: {
			type: [Array, Object],
		},
		address: {
			type: [Object],
		},
		admin: {
			type: [Object],
		},
		payment_image: {
			type: [String],
		},
	},
	data: function () {
		return {
			submitted: false,
			mutableActive: JSON.parse(this.active),
			endpoint: "/api/transactions",
			formAddress: new Form({
				id: "",
				name: "",
				province_id: "",
				city_id: "",
				district_id: "",
				province: "",
				city: "",
				district: "",
			}),
			formPaymentImage: new Form({
				id: "",
				payment_image: "",
				storage_payment_image: "",
			}),
			province: { id: null, name: "" },
			data_province: [],
			city: { id: null, name: "" },
			data_city: [],
			district: { id: null, name: "" },
			data_district: [],
		};
	},
	validations: {
		formAddress: {
			province_id: {
				required,
			},
			city_id: {
				required,
			},
			district_id: {
				required,
			},
		},
		formPaymentImage: {
			payment_image: {
				required,
			},
		},
	},
	computed: {
		// kitItem: function () {
		// 	return [
		// 		"item-count",
		// 		"badge",
		// 		"badge-pill",
		// 		this.cardData.itemCount > 0 ? "badge-secondary" : "badge-danger",
		// 	];
		// },
		kitStatus: function () {
			return [
				"badge",
				"ml-2",
				this.cardData.status === "complete" ? "badge-success" : "badge-warning",
			];
		},
	},
	mounted() {
		this.fetchData();
	},
	methods: {
		updateBuktiTransfer() {
			const self = this;
			axios
				.put(self.endpoint + "/" + self.order_id, self.formPaymentImage)
				.then(({ data }) => {
					if (data.success) {
						Swal.fire("Success !", data.message, "success");
						window.location.href = self.baseURL + "/orders";
					} else {
						Swal.fire("Failed !", data.message, "error");
					}
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});
		},
		handleImageSuccess(res, file) {
			this.formPaymentImage.storage_payment_image = res.result;
			this.formPaymentImage.payment_image = URL.createObjectURL(file.raw);
		},
		beforeImageUpload(file) {
			const isJPG = file.type === "image/jpeg";
			const isPNG = file.type === "image/png";

			if (!isJPG && !isPNG) {
				Swal.fire(
					"Oops...!",
					"Icon picture must be JPG / PNG format!",
					"error"
				);
			}

			return isJPG || isPNG;
		},
		checkOngkir(invoice = "") {
			let admin = this.admin;
			let linkWA =
				"https://api.whatsapp.com/send?phone=" +
				admin.whatsapp +
				"&text=Selamat%20pagi%20bpk%2Fibu%20*" +
				admin.name +
				"*" +
				encodeURIComponent(
					", berapa ongkir & total harga dengan kode pemesanan : *" +
						invoice +
						"* ?"
				);
			window.open(linkWA, "_blank");
		},
		updateAddress() {
			const self = this;
			this.submitted = true;
			this.$v.$touch();
			if (this.$v.$error) {
				Swal.fire("Failed !", "Propinsi s/d Kecamatan harus diisi", "error");
				return;
			}

			axios
				.put("/api/address/" + self.formAddress.id, self.formAddress)
				.then(({ data }) => {
					if (data.success) {
						Swal.fire("Success !", data.message, "success");
						window.location.href = self.baseURL + "/orders";
					} else {
						Swal.fire("Failed !", data.message, "error");
					}
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});
		},
		// async fetchData() {
		async fetchData() {
			const self = this;
			self.formAddress = self.address;
			payment_image;
			self.formPaymentImage.payment_image = self.payment_image;
			if (
				!self.address.province_id &&
				!self.address.city_id &&
				!self.address.district_id
			) {
				await axios
					.get("/api/list-province")
					.then(({ data }) => {
						if (data.success) {
							self.data_province = data.data;
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
			self.formAddress.province_id = option.id;

			// clear city & district
			self.city = { id: "", name: "" };
			self.data_city = [];
			self.formAddress.city_id = "";

			self.district = { id: "", name: "" };
			self.data_district = [];
			self.formAddress.district_id = "";

			// select city
			axios
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
			self.formAddress.city_id = option.id;

			// clear district
			self.district = { id: "", name: "" };
			self.data_district = [];
			self.formAddress.district_id = "";

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
			self.formAddress.district_id = option.id;
		},
	},
	watch: {
		active: {
			handler: function (value) {
				this.mutableActive = value;
			},
		},
	},
};
</script>

<style lang="scss" scoped>
.collapse-enter-active,
.collapse-leave-active {
	// transition: max-height 200ms;
	// max-height: 26rem;
	overflow: hidden;
}
.collapse-enter,
.collapse-leave-to {
	max-height: 0px;
	overflow: hidden;
}

.kit-toggle {
	color: #000;
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 0.5rem 1rem;
	&:hover {
		background-color: #ddd;
		text-decoration: none;
		color: #000;
	}

	.kit-code {
		width: 2.5rem;
		// height: 2.5rem;
		border: 2px solid #555;
		display: inline-block;
		color: #555;
		border-radius: 1.25rem;
		text-align: center;
		line-height: 2.25rem;
		margin-right: 0.5rem;
	}
	.item-count {
		font-size: 0.9rem;
	}
}
// .kit-contents {
// 	// min-height: 26rem;
// }
.items-list {
	// max-height: 14.8rem;
	// min-height: 5rem;
	overflow: auto;
	& li:nth-child(odd) {
		background: #eee;
	}
}
</style>
