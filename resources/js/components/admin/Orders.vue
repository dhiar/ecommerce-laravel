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
			<div class="modal-dialog modal-dialog-centered">
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
					<div class="modal-body">
						Isi dengan detail order with pagination
					</div>
					<!--modal body-->

					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">
							Close
						</button>
					</div>
				</div>
			</div>
		</div>

		<go-back></go-back><br />
		<!-- Page Heading -->
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
							<th class="text-center" style="width: 8% !important;">No</th>
							<th style="width: 20% !important;">Invoice</th>
							<th style="width: 18% !important;">
								Penjual
							</th>
							<th style="width: 18% !important;">
								Status Order
							</th>
							<th class="text-right" style="width: 13% !important;">
								Total Berat
							</th>
							<th class="text-right" style="width: 17% !important;">
								Total Harga
							</th>
							<th class="text-center">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(item, idx) in results.data" :key="item.id">
							<td class="text-center">{{ getNumber(currentPage, idx) }}</td>
							<td>
								{{ item.invoice }}
							</td>
							<td>
								{{ item.relationships.transaction_details[0].relationships.product.relationships.admin.name }}
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
									class="btn btn-sm btn-info"
									href="#"
									@click="showModalOrders(item.id)"
									><i class="fa fa-eye text-gray-100"></i
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
</template>

<script>
import GoBack from "./GoBack.vue";
import { BPagination } from "bootstrap-vue";

export default {
	components: {
		GoBack,
		"b-pagination": BPagination,
	},
	data() {
		return {
			currentPage: 1,
			perPage: 10,
			totalItems: 50,
			results: {},
			submitted: false,
			page: "Orders",
			endpoint: "/api/transactions",
			form: new Form({
				id: "",
				invoice: "",
			}),
		};
	},
	mounted() {
		this.fetchData(1);
	},
	methods: {
		async showModalOrders(id) {
			this.submitted = false;
			$("#modalOrders").modal("show");
			// const self = this;
			// this.form.id = id;
			// if (id) {
			// 	const result = await axios
			// 		.get(self.endpoint + "/" + id)
			// 		.catch((error) => {
			// 			this.showErrorMessage(error);
			// 		});

			// 	this.form = result.data;
			// } else {
			// 	// clear form
			// 	if (!this.submitted) {
			// 		self.clearForm(self.form);
			// 	}
			// }
		},
		async fetchData(page = 1) {
			const self = this;
			alert(self.endpoint);
			await axios.get(self.endpoint + "?page=" + page).then(({ data }) => {
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
			alert("query = " + query);
			// axios
			// 	.get(self.endpoint + "?q=" + query)
			// 	.then(({ data }) => {
			// 		self.currentPage = data.current_page;
			// 		self.perPage = data.per_page;
			// 		self.totalItems = data.total;
			// 		self.results = data;
			// 	})
			// 	.catch(() => {
			// 		this.$Progress.fail();
			// 		Toast.fire({
			// 			icon: "error",
			// 			title: "Orders not found.",
			// 		});
			// 	});
		});
	},
	watch: {
		currentPage: {
			handler: function (value) {
				this.fetchData(value);
			},
		},
	},
};
</script>
