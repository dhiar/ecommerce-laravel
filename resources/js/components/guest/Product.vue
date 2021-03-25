<template>
	<div>
		<div
			class="modal fade"
			id="modalOrder"
			tabindex="-1"
			aria-labelledby="addNewLabel"
			aria-hidden="true"
			style="width: 100%;"
		>
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<div class="h5 text-gray-800 line-height-222">
							Masukkan Identitas Anda
						</div>
					</div>
					<form @submit.prevent="orderViaWa()">
						<div class="modal-body">
							<div class="form-group">
								<label for="name">Name</label>
								<input
									v-model="form.name"
									type="text"
									id="name"
									name="name"
									placeholder="name"
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
								<label for="name">Alamat</label>
								<textarea
									v-model="form.address"
									placeholder="Detail alamat anda / penerima"
									name="address"
									id="desc"
									class="form-control"
									rows="5"
									:class="{
										'is-invalid': submitted && $v.form.address.$error,

										'is-valid': !$v.form.address.$invalid,
									}"
								></textarea>
								<div class="valid-feedback">Alamat is valid.</div>
								<div
									v-if="submitted && !$v.form.address.required"
									class="invalid-feedback"
								>
									Alamat harus diisi
								</div>
								<div
									v-if="submitted && !$v.form.address.maxLength"
									class="invalid-feedback"
								>
									Alamat terlalu panjang ( maks :
									{{ $v.form.address.$params.maxLength.max }} karakter )
								</div>
								<div
									v-if="submitted && !$v.form.address.minLength"
									class="invalid-feedback"
								>
									Alamat terlalu pendek ( min :
									{{ $v.form.address.$params.minLength.min }} karakter )
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">
								Close
							</button>
							<button
								type="submit"
								:disabled="form.busy"
								class="btn btn-success bg-green-light"
							>
								<span class="fa fa-whatsapp"></span>&nbsp;&nbsp;Hubungi Penjual
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="breacrumb-section">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="breadcrumb-text product-more">
							<a :href="baseURL"><i class="fa fa-home"></i> Home</a>
							<a :href="baseURL + '/products'">Products</a>
							<span>Detail</span>
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
						></filter-product>
					</div>
					<div class="col-lg-9">
						<div class="row" v-if="results.data && results.data.length > 0">
							<div class="col-lg-12">
								<div class="product-list">
									<div class="row">
										<div
											class="col-lg-4 col-sm-6"
											v-for="(item, idx) in results.data"
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

													<div class="product-price">
														{{ formatCurrency(item.price) }}
														<span
															style="
																color: #252525;
																text-decoration: none;
																font-size: 15px;
															"
															>/ pcs</span
														>
														<!-- <span>$35.00</span> -->
													</div>
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

						<div class="row">
							<div class="col-lg-6">
								<div class="card">
									<div class="card-content" style="padding: 10px;">
										<carousel
											:starting-image="2"
											:images="images"
											:auto-slide-interval="5000"
											:show-progress-bar="true"
										></carousel>
									</div>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="product-details">
									<div class="pd-title">
										<span style="color: #212529; text-transform: none;"
											>Seller : {{ product.relationships.admin.name }} ,
											Location :
											{{ product.relationships.admin.relationships.address.name }}
										</span>
										<h3>
											{{ product.name }}
										</h3>
										<a href="#" class="heart-icon"
											><i class="icon_heart_alt"></i
										></a>
									</div>
									<div class="pd-rating">
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star-o"></i>
										<span>(5)</span>
									</div>
									<div class="pd-desc" style="color: #636363; font-size: 15px;">
										<h4>{{ formatCurrency(product.price) }}</h4>
										<br />
										Weight : {{ formatWeight(product.weight) }}
										<!-- <h4>harga_promo<span><harga_ori></span></h4> -->
									</div>
									<!-- <div class="pd-size-choose">
										<div class="sc-item">
											<input type="radio" id="sm-size" />
											<label for="sm-size">s</label>
										</div>
									</div> -->
									<div class="quantity">
										<div class="pro-qty">
											<span class="dec qtybtn" @click="changeNumber(-1)"
												>-</span
											>
											<input
												type="text"
												v-model="count"
												@input="validNumber()"
											/>
											<span class="inc qtybtn" @click="changeNumber(1)">+</span>
										</div>
										<a
											@click="modalOrder"
											href="#"
											class="success-btn pd-cart bg-green-light"
										>
											<span class="fa fa-whatsapp"></span>
											Order Via WA
										</a>
									</div>
									<div class="quantity">
										<div class="pro-qty-none"></div>
										<a href="#" class="primary-btn pd-cart">Add To Cart</a>
									</div>
									<ul class="pd-tags">
										<li>
											<span>Category </span> :
											{{ product.relationships.category.name }}
										</li>
										<li>
											<span>Brand </span> :
											{{ product.relationships.brand.name }}
										</li>
										<li>
											<span>Tags</span> :
											<span
												class="badge badge-primary text-gray-100 btn"
												style="margin: 2px;"
												v-for="(tag, idx) in product.relationships.tags"
												:key="tag.id"
											>
												{{ tag.name }}
											</span>
										</li>
									</ul>
									<div class="pd-share">
										<!-- <div class="p-code">Sku : 00012</div> -->
										<div class="pd-social">
											<a href="#"><i class="ti-facebook"></i></a>
											<a href="#"><i class="ti-twitter-alt"></i></a>
											<a href="#"><i class="ti-linkedin"></i></a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="product-tab">
							<div class="tab-item">
								<ul class="nav" role="tablist">
									<li>
										<a class="active" data-toggle="tab" href="#tab-1" role="tab"
											>Deskripsi</a
										>
									</li>
									<li>
										<a data-toggle="tab" href="#tab-2" role="tab"
											>Grosir</a
										>
									</li>
								</ul>
							</div>
							<div class="tab-item-content">
								<div class="tab-content">
									<div
										class="tab-pane fade-in active"
										id="tab-1"
										role="tabpanel"
									>
										<div class="product-content">
											<div class="row">
												<div class="col-lg-7">
													<h5>Info Produk</h5>
													<p v-html="product.description"></p>
												</div>
												<div class="col-lg-5">
													<img src="img/product-single/tab-desc.jpg" alt="" />
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="tab-2" role="tabpanel">
										<div class="specification-table">
												<table>
													<tr v-for="(grosir, idx) in product.relationships.grosirs" :key="grosir.id">
														<td class="p-catagory">Minimal Beli</td>
														<td>
															<div class="p-price text-center">
																{{ grosir.min }}
															</div>
														</td>
														<td style="width:10%;"></td>
														<td class="p-catagory">Harga</td>
														<td style="width:20%;">
															<div class="p-price text-right">
																{{ formatCurrency(grosir.price) }}
															</div>
														</td>
													</tr>
												</table>
										</div>
									</div>
								</div>
							</div>
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
import { required, minLength, maxLength } from "vuelidate/lib/validators";
export default {
	components: {
		carousel: carousel,
		"b-pagination": BPagination,
	},
	data() {
		return {
			submitted: false,
			page: "product",
			endpoint: "/api/products",
			endpoint_filter: "/api/filter-products",
			endpoint_tags: "/api/product_tags",
			endpoint_transaction: "/api/transactions",
			dataImgBigUrl: "",
			form: new Form({
				name: "",
				address: "",
			}),
			images: [],
			product: {
				relationships: {
					admin: {
						relationships: {},
					},
					category: {
						id: "",
						name: "",
					},
					brand: {
						id: "",
						name: "",
					},
					grosirs:[]
				},
			},
			count: 1,
			categoryId: "",
			brandId: "",
			product_tags: [],
			currentPage: 1,
			perPage: 6,
			totalItems: 50,
			results: {},
		};
	},
	validations: {
		form: {
			name: {
				required,
				minLength: minLength(5),
				maxLength: maxLength(30),
			},
			address: {
				required,
				minLength: minLength(5),
				maxLength: maxLength(300),
			},
		},
	},
	async created() {
		let productSlug = this.$route.params.slug;

		const self = this;
		const result = await axios
			.get(self.endpoint, {
				params: {
					slug: productSlug,
				},
			})
			.catch((error) => {
				this.showErrorMessage(error);
			});

		this.product = result.data.data[0];

		this.images = [
			{
				id: "0",
				big: result.data.data[0].image,
				thumb: result.data.data[0].image,
			},
		];

		let rawImages = this.product.relationships.images;

		if (rawImages.length > 0) {
			rawImages.forEach((obj, i) => {
				this.images.push({
					id: (i + 1).toString(),
					big: obj.image,
					thumb: obj.image,
				});
			});
		} else {
			this.images.push({
				id: "1",
				big: this.images[0].big,
				thumb: this.images[0].thumb,
			});
			this.images.push({
				id: "2",
				big: this.images[0].big,
				thumb: this.images[0].thumb,
			});
		}
	},
	methods: {
		orderViaWa() {
			const self = this;
			this.submitted = true;
			this.$v.$touch();
			if (this.$v.$error) {
				return;
			} else {
				let product = self.product;
				// let product = productData
				// product.relationships = [];
				let name = this.form.name;
				let address = this.form.address;

				axios
					.post(self.endpoint_transaction, {
						product: product,
						name: name,
						address: address,
						is_wa: 1,
						is_grosir: 0,
						count: this.count,
					})
					.then(({ data }) => {
						if (data.success) {
							Swal.fire("Success !", data.message, "success");

							localStorage.setItem("invoice", data.data.invoice);
							let linkWA =
								"https://api.whatsapp.com/send?phone=" +
								product.relationships.admin.whatsapp +
								"&text=Selamat%20pagi%20bpk%2Fibu%20*" +
								product.relationships.admin.name +
								"*%20%2C%20ingin%20menanyakan%2C%20apakah%20produk%20berikut%20masih%20tersedia%3F%0D%0A%0D%0A" +
								encodeURIComponent(
									"Category : " + product.relationships.category.name
								) +
								"%0D%0A" +
								encodeURIComponent(
									"Brand : " + product.relationships.brand.name
								) +
								"%0D%0A" +
								"Name%20:%20*" +
								encodeURIComponent(product.name) +
								"*%0D%0A" +
								"Jumlah%20:%20*" +
								this.count +
								"*%0D%0A%0D%0A" +
								encodeURIComponent("Nama Customer : *" + name + "*") +
								"%0D%0A" +
								encodeURIComponent("Alamat : *" + address + "*") +
								"%0D%0A";

							window.open(linkWA, "_blank");
						} else {
							Swal.fire("Failed !", data.message, "error");
						}
						$("#modalOrder").modal("hide");
					})
					.catch((error) => {
						this.showErrorMessage(error);
					});
			}
		},
		modalOrder() {
			$("#modalOrder").modal("show");
			const self = this;
		},
		changeNumber(number) {
			this.count = parseInt(this.count);
			this.count += number;
			if (this.count <= 0) {
				this.count = 1;
			}
		},
		validNumber() {
			if (isNaN(this.count)) {
				this.count = 1;
			}
			this.count = parseInt(this.count);
			if (this.count <= 0) {
				this.count = 1;
			}
		},
		fromParentSetModal(val) {
			const self = this;
			this.categoryId = val.category_id;
			this.brandId = val.brand_id;
			this.product_tags = val.product_tags;

			let productTags = this.product_tags;

			axios
				.post(self.endpoint_filter, {
					id_category: this.categoryId,
					id_brand: this.brandId,
					product_tags: this.product_tags,
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

		async fetchProducts(page = 1) {
			const self = this;

			await axios
				.get(self.endpoint + "?page=" + page, {
					params: {
						id_category: this.categoryId,
						id_brand: this.brandId,
						product_tags: this.product_tags,
						limit: 6,
					},
				})
				.then(({ data }) => {
					this.currentPage = data.current_page;
					this.perPage = data.per_page;
					this.totalItems = data.total;
					this.results = data;
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
