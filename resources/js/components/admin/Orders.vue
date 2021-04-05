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
						<div class="h5 text-gray-800 line-height-222">
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
								<tr>
									<th>Bea Kirim</th>
									<td v-if="isEdit">
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
									<td v-else>{{ formatCurrency(form.shipping_cost) }}</td>
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
									<th>Alamat Tujuan</th>
									<td>{{ form.relationships.address.name }}</td>
								</tr>

								<tr>
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
							>Update Alamat Penerima</a
						>
					</li>
					<li class="nav-item">
						<a
							class="nav-link"
							data-toggle="pill"
							href="#order-confirm-pay"
							role="tab"
							aria-selected="true"
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
							>Pesanan Selesai</a
						>
					</li>
				</ul>
			</div>

			<div class="card-body">
				<div class="tab-content">
					<div
						class="tab-pane fade active show"
						id="order-confirm-address"
						role="tabpanel"
					>
						<div class="card shadow">
							<div class="card-header">
								<div class="row">
									<div class="col-md-8 align-self-center">
										<h2 class="lead text-dark mb-0">Orders</h2>
									</div>
									<div class="col-md-4 float-right text-right"></div>
								</div>
							</div>
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

					<div class="tab-pane fade" id="order-confirm-pay" role="tabpanel">
						Update Bea Pengiriman
					</div>

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

export default {
	components: {
		GoBack,
		"b-pagination": BPagination,
	},
	data() {
		return {
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
					address: {},
					delivery_status: {},
					transaction_details: [],
				},
			}),
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
		updateOrder(id) {
			const self = this;
			axios
				.put(self.endpoint + "/" + id, {
					id_delivery_status: self.form.id_delivery_status,
					shipping_cost: self.form.shipping_cost,
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
			}
		},
		async fetchData(page = 1, search = "") {
			const self = this;
			await axios
				.get(self.endpoint + "?page=" + page + "&q=" + search)
				.then(({ data }) => {
					this.currentPage = data.current_page;
					this.perPage = data.per_page;
					this.totalItems = data.total;
					this.results = data;
				});
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
