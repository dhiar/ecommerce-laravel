<template>
	<div>
		<div class="breacrumb-section">
			<div class="container">
				<div class="row">
					<div class="col-6 col-sm-6 col-md-6 col-lg-6">
						<div class="breadcrumb-text product-more">
							<a :href="baseURL"><i class="fa fa-home"></i> Home</a>
							<a :href="baseURL + '/sellers'">Sellers</a>
						</div>
					</div>
					<div class="col-6 col-sm-6 col-md-6 col-lg-6">
						<div class="inner-header" style="padding: 0px !important;">
							<div class="advanced-search" style="padding: 0px !important;">
								<button type="button" class="category-btn">Search</button>
								<div class="input-group">
									<!-- @search-change="asyncSearchCategory" -->
									<multiselect
										v-model="seller"
										:options="sellers"
										placeholder="Search Seller"
										label="name"
										track-by="id"
										:searchable="true"
										:max-height="200"
										:max="10"
										style="
											margin-top: 5px !important;
											font-size: 13px !important;
										"
										@select="onSelectSeller"
									></multiselect>
									<!-- <button type="button"><i class="ti-search"></i></button> -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<section class="product-shop spad page-details" style="padding-top: 50px;">
			<div class="container">
				<div class="row">
					<div class="col-lg-3">
						<filter-product
							@fromChildSetModal="fromParentSetModal"
							@fromChildFilterAddress="fromParentFilterAddress"
						></filter-product>
					</div>
					<div class="col-lg-9">
						<div class="row" v-if="results.data && results.data.length > 0">
							<div class="col-lg-12">
								<div class="product-list">
									<div class="row">
										<div
											class="col-6 col-lg-4 col-sm-6 themed-grid-col"
											v-for="item in results.data"
											:key="item.id"
											style="margin-bottom: 20px !important;"
										>
											<div class="product-item">
												<div class="pi-pic">
													<a href="#">
														<img
															v-if="item.photo"
															:src="item.photo"
															alt=""
															@error="imgErrorCondition"
															class="img-fluid"
															style="
																width: 100% !important;
																object-fit: cover !important;
																height: 250px !important;
															"
														/>
													</a>
												</div>
												<div class="pi-text">
													<div class="catagory-name">
														{{
															item.relationships.address.name +
															", " +
															item.relationships.address.district +
															", " +
															item.relationships.address.city +
															", " +
															item.relationships.address.province
														}}
													</div>
													<a href="#">
														<h5>{{ item.name }}</h5>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
								<br />
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
								<!-- 
								</div> -->
							</div>
						</div>

						<div
							class="row text-center"
							v-if="results.data && results.data.length == 0"
						>
							<div class="col-lg-12 text-center">
								<h4 style="color: #252525; font-weight: 700;">
									Produk Tidak Ditemukan.
								</h4>
							</div>
						</div>

						<div v-if="results.data">
							<hr />
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</template>

<script>
import carousel from "../admin/Carousel.vue";
import { BPagination } from "bootstrap-vue";
export default {
	components: {
		carousel: carousel,
		"b-pagination": BPagination,
	},
	data() {
		return {
			seller: { id: "0", name: "All Seller" },
			sellers: [
				{ id: "0", name: "All Seller" },
				{ id: "4", name: "Rocky Ahmad" },
				{ id: "5", name: "Nuruddin Zangky" },
				{ id: "6", name: "Friza Rahmi Artini" },
			],
			searchName: "",
			page: "product",
			endpoint: "/api/sellers",
			endpoint_filter: "/api/filter-sellers",
			categoryId: "",
			brandId: "",
			currentPage: 1,
			perPage: 6,
			totalItems: 50,
			results: {},
			addressName: "",
			addressProvinceId: "",
			addressCityId: "",
			addressDistrictId: "",
		};
	},
	async created() {
		this.fetchSellers();
	},
	methods: {
		onSelectSeller(option) {
			this.seller = option;
			this.fetchSellers();
		},
		searchSeller(event, page = 1) {
			const self = this;

			if (event) {
				self.searchName = event.target.value;
			}

			axios
				.get(self.endpoint + "?page=" + page + "&q=" + self.searchName, {
					params: {
						limit: 6,
					},
				})
				.then(({ data }) => {
					this.currentPage = data.current_page;
					this.perPage = data.per_page;
					this.totalItems = data.total;
					this.results = data;
				})
				.catch(() => {
					this.$Progress.fail();
					Toast.fire({
						icon: "error",
						title: "Product not found.",
					});
				});
		},
		fromParentFilterAddress(formAddress) {
			const self = this;

			// remove input search product
			self.searchName = "";

			self.addressName = formAddress.name;
			self.addressProvinceId = formAddress.province_id;
			self.addressCityId = formAddress.city_id;
			self.addressDistrictId = formAddress.district_id;

			self.fetchSellers();
		},
		fromParentSetModal(val) {
			const self = this;

			// remove input search product
			self.searchName = "";

			self.categoryId = val.category_id;
			self.brandId = val.brand_id;
			self.product_tags = val.product_tags;

			self.fetchSellers();
		},
		async fetchSellers(page = 1) {
			const self = this;
			// if (self.searchName == "" || !self.searchName) {
			axios
				.post(self.endpoint_filter + "?page=" + page, {
					id_category: this.categoryId,
					id_brand: this.brandId,
					product_tags: this.product_tags,
					name: this.addressName,
					province_id: this.addressProvinceId,
					city_id: this.addressCityId,
					district_id: this.addressDistrictId,
					id_seller: this.seller.id,
					limit: 6,
				})
				.then(({ data }) => {
					this.currentPage = data.current_page;
					this.perPage = data.per_page;
					this.totalItems = data.total;
					this.results = data;
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});
			// }
		},
	},
	watch: {
		currentPage: {
			handler: function (value) {
				if (
					this.searchName &&
					this.searchName != "" &&
					!this.categoryId &&
					!this.brandId &&
					!this.brandId &&
					!this.addressName &&
					!this.addressProvinceId &&
					!this.addressCityId &&
					!this.addressDistrictId
					// address dll kosong
				) {
					console.log("searchName exist");
					// this.searchSeller(undefined, value);
					// province_id: this.addressProvinceId,
					// 	city_id: this.addressCityId,
					// 	district_id: this.addressDistrictId,
				}

				if (
					this.categoryId ||
					this.brandId ||
					this.brandId ||
					this.addressName ||
					this.addressProvinceId ||
					this.addressCityId ||
					this.addressDistrictId
				) {
					if (this.searchName && this.searchName != "") {
						console.log("searchName exist");
					} else {
						console.log("searchName NOT");
						this.fetchSellers(value);
					}
				} else {
					console.log("aaa");
					// jika pertama kali di-load, tampilkan semua seller tanpa filter
				}
			},
		},
	},
};
</script>
