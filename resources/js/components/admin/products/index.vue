<template>
	<!-- Begin Page Content -->
	<div class="container-fluid mb-5">
		<go-back></go-back><br />
		<!-- Page Heading -->
		<div class="card shadow">
			<div class="card-header">
				<div class="row">
					<div class="col-md-3 align-self-center">
						<h2 class="lead text-dark mb-0">Products</h2>
					</div>
					<div class="col-sm-3 float-right text-right">
						<multiselect
							v-model="category"
							:options="categories"
							placeholder="Select one"
							label="name"
							track-by="id"
							:searchable="true"
							:max-height="200"
							:max="10"
							style="width: 100%;"
							@search-change="asyncFindCategory"
							@select="onSelectCategory"
						></multiselect>
					</div>
					<div class="col-sm-3 float-right text-right">
						<multiselect
							v-model="brand"
							:options="brands"
							placeholder="Select one"
							label="name"
							track-by="id"
							:searchable="true"
							:max-height="200"
							:max="10"
							style="width: 100%;"
							@search-change="asyncFindBrand"
						></multiselect>
					</div>
					<div class="col-sm-auto float-right text-right">
						<button @click="pageNewProduct()" class="btn btn-primary">
							Tambah Product
						</button>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th class="text-center" style="width: 5% !important;">No</th>
							<th class="text-center" style="width: 10% !important;">Image</th>
							<th style="width: 20% !important;">Name</th>
							<th style="width: 15% !important;">Price</th>
							<th style="width: 10% !important;">Stock</th>
							<th style="width: 15% !important;">Category</th>
							<!-- <th style="width: 10% !important">Publish</th> -->
							<th style="width: 12% !important;">Created By</th>
							<th class="text-center">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(item, idx) in results.data" :key="item.id">
							<td class="text-center">{{ getNumber(currentPage, idx) }}</td>
							<td class="text-center">
								<img
									:src="item.image"
									@error="imgErrorCondition"
									class="img-fluid"
									style="max-height: 100px !important;"
								/>
								<a
									@click="addImages(item.id)"
									class="badge badge-success text-gray-100 btn img-fluid"
									style="width: 100% !important;"
									>Add Images</a
								>
								<a
									@click="cloneProduct(item.id)"
									class="badge badge-primary text-gray-100 btn"
									style="width: 100% !important;"
									>clone</a
								>
							</td>
							<td>{{ item.name }}</td>
							<td>
								{{ formatCurrency(item.price) }}
								<a
									@click="addGrosir(item.id)"
									class="badge badge-success text-gray-100 btn"
									>Add Grosir</a
								>
							</td>
							<td>{{ item.stock }}</td>
							<td>{{ item.relationships.category.name }}</td>
							<!-- <td>{{ item.is_published | isPublished }}</td> -->
							<td>{{ item.relationships.admin.name | upText }}</td>
							<td class="text-center">
								<a
									class="btn btn-sm btn-success"
									href="#"
									@click="pageDetailProduct(item.id)"
									><i class="fa fa-eye text-gray-100"></i
								></a>
								<a
									class="btn btn-sm btn-info"
									href="#"
									@click="pageNewProduct(item.id)"
									><i class="fa fa-pen text-gray-100"></i
								></a>

								<a
									class="btn btn-sm btn-danger"
									href="#"
									@click="deleteProduct(item.id, item.name)"
									><i class="fa fa-trash-alt text-gray-100"></i
								></a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="card-footer">
				<!-- pagination -->
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
import GoBack from "../GoBack.vue";
import { BPagination } from "bootstrap-vue";
export default {
	components: {
		"b-pagination": BPagination,
		GoBack,
	},
	data() {
		return {
			currentPage: 1,
			perPage: 10,
			totalItems: 50,
			results: {},
			page: "product",
			endpoint: "/api/products",
			endpoint_clone: "/api/clone-product",
			endpoint_category: "/api/product_category",
			category: { id: "0", name: "All Category", slug: "", icon: "" },
			categories: [],
			brand: { id: "0", name: "All Brand", slug: "" },
			brands: [],
		};
	},
	mounted() {
		this.fetchData(1);
	},
	methods: {
		async onSelectCategory(option) {
			await axios
				.get("/api/product_brand/category/" + option.id)
				.then(({ data }) => {
					let brands = data.data;
					brands.unshift({
						id: "0",
						name: "All Brand",
					});
					this.brands = brands;
					this.brand = { id: "0", name: "All Brand" };
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});

			this.fetchData(1);
		},
		async asyncFindBrand(query) {
			const brands = await axios
				.get("/api/product_brand?q=" + query, {
					params: {
						fieldOrder: "name",
						sort: "ASC",
						id_category: this.category.id,
					},
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});
			this.brands = brands.data.data;

			if (this.brands.length == 0) {
				this.brands = [{ id: "0", name: "All Brand", slug: "" }];
			}
		},
		async asyncFindCategory(query) {
			// list of category
			const categories = await axios
				.get("/api/product_category?q=" + query, {
					params: {
						fieldOrder: "name",
						sort: "ASC",
					},
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});
			this.categories = categories.data.data;
		},
		addImages(id) {
			this.$router.push({ path: `/admin/product/images/` + id });
		},
		addGrosir(id) {
			this.$router.push({ path: `/admin/product/grosir/` + id });
		},
		pageDetailProduct(id) {
			this.$router.push({ path: `/admin/product/detail/` + id });
		},
		pageNewProduct(id) {
			if (id) {
				this.$router.push({ path: `/admin/product/` + id });
			} else {
				this.$router.push({ path: `/admin/product/create` });
			}
		},
		deleteProduct(id, name) {
			const self = this;
			Swal.fire({
				title: "Are you sure delete " + this.page + " : " + name + " ?",
				text: "You won't be able to revert this!",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Yes, delete it!",
			}).then((result) => {
				if (result.value) {
					axios
						.delete(self.baseURL + self.endpoint + "/" + id)
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
				}
			});
		},
		async fetchData(page = 1) {
			const self = this;
			await axios
				.get(self.endpoint + "?page=" + page, {
					params: {
						id_category: self.category.id,
						id_brand: self.brand.id,
					},
				})
				.then(({ data }) => {
					this.currentPage = data.current_page;
					this.perPage = data.per_page;
					this.totalItems = data.total;
					this.results = data;
				});

			// get category
			const resultCategory = await axios
				.get(self.endpoint_category, {
					params: {
						fieldOrder: "name",
						sort: "ASC",
						withId: this.category.id,
					},
				})
				.catch((error) => {
					self.showErrorMessage(error);
				});

			this.categories = resultCategory.data.data;
			this.categories.unshift({
				id: "o",
				name: "All Category",
				slug: "",
				icon: "",
			});
		},
		async cloneProduct(id) {
			await axios
				.put(this.endpoint_clone + "/" + id)
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
						title: "Product not found.",
					});
				});
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
