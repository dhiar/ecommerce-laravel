<template>
	<div>
		<div
			class="modal fade"
			id="modalAddress"
			tabindex="-1"
			aria-labelledby="addNewLabel"
			aria-hidden="true"
			style="width: 100%;"
		>
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<div class="h5 text-gray-800 line-height-222">
							Search By Address
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

					<form @submit.prevent="filterAddress">
						<div class="modal-body">
							<div class="form-group">
								<div class="form-group">
									<label for="province">Propinsi</label>
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
								</div>

								<div class="form-group">
									<label for="province">Kabupaten</label>
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
									></multiselect>
								</div>

								<div class="form-group">
									<label for="district">Kecamatan</label>
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
									></multiselect>
								</div>
								<div class="form-group">
									<label for="address">Desa (Jalan / Nomor / Gang)</label>
									<input
										v-model="formAddress.name"
										type="text"
										id="name"
										name="name"
										placeholder="name"
										class="form-control"
									/>
									<!-- :class="{
											'is-invalid': submitted && $v.formAddress.name.$error,

											'is-valid': !$v.formAddress.name.$invalid,
										}" -->
									<!-- <div class="valid-feedback">Address is valid.</div>
									<div
										v-if="submitted && !$v.formAddress.name.required"
										class="invalid-feedback"
									>
										Address harus diisi
									</div>
									<div
										v-if="submitted && !$v.formAddress.name.maxLength"
										class="invalid-feedback"
									>
										Address terlalu panjang ( maks :
										{{ $v.formAddress.name.$params.maxLength.max }} karakter )
									</div>
									<div
										v-if="submitted && !$v.formAddress.name.minLength"
										class="invalid-feedback"
									>
										Address terlalu pendek ( maks :
										{{ $v.formAddress.name.$params.minLength.min }} karakter )
									</div> -->
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">
								Close
							</button>
							<button
								type="submit"
								:disabled="formAddress.busy"
								class="btn btn-primary"
							>
								Search
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="filter-widget">
			<h4 class="fw-title" style="margin-bottom: 10px;">Category</h4>
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
		<div class="filter-widget">
			<h4 class="fw-title" style="margin-bottom: 10px;">Brand</h4>
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
		<div class="filter-widget">
			<h4 class="fw-title" style="margin-bottom: 10px;">Tags</h4>
			<multiselect
				v-model="product_tags"
				:options="tags"
				placeholder="Select one"
				label="name"
				track-by="id"
				:searchable="true"
				:max-height="200"
				:max="10"
				style="width: 100%;"
				:multiple="true"
				:taggable="true"
				@tag="addTag"
				@search-change="fetchTags"
			></multiselect>
		</div>
		<div class="filter-widget">
			<a
				class="success-btn pd-cart bg-green-light"
				href="#"
				@click="filterProduct"
			>
				<span class="fa fa-search"></span>
				Search
			</a>
			<a class="danger-btn pd-cart" href="#" @click="showModalAddress">
				<span class="fa fa-search"></span>
				Location
			</a>
		</div>
	</div>
</template>

<script>
import { BPagination } from "bootstrap-vue";
import { required, minLength, maxLength } from "vuelidate/lib/validators";
export default {
	components: {
		"b-pagination": BPagination,
	},
	data() {
		return {
			submitted: false,
			page: "product",
			endpoint: "/api/products",
			endpoint_brand: "/api/product_brand",
			endpoint_category: "/api/product_category",
			endpoint_tags: "/api/tags",
			// endpoint_filter: "/api/filter-products",
			product_tags: [],
			tags: [],

			category: { id: "0", name: "All Category", slug: "", icon: "" },
			categories: [],
			brand: { id: "0", name: "All Brand", slug: "" },
			brands: [],

			formAddress: new Form({
				name: "",
				province_id: "",
				city_id: "",
				district_id: "",
				limit: 6,
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
			// name: {
			// 	required,
			// 	minLength: minLength(10),
			// 	maxLength: maxLength(150),
			// },
		},
	},
	methods: {
		filterAddress() {
			$("#modalAddress").modal("hide");
			this.$emit("fromChildFilterAddress", this.formAddress);
		},
		showModalAddress() {
			const self = this;
			// get exist data area-dropdown
			axios
				.get("/api/list-province")
				.then(({ data }) => {
					if (data.success) {
						self.data_province = data.data;
						self.province = _.find(self.data_province, function (obj) {
							return obj.id == self.formAddress.province_id;
						});
					} else {
						Swal.fire("Failed !", data.message, "error");
					}
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});

			$("#modalAddress").modal("show");
		},
		filterProduct() {
			this.$emit("fromChildSetModal", {
				brand_id: this.brand.id,
				category_id: this.category.id,
				product_tags: this.product_tags,
			});
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
		fetchData() {
			const self = this;
			this.asyncFindCategory("");
			this.fetchTags("");
		},
		async asyncFindCategory(query) {
			// list of category
			const categories = await axios
				.get(this.endpoint_category + "?q=" + query, {
					params: {
						fieldOrder: "name",
						sort: "ASC",
					},
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});
			this.categories = categories.data.data;
			this.categories.unshift({
				id: "0",
				name: "All Category",
				slug: "",
			});
		},
		async onSelectCategory(option) {
			await axios
				.get(this.endpoint_brand + "/category/" + option.id)
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
		},
		async asyncFindBrand(query) {
			const brands = await axios
				.get(this.endpoint_brand + "?q=" + query, {
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
		fetchTags(query) {
			axios
				.get(this.endpoint_tags + "?q=" + query)
				.then(({ data }) => {
					this.tags = data.data;
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});
		},
		addTag(newTag) {
			const tag = {
				name: newTag,
				id: "",
			};

			this.product_tags.push(tag);
			this.tags.push(tag);
		},
	},

	mounted() {
		this.fetchData();
	},
};
</script>
