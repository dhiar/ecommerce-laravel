<template>
	<div>
		<div class="breacrumb-section" style="padding: 0px !important;">
			<div class="container">
				<div v-if="$isMobile()" class="row">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<div class="inner-header" style="padding: 0px !important;">
							<div class="advanced-search" style="padding: 0px !important;">
								<button type="button" class="category-btn">Search</button>
								<div class="input-group">
									<input
										type="text"
										class="text-gray-dark"
										placeholder="Search Product.."
										v-model="searchName"
										@keyup.enter="searchProduct"
									/>
									<button type="button"><i class="ti-search"></i></button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div v-else class="row">
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
										v-model="searchName"
										@keyup.enter="searchProduct"
									/>
									<button type="button"><i class="ti-search"></i></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<section class="product-shop spad page-details" style="padding-top: 20px;">
			<div class="container">
				<div class="row">
					<div class="col-12 col-lg-3 col-md-3 col-sm-3 themed-grid-col">
						<div
							class="card"
							style="box-shadow: none !important; border: none !important;"
						>
							<div class="card-header p-0">
								<a
									class="kit-toggle"
									href="#"
									@click.prevent="mutableActive = !mutableActive"
								>
									<div>
										Filter Product
										<span :class="kitStatus">Click Here</span>
									</div>

									<div>
										<!-- <span :class="kitItem"> -->
										<span>
											<i
												v-if="mutableActive"
												class="fa fa-chevron-up"
												aria-hidden="true"
											></i>
											<i
												v-else
												class="fa fa-chevron-down"
												aria-hidden="true"
											></i>
										</span>
									</div>
								</a>
							</div>

							<transition name="collapse">
								<div v-show="mutableActive" style="margin-top: 25px;">
									<filter-product
										@fromChildSetModal="fromParentSetModal"
										@fromChildFilterAddress="fromParentFilterAddress"
									></filter-product>
								</div>
							</transition>
						</div>
					</div>
					<div class="col-12 col-lg-9 col-md-9 col-sm-9 themed-grid-col">
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
			kitStatus: ["badge", "ml-2", "badge-success"],
			mutableActive: true,
			searchName: "",
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
		searchProduct(event, page = 1) {
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

			self.fetchProducts();
		},
		fromParentSetModal(val) {
			const self = this;

			// remove input search product
			self.searchName = "";

			self.categoryId = val.category_id;
			self.brandId = val.brand_id;
			self.product_tags = val.product_tags;

			self.fetchProducts();
		},
		async fetchProducts(page = 1) {
			const self = this;

			if (self.searchName == "" || !self.searchName) {
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
			}
		},
	},
	watch: {
		currentPage: {
			handler: function (value) {
				if (this.searchName && this.searchName != "") {
					this.searchProduct(undefined, value);
				} else {
					this.fetchProducts(value);
				}
			},
		},
	},
};
</script>
<style lang="scss" scoped>
.collapse-enter-active,
.collapse-leave-active {
	// transition: max-height 200ms;
	// max-height: 26rem;
	overflow: hidden;
}
.collapse-enter,
.collapse-leave-to {
	max-height: 0px;
	overflow: hidden;
}

.kit-toggle {
	color: #000;
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 0.5rem 1rem;
	&:hover {
		background-color: #ddd;
		text-decoration: none;
		color: #000;
	}

	.kit-code {
		width: 2.5rem;
		// height: 2.5rem;
		border: 2px solid #555;
		display: inline-block;
		color: #555;
		border-radius: 1.25rem;
		text-align: center;
		line-height: 2.25rem;
		margin-right: 0.5rem;
	}
	.item-count {
		font-size: 0.9rem;
	}
}
// .kit-contents {
// 	// min-height: 26rem;
// }
.items-list {
	// max-height: 14.8rem;
	// min-height: 5rem;
	overflow: auto;
	& li:nth-child(odd) {
		background: #eee;
	}
}
</style>
