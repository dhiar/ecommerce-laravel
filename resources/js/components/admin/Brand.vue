<template>
	<!-- Begin Page Content -->
	<div class="container-fluid mb-5">
		<div
			class="modal fade"
			id="modalBrand"
			tabindex="-1"
			aria-labelledby="addNewLabel"
			aria-hidden="true"
			style="width: 100%;"
		>
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<div class="h5 text-gray-800 line-height-222">
							{{ form.id ? "Update " : "Tambah" }} Brand
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

					<form @submit.prevent="createAdminBrand(form.id)">
						<div class="modal-body">
							<div class="form-group">
								<label for="name">Name</label>
								<input
									v-model="form.name"
									type="text"
									id="name"
									name="name"
									placeholder="name"
									@input="inputSlug()"
									class="form-control"
									:class="{
										'is-invalid': submitted && $v.form.name.$error,

										'is-valid': !$v.form.name.$invalid,
									}"
								/>
								<div class="valid-feedback">Name is valid.</div>
								<div
									v-if="submitted && !$v.form.name.required"
									class="invalid-feedback"
								>
									Name harus diisi
								</div>
								<div
									v-if="submitted && !$v.form.name.maxLength"
									class="invalid-feedback"
								>
									Name terlalu panjang ( maks :
									{{ $v.form.name.$params.maxLength.max }} karakter )
								</div>
								<div
									v-if="submitted && !$v.form.name.minLength"
									class="invalid-feedback"
								>
									Name terlalu pendek ( min :
									{{ $v.form.name.$params.minLength.min }} karakter )
								</div>
							</div>

							<div class="form-group">
								<label for="slug">Slug</label>
								<input
									v-model="form.slug"
									type="text"
									id="slug"
									name="slug"
									class="form-control"
									:class="{
										'is-invalid': submitted && $v.form.slug.$error,

										'is-valid': !$v.form.slug.$invalid,
									}"
									:readonly="true"
								/>
								<div class="valid-feedback">Slug is valid.</div>
							</div>

							<div class="form-group">
								<label for="id_category">Category</label>
								<multiselect
									v-model="category"
									:options="categories"
									placeholder="Select one"
									label="name"
									track-by="id"
									:searchable="true"
									:max-height="200"
									:max="10"
									@search-change="asyncFindCategory"
									:class="{
										'is-invalid': submitted && $v.form.id_category.$error,
										'is-valid': !$v.form.id_category.$invalid,
									}"
								></multiselect>
								<div class="valid-feedback">Category is valid.</div>
								<div
									v-if="submitted && !$v.form.id_category.required"
									class="invalid-feedback"
								>
									Category harus diisi
								</div>
							</div>
						</div>
						<!--modal body-->

						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">
								Close
							</button>
							<button
								type="submit"
								:disabled="form.busy"
								class="btn btn-primary"
							>
								Save
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<go-back></go-back><br />
		<!-- Page Heading -->
		<div class="card shadow">
			<div class="card-header">
				<div class="row no-gutters">
					<div class="col-md-7 align-self-center">
						<h2 class="lead text-dark mb-0">Brand</h2>
					</div>
					<div class="col-md-3 float-right text-right">
						<span>
							<multiselect
								v-model="searchCategory"
								:options="searchCategories"
								placeholder="Search Category"
								label="name"
								track-by="id"
								:searchable="true"
								:max-height="200"
								:max="10"
								@select="onSelectCategory"
								@search-change="asyncSearchCategory"
							></multiselect>
						</span>
					</div>
					<div class="col-md-2 float-right text-right">
						<button @click="showModalBrand()" class="btn btn-primary">
							Tambah Brand
						</button>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th class="text-center" style="width: 8% !important;">No</th>
							<th style="width: 25% !important;">Brand</th>
							<th style="width: 25% !important;">Slug</th>
							<th style="width: 20% !important;">
								Category
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
								{{ item.name }} <br />
								<a
									@click="cloneBrand(item.id)"
									class="badge badge-primary text-gray-100 btn"
									>clone</a
								>
							</td>
							<td>{{ item.slug }}</td>
							<td>
								{{
									item.relationships.category_brand.relationships.category.name
								}}
							</td>
							<td class="text-center">
								<a
									class="btn btn-sm btn-info"
									href="#"
									@click="showModalBrand(item.id)"
									><i class="fa fa-pen text-gray-100"></i
								></a>

								<a
									class="btn btn-sm btn-danger"
									href="#"
									@click="deleteBrand(item.id, item.name)"
									><i class="fa fa-trash-alt text-gray-100"></i
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
import { required, minLength, maxLength } from "vuelidate/lib/validators";

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
			page: "Brand",
			endpointBrand: "/api/product_brand",
			endpointBrand_clone: "/api/clone-brand",
			endpointCategory: "/api/product_category",
			// form brand
			form: new Form({
				id: "",
				name: "",
				slug: "",
				id_category: "",
			}),
			category: { id: "", name: "", slug: "", icon: "" },
			categories: [],
			searchCategory: { id: "0", name: "All Category", slug: "", icon: "" },
			searchCategories: [],
		};
	},
	validations: {
		form: {
			name: {
				required,
				minLength: minLength(5),
				maxLength: maxLength(30),
			},
			slug: {
				required,
				minLength: minLength(5),
				maxLength: maxLength(30),
			},
			id_category: {
				required,
			},
		},
	},
	mounted() {
		this.fetchData(1);
	},
	methods: {
		async onSelectCategory(option) {
			await axios
				.get("/api/product_brand/category/" + option.id)
				.then(({ data }) => {
					this.currentPage = data.current_page;
					this.perPage = data.per_page;
					this.totalItems = data.total;
					this.results = data;
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});
		},
		async asyncFindCategory(query) {
			// list of products
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
		async asyncSearchCategory(query) {
			// list of products
			const searchCategories = await axios
				.get("/api/product_category?q=" + query, {
					params: {
						fieldOrder: "name",
						sort: "ASC",
					},
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});
			this.searchCategories = searchCategories.data.data;
			self.searchCategories.unshift({
				id: "0",
				name: "All Category",
				slug: "",
				icon: "",
			});
		},
		inputSlug() {
			const self = this;
			self.form.slug = self.sanitizeTitle(self.form.name);
		},
		async showModalBrand(id) {
			this.submitted = false;
			const self = this;
			this.form.id = id;

			if (id) {
				const result = await axios
					.get(self.endpointBrand + "/" + id)
					.catch((error) => {
						this.showErrorMessage(error);
					});

				self.form = result.data;
				self.form.id_category =
					result.data.relationships.category_brand.id_category;
				self.category =
					result.data.relationships.category_brand.relationships.category;
			} else {
				// clear form
				if (!this.submitted) {
					self.clearForm(self.form);
				}
			}

			// get list of category
			const categories = await axios
				.get(self.endpointCategory, {
					params: {
						fieldOrder: "name",
						sort: "ASC",
						withId: self.form.id_category,
					},
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});

			self.categories = categories.data.data;

			$("#modalBrand").modal("show");
		},
		async createAdminBrand(id) {
			this.submitted = true;
			const self = this;
			self.form.id_category = self.category.id;

			this.$v.$touch();
			if (this.$v.$error) {
				return;
			} else {
				if (id) {
					axios
						.put(self.endpointBrand + "/" + id, this.form)
						.then(({ data }) => {
							if (data.success) {
								Swal.fire("Success !", data.message, "success");
							} else {
								Swal.fire("Failed !", data.message, "error");
							}
							$("#modalBrand").modal("hide");
							this.fetchData();
						})
						.catch((error) => {
							this.showErrorMessage(error);
						});
				} else {
					await axios
						.post(self.endpointBrand, this.form)
						.then(({ data }) => {
							if (data.success) {
								Swal.fire("Success !", data.message, "success");
							} else {
								Swal.fire("Failed !", data.message, "error");
							}
							$("#modalBrand").modal("hide");
							this.fetchData();
						})
						.catch((error) => {
							this.showErrorMessage(error);
						});
				}
			}
			this.submitted = false;
		},
		deleteBrand(id, name) {
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
						.delete(self.baseURL + self.endpointBrand + "/" + id)
						.then(({ data }) => {
							if (data.success) {
								Swal.fire("Success !", data.message, "success");
							} else {
								Swal.fire("Failed !", data.message, "error");
							}
							$("#modalBrand").modal("hide");
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

			await axios.get(self.endpointBrand + "?page=" + page).then(({ data }) => {
				this.currentPage = data.current_page;
				this.perPage = data.per_page;
				this.totalItems = data.total;
				this.results = data;
			});
		},
		async cloneBrand(id) {
			await axios
				.put(this.endpointBrand_clone + "/" + id)
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
	async created() {
		const self = this;
		// get list of category
		const searchCategories = await axios
			.get(self.endpointCategory, {
				params: {
					fieldOrder: "name",
					sort: "ASC",
				},
			})
			.catch((error) => {
				this.showErrorMessage(error);
			});

		self.searchCategories = searchCategories.data.data;
		self.searchCategories.unshift({
			id: "0",
			name: "All Category",
			slug: "",
			icon: "",
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

<style scoped>
.nav-tabs .nav-link {
	margin-bottom: -1px;
	border: 1px solid #dee2e6;
	border-top-left-radius: 0.25rem;
	border-top-right-radius: 0.25rem;
}

.nav-tabs .nav-link.active,
.nav-tabs .nav-item.show .nav-link {
	background-color: #3490dc;
	color: whitesmoke;
}
</style>
