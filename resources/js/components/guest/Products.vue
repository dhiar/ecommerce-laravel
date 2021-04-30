<template>
	<div>
		<div class="breacrumb-section">
			<div class="container">
				<div class="row">
					<div class="col-6 col-sm-6 col-md-6 col-lg-6">
						<div class="breadcrumb-text product-more">
							<a :href="baseURL"><i class="fa fa-home"></i> Home</a>
							<a :href="baseURL + '/products'">Products</a>
						</div>
					</div>
					<div class="col-6 col-sm-6 col-md-6 col-lg-6">
						<div class="inner-header" style="padding: 0px !important;">
							<div class="advanced-search" style="padding: 0px !important;">
								<button type="button" class="category-btn">Search</button>
								<div class="input-group">
									<input
										type="text"
										class="text-gray-dark"
										placeholder="Search Product.."
									/>
									<button type="button"><i class="ti-search"></i></button>
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
						<!-- category, brand dari component -->
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
											class="col-lg-4 col-sm-6"
											v-for="item in results.data"
											:key="item.id"
										>
											<div class="product-item">
												<div class="pi-pic">
													<img
														v-if="item.image"
														:src="item.image"
														alt=""
														@error="imgErrorCondition"
														class="img-fluid img-thumbnail"
														style="
															object-fit: cover !important;
															height: 270px !important;
														"
													/>
													<div class="sale pp-sale">Sale</div>
													<div class="icon">
														<i class="icon_heart_alt"></i>
													</div>
													<ul>
														<li class="w-icon active">
															<a href="#"><i class="icon_bag_alt"></i></a>
														</li>
														<li class="quick-view">
															<a :href="baseURL + '/product/' + item.slug"
																>Quick View</a
															>
														</li>
														<li class="w-icon">
															<a href="#"><i class="fa fa-random"></i></a>
														</li>
													</ul>
												</div>
												<div class="pi-text">
													<div class="catagory-name">
														{{ item.relationships.category.name }}
													</div>
													<a href="#">
														<h5>{{ item.name }}</h5>
													</a>
													<!-- <div class="product-price">
														{{ formatCurrency(item.price) }}
														<span
															style="
																color: #252525;
																text-decoration: none;
																font-size: 15px;
															"
															>/ pcs</span
														>
													</div> -->
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
			page: "product",
			endpoint: "/api/products",
			endpoint_filter: "/api/filter-products",
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
		this.fetchProducts();
	},
	methods: {
		fromParentFilterAddress(formAddress) {
			const self = this;

			self.addressName = formAddress.name;
			self.addressProvinceId = formAddress.province_id;
			self.addressCityId = formAddress.city_id;
			self.addressDistrictId = formAddress.district_id;

			self.fetchProducts();
		},
		fromParentSetModal(val) {
			const self = this;
			self.categoryId = val.category_id;
			self.brandId = val.brand_id;
			self.product_tags = val.product_tags;

			self.fetchProducts();
		},
		async fetchProducts(page = 1) {
			const self = this;
			axios
				.post(self.endpoint_filter + "?page=" + page, {
					id_category: this.categoryId,
					id_brand: this.brandId,
					product_tags: this.product_tags,
					name: this.addressName,
					province_id: this.addressProvinceId,
					city_id: this.addressCityId,
					district_id: this.addressDistrictId,
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
		},
	},
	watch: {
		currentPage: {
			handler: function (value) {
				this.fetchProducts(value);
			},
		},
	},
};
</script>
