<template>
	<!-- Begin Page Content -->
	<div class="container-fluid mb-5">
		<div
			class="modal fade"
			id="modalOrders"
			tabindex="-1"
			aria-labelledby="addNewLabel"
			aria-hidden="true"
			style="width: 100%;"
		>
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<div
							v-if="number_of_tabs == '1'"
							class="h5 text-gray-800 line-height-222"
						>
							Update Alamat Penerima
						</div>
						<div
							v-else-if="number_of_tabs == '2'"
							class="h5 text-gray-800 line-height-222"
						>
							Update Bea Kirim
						</div>
						<div v-else class="h5 text-gray-800 line-height-222">
							Detail Orders
						</div>
						<button
							type="button"
							class="close"
							data-dismiss="modal"
							aria-label="Close"
						>
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<!-- modal-header -->
					<form @submit.prevent="updateOrder(form.id)">
						<div class="modal-body">
							<table class="table table-hover">
								<tr>
									<th style="width: 30%;">Invoice</th>
									<td>{{ form.invoice }}</td>
								</tr>
								<tr v-if="isEdit && number_of_tabs == '2'">
									<th>Bea Kirim</th>
									<!-- <td v-if="isEdit && number_of_tabs == '1'">
										{{ formatCurrency(form.shipping_cost) }}
									</td> -->
									<td>
										<input
											type="text"
											v-model="displayShippingCharge"
											id="displayShippingCharge"
											name="displayShippingCharge"
											class="form-control"
											@blur="isInputActive = false"
											@focus="isInputActive = true"
											:class="{
												'is-invalid': submitted && $v.form.shipping_cost.$error,
												'is-valid': !$v.form.shipping_cost.$invalid,
											}"
										/>
										<div class="valid-feedback">Bea kirim valid.</div>
										<div
											v-if="submitted && !$v.form.shipping_cost.required"
											class="invalid-feedback"
										>
											Bea kirim harus diisi
										</div>
										<div
											v-if="submitted && !$v.form.shipping_cost.numeric"
											class="invalid-feedback"
										>
											Bea kirim harus berupa angka
										</div>
									</td>
								</tr>
								<tr v-if="isEdit && number_of_tabs == '2'">
									<th>Nama Ekspedisi</th>
									<td>
										<div class="form-group">
											<input
												v-model="form.ekspedisi_name"
												type="text"
												id="ekspedisi_name"
												name="ekspedisi_name"
												placeholder="Nama Jasa Ekspedisi (JNE, JNT, Si Cepat, dll)"
												class="form-control"
											/>
										</div>
									</td>
								</tr>
								<tr>
									<th>Harga Produk</th>
									<td>
										{{ formatCurrency(form.total_price) }}
									</td>
								</tr>
								<tr>
									<th>Berat</th>
									<td>{{ formatWeight(form.total_weight) }}</td>
								</tr>
								<tr>
									<th>Alamat / Desa , RT/RW / No.</th>
									<td>{{ form.relationships.address.name }}</td>
								</tr>

								<tr
									v-if="
										number_of_tabs != '1' &&
										number_of_tabs != '2' &&
										number_of_tabs != '4'
									"
								>
									<th>Bukti Pembayaran</th>
									<td v-if="isEdit">
										<el-upload
											:action="baseURL + '/api/upload-payment'"
											style="
												border-style: dashed;
												border-width: 1px;
												border-color: gray;
												width: 100%;
											"
											class="img-fluid text-center"
											:show-file-list="false"
											:on-success="handlePaymentImageSuccess"
											:before-upload="beforePaymentImageUpload"
										>
											<img
												v-if="form.payment_image"
												:src="form.payment_image"
												class="img-fluid text-center"
												@error="imgErrorCondition"
												:class="{
													'is-invalid':
														submitted && $v.form.payment_image.$error,

													'is-valid': !$v.form.payment_image.$invalid,
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
											v-if="submitted && !$v.form.payment_image.required"
											class="invalid-feedback"
										>
											Bukti pembayaran harus diisi
										</div>
									</td>
								</tr>

								<tr
									v-if="
										number_of_tabs != '1' &&
										number_of_tabs != '2' &&
										number_of_tabs != '4'
									"
								>
									<th>Nomor Resi Pengiriman</th>
									<td v-if="isEdit">
										<div class="form-group">
											<input
												v-model="form.delivery_number"
												type="text"
												id="delivery_number"
												name="delivery_number"
												placeholder="Nomor Resi Pengiriman"
												class="form-control"
												:class="{
													'is-invalid':
														submitted && $v.form.delivery_number.$error,

													'is-valid': !$v.form.delivery_number.$invalid,
												}"
											/>
											<div class="valid-feedback">Nomor resi is valid.</div>
											<div
												v-if="submitted && !$v.form.delivery_number.required"
												class="invalid-feedback"
											>
												Nomor resi harus diisi
											</div>
										</div>
									</td>
								</tr>

								<tr v-if="number_of_tabs != '1' && number_of_tabs != '2'">
									<th>Status Kirim</th>
									<td v-if="isEdit">
										<el-select
											v-model="form.id_delivery_status"
											filterable
											remote
											reserve-keyword
											placeholder="Please select status"
											style="width: 100%;"
										>
											<el-option
												v-for="item in list_delivery_status"
												:key="item.id"
												:label="item.name"
												:value="item.id"
											>
											</el-option>
										</el-select>
									</td>
									<td v-else>{{ form.relationships.delivery_status.name }}</td>
								</tr>

								<tr
									v-if="
										isEdit && number_of_tabs != '3' && number_of_tabs != '4'
									"
								>
									<th>Propinsi</th>
									<td v-if="number_of_tabs == '1' || number_of_tabs == '2'">
										<multiselect
											v-model="province"
											:options="data_province"
											placeholder="Select one"
											label="name"
											track-by="id"
											:searchable="true"
											:max-height="150"
											:max="3"
											:disabled="number_of_tabs == '1' ? false : true"
											@select="onSelectProvince"
											:class="{
												'is-invalid': submitted && $v.form.province_id.$error,
												'is-valid': !$v.form.province_id.$invalid,
											}"
										></multiselect>
										<div class="valid-feedback">Propinsi is valid.</div>
										<div
											v-if="submitted && !$v.form.province_id.required"
											class="invalid-feedback"
										>
											Propinsi harus diisi
										</div>
									</td>
								</tr>

								<tr
									v-if="
										isEdit && number_of_tabs != '3' && number_of_tabs != '4'
									"
								>
									<th>Kabupaten</th>
									<td v-if="number_of_tabs == '1' || number_of_tabs == '2'">
										<multiselect
											v-model="city"
											:options="data_city"
											placeholder="Select one"
											label="name"
											track-by="id"
											:searchable="true"
											:max-height="150"
											:max="3"
											:disabled="number_of_tabs == '1' ? false : true"
											@select="onSelectCity"
											:class="{
												'is-invalid': submitted && $v.form.city_id.$error,
												'is-valid': !$v.form.city_id.$invalid,
											}"
										></multiselect>
										<div class="valid-feedback">Kabupaten is valid.</div>
										<div
											v-if="submitted && !$v.form.city_id.required"
											class="invalid-feedback"
										>
											Kabupaten harus diisi
										</div>
									</td>
								</tr>

								<tr
									v-if="
										isEdit && number_of_tabs != '3' && number_of_tabs != '4'
									"
								>
									<th>Kecamatan</th>
									<td v-if="number_of_tabs == '1' || number_of_tabs == '2'">
										<multiselect
											v-model="district"
											:options="data_district"
											placeholder="Select one"
											label="name"
											track-by="id"
											:searchable="true"
											:max-height="150"
											:max="3"
											:disabled="number_of_tabs == '1' ? false : true"
											@select="onSelectDistrict"
											:class="{
												'is-invalid': submitted && $v.form.district_id.$error,
												'is-valid': !$v.form.district_id.$invalid,
											}"
										></multiselect>
										<div class="valid-feedback">Kecamatan is valid.</div>
										<div
											v-if="submitted && !$v.form.district_id.required"
											class="invalid-feedback"
										>
											Kecamatan harus diisi
										</div>
									</td>
								</tr>

								<tr v-if="isEdit && number_of_tabs == '1'">
									<th>No.HP Penerima</th>
									<td>
										<vue-phone-number-input
											default-country-code="ID"
											@update="showPhonePayload"
											v-model="form.phone_number"
										></vue-phone-number-input>
									</td>
								</tr>
							</table>
							<table v-if="!isEdit" class="table table-hover">
								<tr>
									<th class="h5 text-gray-800 line-height-222">
										Detail
									</th>
								</tr>
							</table>
							<table v-if="!isEdit" class="table table-hover">
								<thead>
									<tr>
										<th class="text-center">No</th>
										<th>Produk</th>
										<th>Harga</th>
										<th>Berat</th>
										<th>Seller</th>
									</tr>
								</thead>
								<tbody>
									<tr
										v-for="(item, idx) in form.relationships
											.transaction_details"
										:key="item.id"
									>
										<td class="text-center">
											{{ idx + 1 }}
										</td>
										<td>
											{{ item.relationships.product.name }}
										</td>
										<td>
											{{ formatCurrency(item.relationships.product.price) }}
										</td>
										<td>
											{{ formatWeight(item.relationships.product.weight) }}
										</td>
										<td>
											{{ item.relationships.product.relationships.admin.name }}
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!--modal body-->

						<div class="modal-footer">
							<button
								v-if="isEdit"
								type="submit"
								:disabled="form.busy"
								class="btn btn-primary"
							>
								Update
							</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal">
								Close
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<go-back></go-back><br />
		<!-- Page Heading -->

		<div class="card card-primary card-outline card-outline-tabs">
			<div class="card-header p-0 border-bottom-0">
				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item">
						<a
							class="nav-link active"
							data-toggle="pill"
							href="#order-confirm-address"
							role="tab"
							aria-selected="true"
							@click="fetchData(null, null, '1', 'order-confirm-address')"
							>Update Alamat Penerima</a
						>
					</li>
					<li class="nav-item">
						<a
							class="nav-link"
							data-toggle="pill"
							href="#order-ongkir"
							role="tab"
							aria-selected="true"
							@click="fetchData(null, null, '2', 'order-ongkir')"
							>Update Ongkir</a
						>
					</li>
					<li class="nav-item">
						<a
							class="nav-link"
							data-toggle="pill"
							href="#order-confirm-pay"
							role="tab"
							aria-selected="true"
							@click="fetchData(null, null, '3', 'order-confirm-pay')"
							>Update Pembayaran</a
						>
					</li>
					<li class="nav-item">
						<a
							class="nav-link"
							data-toggle="pill"
							href="#order-confirm-shipping"
							role="tab"
							aria-selected="true"
							@click="fetchData(null, null, '4', 'order-confirm-shipping')"
							>Update Pengiriman</a
						>
					</li>
					<li class="nav-item">
						<a
							class="nav-link"
							data-toggle="pill"
							href="#order-complete"
							role="tab"
							aria-selected="true"
							@click="fetchData(null, null, '5', 'order-complete')"
							>Pesanan Selesai</a
						>
					</li>
				</ul>
			</div>

			<div class="card-body">
				<div class="tab-content">
					<div class="tab-pane fade active show" :id="elId" role="tabpanel">
						<div class="card shadow">
							<div class="card-body table-responsive">
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="text-center" style="width: 8% !important;">
												No
											</th>
											<th style="width: 20% !important;">Invoice</th>
											<th style="width: 18% !important;">
												Penjual
											</th>
											<th style="width: 13% !important;">
												Status Order
											</th>
											<th class="text-right" style="width: 13% !important;">
												Total Berat
											</th>
											<th class="text-right" style="width: 15% !important;">
												Total Harga
											</th>
											<th class="text-center">Aksi</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(item, idx) in results.data" :key="item.id">
											<td class="text-center">
												{{ getNumber(currentPage, idx) }}
											</td>
											<td>
												{{ item.invoice }}
											</td>
											<td>
												{{
													item.relationships.transaction_details[0]
														.relationships.product.relationships.admin.name
												}}
											</td>
											<td>
												{{ item.relationships.delivery_status.name }}
											</td>
											<td class="text-right">
												{{ formatWeight(item.total_weight) }}
											</td>
											<td class="text-right">
												{{ formatCurrency(item.total_price) }}
											</td>

											<td class="text-center">
												<a
													class="btn btn-sm btn-success"
													href="#"
													@click="showModalOrders(item.id, false)"
													><i class="fa fa-eye text-gray-100"></i
												></a>
												<a
													class="btn btn-sm btn-info"
													href="#"
													@click="showModalOrders(item.id, true)"
													><i class="fa fa-pen text-gray-100"></i
												></a>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="card-footer">
								<div class="overflow-auto">
									<b-pagination
										size="md"
										first-text="First"
										prev-text="Prev"
										next-text="Next"
										last-text="Last"
										:total-rows="totalItems"
										v-model="currentPage"
										:per-page="perPage"
										align="center"
									></b-pagination>
								</div>
							</div>
						</div>
					</div>

					<!-- <div class="tab-pane fade" id="order-ongkir" role="tabpanel">
						Update Bea Pengiriman
					</div> -->

					<div
						class="tab-pane fade"
						id="order-confirm-shipping"
						role="tabpanel"
					>
						Update Status Pengiriman
					</div>

					<div class="tab-pane fade" id="order-complete" role="tabpanel">
						Pesanan Selesai
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import GoBack from "./GoBack.vue";
import { BPagination } from "bootstrap-vue";
import {
	required,
	minLength,
	maxLength,
	numeric,
} from "vuelidate/lib/validators";

import VuePhoneNumberInput from "vue-phone-number-input";
import "vue-phone-number-input/dist/vue-phone-number-input.css";

export default {
	components: {
		GoBack,
		"b-pagination": BPagination,
		"vue-phone-number-input": VuePhoneNumberInput,
	},
	data() {
		return {
			elId: "order-confirm-address",
			number_of_tabs: "1",
			isInputActive: false,
			isEdit: false,
			currentPage: 1,
			perPage: 10,
			totalItems: 50,
			results: {},
			submitted: false,
			page: "Orders",
			endpoint: "/api/transactions",
			list_delivery_status: [
				{ id: 1, name: "Verifikasi" },
				{ id: 2, name: "Dikemas" },
				{ id: 3, name: "Dikirim" },
				{ id: 4, name: "Diterima" },
				{ id: 5, name: "Cancel" },
			],
			phonePayloads: {},
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
				province_id: "",
				city_id: "",
				district_id: "",
				phone_code: "",
				phone_number: "",
				phone_formatted: "",
				ekspedisi_name: "JNE",
				payment_image: "",
				storage_payment_image: "",
				delivery_number: "",
				relationships: {
					address: {},
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
		this.fetchData(1);
	},
	computed: {
		displayShippingCharge: {
			get: function () {
				if (this.isInputActive) {
					// Cursor is inside the input field. unformat display value for user
					return this.form.shipping_cost.toString();
				} else {
					return this.formatCurrency(this.form.shipping_cost);
				}
			},
			set: function (modifiedValue) {
				let newValue = parseFloat(modifiedValue.replace(/[^\d\.]/g, ""));
				if (isNaN(newValue)) {
					newValue = 0;
				}
				this.form.shipping_cost = newValue;
			},
		},
	},
	methods: {
		// :on-success="handlePaymentImageSuccess"
		// :before-upload="beforePaymentImageUpload"
		handlePaymentImageSuccess(res, file) {
			this.form.storage_payment_image = res.result;
			this.form.payment_image = URL.createObjectURL(file.raw);
		},
		beforePaymentImageUpload(file) {
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
		showPhonePayload(payload) {
			this.phonePayloads = payload;
		},
		updateOrder(id) {
			const self = this;

			// update address
			if (self.number_of_tabs == "1") {
				if (!this.phonePayloads.isValid) {
					return Swal.fire({
						icon: "warning",
						title: "Save Failed!",
						html: "Ensure that the phone are correct.",
						type: "warning",
						showConfirmButton: true,
					});
				}

				axios
					.put(self.endpoint + "/" + id, {
						phone_code: this.phonePayloads.countryCallingCode,
						phone_number: this.phonePayloads.phoneNumber,
						phone_formatted: this.phonePayloads.formattedNumber,
					})
					.then(({ data }) => {
						if (data.success) {
							Swal.fire("Success !", data.message, "success");
						} else {
							Swal.fire("Failed !", data.message, "error");
						}
						this.fetchData();
					})
					.catch((error) => {
						this.showErrorMessage(error);
					});

				axios
					.put("/api/address" + "/" + self.form.relationships.address.id, {
						province_id: self.province.id,
						city_id: self.city.id,
						district_id: self.district.id,
					})
					.then(({ data }) => {
						if (data.success) {
							Swal.fire("Success !", data.message, "success");
						} else {
							Swal.fire("Failed !", data.message, "error");
						}
						this.fetchData();
					})
					.catch((error) => {
						this.showErrorMessage(error);
					});
			} else if (self.number_of_tabs == "2") {
				axios
					.put(self.endpoint + "/" + id, {
						ekspedisi_name: self.form.ekspedisi_name,
						shipping_cost: self.form.shipping_cost,
					})
					.then(({ data }) => {
						if (data.success) {
							Swal.fire("Success !", data.message, "success");
						} else {
							Swal.fire("Failed !", data.message, "error");
						}
						self.fetchData(null, null, "2", "order-ongkir");
					})
					.catch((error) => {
						this.showErrorMessage(error);
					});
			} else if (self.number_of_tabs == "3") {
				this.submitted = true;

				this.$v.$touch();
				if (this.$v.$error) {
					Swal.fire(
						"Failed !",
						"Bukti pembayaran & no.resi harus diisi",
						"error"
					);
					return;
				} else {
					if (self.form.id_delivery_status == "1") {
						Swal.fire(
							"Failed !",
							"Status kirim harus diubah menjadi DIKEMAS",
							"error"
						);
					}

					if (self.form.id_delivery_status == "1") {
						Swal.fire(
							"Failed !",
							"Status kirim harus diubah menjadi DIKEMAS",
							"error"
						);
						return;
					}

					if (!self.form.storage_payment_image) {
						Swal.fire("Failed !", "Bukti pembayaran harus diisi.", "error");
						return;
					}

					axios
						.put(self.endpoint + "/" + id, {
							storage_payment_image: self.form.storage_payment_image,
							payment_image: self.form.storage_payment_image,
							delivery_number: self.form.delivery_number,
							id_delivery_status: self.form.id_delivery_status,
						})
						.then(({ data }) => {
							if (data.success) {
								Swal.fire("Success !", data.message, "success");
							} else {
								Swal.fire("Failed !", data.message, "error");
							}
							self.fetchData(null, null, "3", "order-confirm-pay");
						})
						.catch((error) => {
							this.showErrorMessage(error);
						});
				}
			} else {
				if (parseInt(self.form.id_delivery_status) <= 2) {
					Swal.fire(
						"Failed !",
						"Status kirim harus diubah menjadi DIKIRIM atau DITERIMA",
						"error"
					);
					return;
				}
				axios
					.put(self.endpoint + "/" + id, {
						id_delivery_status: self.form.id_delivery_status,
					})
					.then(({ data }) => {
						if (data.success) {
							Swal.fire("Success !", data.message, "success");
							window.location.href = self.baseURL + "/admin/orders";
						} else {
							Swal.fire("Failed !", data.message, "error");
						}
						this.fetchData();
					})
					.catch((error) => {
						this.showErrorMessage(error);
					});
			}
			$("#modalOrders").modal("hide");
		},
		async showModalOrders(id, isEdit) {
			this.isEdit = isEdit;
			this.submitted = false;
			$("#modalOrders").modal("show");
			const self = this;
			this.form.id = id;
			if (id) {
				const result = await axios
					.get(self.endpoint + "/" + id)
					.catch((error) => {
						this.showErrorMessage(error);
					});
				this.form = result.data;
				this.form.province_id = result.data.relationships.address.province_id;
				this.form.city_id = result.data.relationships.address.city_id;
				this.form.district_id = result.data.relationships.address.district_id;

				axios
					.get("/api/list-province")
					.then(({ data }) => {
						if (data.success) {
							self.data_province = data.data.data.results;
							self.province = _.find(self.data_province, function (obj) {
								return obj.id == self.form.relationships.address.province_id;
							});
							self.getDataCity();
							self.getDataDistrict();
						} else {
							Swal.fire("Failed !", data.message, "error");
						}
					})
					.catch((error) => {
						this.showErrorMessage(error);
					});
			}
		},
		async fetchData(
			page = 1,
			search = "",
			number_of_tabs = "1",
			elId = "order-confirm-address"
		) {
			const self = this;

			self.elId = elId;
			let pageNumber = page ? page : 1;
			let searchValue = search ? search : "";
			self.number_of_tabs = number_of_tabs;

			this.list_delivery_status = [
				{ id: 1, name: "Verifikasi" },
				{ id: 2, name: "Dikemas" },
				{ id: 3, name: "Dikirim" },
				{ id: 4, name: "Diterima" },
				{ id: 5, name: "Cancel" },
			];

			if (number_of_tabs == 3) {
				this.list_delivery_status = [
					{ id: 1, name: "Verifikasi" },
					{ id: 2, name: "Dikemas" },
				];
			}
			if (number_of_tabs == 4) {
				this.list_delivery_status = [
					{ id: 2, name: "Dikemas" },
					{ id: 3, name: "Dikirim" },
					{ id: 4, name: "Diterima" },
					{ id: 5, name: "Cancel" },
				];
			}

			await axios
				.get(self.endpoint + "?page=" + pageNumber + "&q=" + searchValue, {
					params: {
						number_of_tabs: number_of_tabs,
					},
				})
				.then(({ data }) => {
					this.currentPage = data.current_page;
					this.perPage = data.per_page;
					this.totalItems = data.total;
					this.results = data;
				});
		},
		async getDataCity() {
			const self = this;
			await axios
				.get("/api/list-city/" + self.form.province_id)
				.then(({ data }) => {
					if (data.success) {
						self.data_city = data.data.data.results;
						self.city = _.find(self.data_city, function (obj) {
							return obj.id == self.form.city_id;
						});
					} else {
						Swal.fire("Failed !", data.message, "error");
					}
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});
		},

		async getDataDistrict() {
			const self = this;
			await axios
				.get("/api/list-district/" + self.form.city_id)
				.then(({ data }) => {
					if (data.success) {
						self.data_district = data.data.data.results;
						self.district = _.find(self.data_district, function (obj) {
							return obj.id == self.form.district_id;
						});
					} else {
						Swal.fire("Failed !", data.message, "error");
					}
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});
		},
		async onSelectProvince(option) {
			const self = this;
			self.form.province_id = option.id;

			// clear city & district
			self.city = { id: "", name: "" };
			self.data_city = [];
			self.form.city_id = "";

			self.district = { id: "", name: "" };
			self.data_district = [];
			self.form.district_id = "";

			// select city
			axios
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
			self.form.city_id = option.id;

			// clear district
			self.district = { id: "", name: "" };
			self.data_district = [];
			self.form.district_id = "";

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
			self.form.district_id = option.id;
		},
	},
	created() {
		const self = this;
		Fire.$on("searching", () => {
			let query = this.$parent.search;
			axios
				.get(self.endpoint + "?q=" + query)
				.then(({ data }) => {
					self.currentPage = data.current_page;
					self.perPage = data.per_page;
					self.totalItems = data.total;
					self.results = data;
				})
				.catch(() => {
					this.$Progress.fail();
					Toast.fire({
						icon: "error",
						title: "Orders not found.",
					});
				});
		});
	},
	validations: {
		form: {
			shipping_cost: {
				required,
				numeric,
			},
			province_id: {
				required,
			},
			city_id: {
				required,
			},
			district_id: {
				required,
			},
			payment_image: {
				required,
			},
			delivery_number: {
				required,
			},
		},
	},
	watch: {
		currentPage: {
			handler: function (value) {
				this.fetchData(value, this.$parent.search);
			},
		},
	},
};
</script>
<style scoped>
.nav-tabs .nav-link.active,
.nav-tabs .nav-item.show .nav-link {
	color: #003cff;
	font-weight: 700 !important;
}
</style>
