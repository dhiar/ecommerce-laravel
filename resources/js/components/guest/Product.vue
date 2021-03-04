<template>
	<div>
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
		<section class="product-shop spad page-details" style="padding-top:50px;">
			<div class="container">
				<div class="row">
					<div class="col-lg-3">
						<div class="filter-widget">
							<h4 class="fw-title">Brand</h4>
							<div class="fw-brand-check">
								<div class="bc-item">
									<label for="bc-calvin">
										Brand 1
										<input type="checkbox" id="bc-calvin" />
										<span class="checkmark"></span>
									</label>
								</div>
								<div class="bc-item">
									<label for="bc-diesel">
										Brand 2
										<input type="checkbox" id="bc-diesel" />
										<span class="checkmark"></span>
									</label>
								</div>
							</div>
						</div>
						<div class="filter-widget">
							<h4 class="fw-title">Tags</h4>
							<div class="fw-tags">
								<a href="#">Tag A</a>
								<a href="#">Tag B</a>
								<a href="#">Tag C</a>
							</div>
						</div>
					</div>
					<div class="col-lg-9">
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
                    <!-- {{product}} -->
										<span style="color:#212529; text-transform: none;">Owner Produk : {{ product.relationships.admin.name }} ,
											Location : {{ product.relationships.admin.relationships.address }}
                    </span>
										<h3>{{product.name}}</h3>
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
									<div class="pd-desc">
										<p>
											Description of product
										</p>
										<!-- <h4>harga_promo<span><harga_ori></span></h4> -->
										<h4>{{formatCurrency(product.price)}}</h4>
									</div>
									<div class="pd-size-choose">
										<div class="sc-item">
											<input type="radio" id="sm-size" />
											<label for="sm-size">s</label>
										</div>
										<div class="sc-item">
											<input type="radio" id="md-size" />
											<label for="md-size">m</label>
										</div>
										<div class="sc-item">
											<input type="radio" id="lg-size" />
											<label for="lg-size">l</label>
										</div>
									</div>
									<div class="quantity">
										<div class="pro-qty">
											<input type="text" value="1" />
										</div>
										<a
											class="success-btn pd-cart bg-green-light"
											href="https://api.whatsapp.com/send?phone=6281289482090&text=Selamat%20pagi%20bpk%2Fibu%2C%20ingin%20menanyakan%2C%20apakah%20produk%20berikut%20masih%20tersedia%3F%0D%0A%0D%0AProduk%20%3A%20Produk%201%0D%0AJumlah%20%3A10"
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
											<span>CATEGORY</span>: Category 1
										</li>
										<li><span>TAGS</span>: Tag A, Tag B, Tag C</li>
									</ul>
									<div class="pd-share">
										<div class="p-code">Sku : 00012</div>
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
											>DESCRIPTION</a
										>
									</li>
									<li>
										<a data-toggle="tab" href="#tab-2" role="tab"
											>SPECIFICATIONS</a
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
													<p>
														Deskripsi Produk
													</p>
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
												<tr>
													<td class="p-catagory">Price</td>
													<td>
														<div class="p-price">$495.00</div>
													</td>
												</tr>
												<tr>
													<td class="p-catagory">Availability</td>
													<td>
														<div class="p-stock">22 in stock</div>
													</td>
												</tr>
												<tr>
													<td class="p-catagory">Weight</td>
													<td>
														<div class="p-weight">1,3kg</div>
													</td>
												</tr>
												<tr>
													<td class="p-catagory">Sku</td>
													<td>
														<div class="p-code">00012</div>
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
import GoBack from "../admin/GoBack.vue";
import carousel from "../admin/Carousel.vue";
export default {
	components: {
		GoBack,
		carousel: carousel,
	},
	data() {
		return {
			page: "product",
			endpoint: "/api/products",
			dataImgBigUrl: "",
			images: [],
			product: {
				relationships: {
					admin: {
						relationships : {

						}
					}
				}
			},
		};
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
				let errMsg = "";
				if (typeof error.response.data === "object") {
					errMsg = _.flatten(_.toArray(error.response.data.errors));
				} else {
					errMsg = ["Something went wrong. Please try again."];
				}
				Swal.fire("Failed load data !", errMsg.join(""), "error");
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
		rawImages.forEach((obj, i) => {
			this.images.push({
				id: (i + 1).toString(),
				big: obj.image,
				thumb: obj.image,
			});
		});
	},
};
</script>
