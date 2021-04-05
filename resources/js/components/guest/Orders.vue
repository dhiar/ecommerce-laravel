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
						<table class="table table-hover">
								<tr>
									<th style="width: 30%;">Total Berat</th>
									<td>{{ formatWeight(form.total_weight) }}</td>
								</tr>
								<tr>
									<th style="width: 30%;">Total Bea Produk</th>
									<td>{{ formatCurrency(form.total_price) }}</td>
								</tr>
								<tr>
									<th style="width: 30%;">Bea Kirim</th>
									<td>{{ formatCurrency(form.shipping_cost) }}</td>
								</tr>
								<tr>
									<th style="width: 30%;">Status Kirim</th>
									<td>{{ form.relationships.delivery_status.name }}</td>
								</tr>
								<tr>
									<th style="width: 30%;">Nama Penerima</th>
									<td>{{ formatName(form.invoice) }}</td>
								</tr>
								<br>
								<tr class="card-header">
									<th style="width: 30%;">Alamat Penerima</th>
									<td></td>
								</tr>
								<tr>
									<th style="width: 30%;">Nama Desa RT.RW. / Jalan / Nomor</th>
									<td>{{ form.relationships.address.name }}</td>
								</tr>
								<tr>
									<th style="width: 30%;">Propinsi</th>
									<td>{{ form.relationships.address.province_id ? form.relationships.address.province_id : '-' }}</td>
								</tr>
								<tr>
									<th style="width: 30%;">Kabupaten</th>
									<td>{{ form.relationships.address.city_id ? form.relationships.address.city_id : '-' }}</td>
								</tr>
								<tr>
									<th style="width: 30%;">Kecamatan</th>
									<td>{{ form.relationships.address.district_id ? form.relationships.address.city_id : '-' }}</td>
								</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { required, minLength, maxLength } from "vuelidate/lib/validators";
export default {
	data() {
		return {
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
				total_weight: 9600,
				total_price: 1560000,
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
		this.fetchData();
		alert(localStorage.invoice);
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
						console.log("data = " + JSON.stringify(data));
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
