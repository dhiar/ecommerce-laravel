<template>
	<div class="container">
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
						<vsa-list>
							<!-- Here you can use v-for to loop through items  -->
							<vsa-item v-for="(item, idx) in orders" :key="idx">
								<vsa-heading>
									{{ item.title }}
								</vsa-heading>
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
											<td>{{ item.data.relationships_address }}</td>
										</tr>
										<tr>
											<th style="width: 30%;">
												Nama Desa RT.RW. / Jalan / Nomor
											</th>
											<td>{{ item.data.relationships.address.name }}</td>
										</tr>
										<tr>
											<th style="width: 30%;">Propinsi</th>
											<td>
												{{
													item.data.relationships.address.province
														? item.data.relationships.address.province
														: "-"
												}}
											</td>
										</tr>
										<tr>
											<th style="width: 30%;">Kabupaten</th>
											<td>
												{{
													item.data.relationships.address.city
														? item.data.relationships.address.city
														: "-"
												}}
											</td>
										</tr>
										<tr>
											<th style="width: 30%;">Kecamatan</th>
											<td>
												{{
													item.data.relationships.address.district
														? item.data.relationships.address.district
														: "-"
												}}
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
	},
	data() {
		return {
			orders: [],
			submitted: false,
			endpoint: "/api/transactions",
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
				relationships_address: null,
				relationships: {
					address: {},
					delivery_status: {},
					transaction_details: [],
				},
			}),
		};
	},
	mounted() {
		this.fetchData();
	},
	methods: {
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
						self.orders = [
							{
								id: 1,
								title: "Detail Produk",
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
					})
					.catch((error) => {
						this.showErrorMessage(error);
					});
			} else {
				Swal.fire("Error !", "Silakan login terlebih dahulu.", "error");
				window.location.href = this.baseURL + "/admin/login";
			}
		},
	},
};
</script>
