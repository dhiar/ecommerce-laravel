<template>
	<div class="container">
		<go-back></go-back>
		<div class="row mt-5">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="row">
							<div class="col-md-10 align-self-center">
                <h2 class="lead text-dark mb-0">{{ form.name}}</h2>
							</div>
							<div class="col-md-2 float-right text-right">
								<button
									class="btn btn-info text-gray-100"
									type="button"
									@click="prevPage()"
								>
									<i class="fas fa-chevron-left"></i> Go Back
								</button>
							</div>
						</div>
					</div>

					<div class="card-body" style="padding: 20px;">
						<div class="row">
							<div class="col-12 col-md-6 col-sm-6 themed-grid-col text-center">
								<div class="card shadow text-center">
									<img
										v-if="form.image"
										:src="form.image"
										class="img-fluid text-center"
										@error="imgErrorCondition"
									/>
								</div>
							</div>
							<div class="col-12 col-md-6 col-sm-6 themed-grid-col">
								<div class="card shadow">
									<div class="card-header">
										<h5>{{ formatCurrency(form.price) }}</h5>
									</div>
									<table class="table table-hover">
										<tbody>
											<tr>
												<td>Category</td>
												<td>:</td>
												<td>{{ form.relationships.category.name }}</td>
											</tr>
											<tr>
												<td>Promo Price</td>
												<td>:</td>
												<td>{{ formatCurrency(form.promo_price) }}</td>
											</tr>
											<tr>
												<td>Weight</td>
												<td>:</td>
												<td>{{ form.weight }} gram</td>
											</tr>
											<tr>
												<td>Stock</td>
												<td>:</td>
												<td>{{ form.stock }}</td>
											</tr>
											<tr>
												<td>Condition</td>
												<td>:</td>
												<td>{{ form.condition }}</td>
											</tr>
											<tr>
												<td>Publish Type</td>
												<td>:</td>
												<td>{{ form.is_published | isPublished }}</td>
											</tr>
											<tr>
												<td>Created By</td>
												<td>:</td>
												<td>{{ form.relationships.admin.name | upText }}</td>
											</tr>
											<tr>
												<td>Count Transaction</td>
												<td>:</td>
												<td>{{ form.transaction }}</td>
											</tr>
											<tr>
												<td>Count View</td>
												<td>:</td>
												<td>{{ form.count_view }}</td>
											</tr>
											<tr>
												<td>Count Like</td>
												<td>:</td>
												<td>{{ form.count_like }}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-md-12 col-sm-12 themed-grid-col">
								<div class="card shadow">
									<div class="card-header">
										<h2 class="lead text-dark mb-0">Description</h2>
									</div>
									<div class="card-body">
										<p v-html="form.description"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import GoBack from "../GoBack.vue";
export default {
	components: {
		GoBack,
	},
	data() {
		return {
			page: "product",
			endpoint: "/api/products",
			endpoint_category: "/api/product_category",
			form: new Form({
				id: "",
				id_admin: "",
				name: "",
				image: "",
				storage_path_image: "",
				price: "0",
				weight: "0",
				stock: "0",
				condition: "New",
				description: "",
				is_published: "1",
				slug: "",
				transaction: "0",
				promo_price: "0",
				count_view: "0",
				count_like: "0",
				relationships: {
					category: {},
					admin: {},
				},
			}),
		};
	},
	mounted() {
		this.fetchData();
	},
	computed: {
		displayPrice: {
			get: function () {
				if (this.isInputActive) {
					// Cursor is inside the input field. unformat display value for user
					return this.form.price.toString();
				} else {
					return this.formatCurrency(this.form.price);
				}
			},
			set: function (modifiedValue) {
				let newValue = parseFloat(modifiedValue.replace(/[^\d\.]/g, ""));
				if (isNaN(newValue)) {
					newValue = 0;
				}
				this.form.price = newValue;
			},
		},
	},
	methods: {
		async fetchData() {
			let productId = this.$route.params.id;
			const self = this;
			this.submitted = false;

			if (productId) {
				this.form.id = productId;
				const result = await axios
					.get(self.endpoint + "/" + productId)
					.catch((error) => {
						let errMsg = "";
						if (typeof error.response.data === "object") {
							errMsg = _.flatten(_.toArray(error.response.data.errors));
						} else {
							errMsg = ["Something went wrong. Please try again."];
						}
						Swal.fire("Failed load data !", errMsg.join(""), "error");
					});

				this.form = result.data;
			}
		},
	},
};
</script>
