<template>
	<div class="card">
		<div class="card-header p-0">
			<a
				class="kit-toggle"
				href="#"
				@click.prevent="mutableActive = !mutableActive"
			>
				<div>
					<span class="kit-code">{{ cardData.code }}</span>
					{{ cardData.name }}
					<span :class="kitStatus">{{ cardData.status }}</span>
				</div>

				<div>
					<!-- <span :class="kitItem"> -->
					<span>
						<i
							v-if="mutableActive"
							class="fa fa-chevron-up"
							aria-hidden="true"
						></i>
						<i v-else class="fa fa-chevron-down" aria-hidden="true"></i>
					</span>
				</div>
			</a>
		</div>
		<transition name="collapse">
			<div v-show="mutableActive">
				<div
					v-if="cardData.code == 'A'"
					class="kit-contents card-body"
					style="height: 100% !important;"
				>
					<table class="table table-hover">
						<tr>
							<th style="width: 30% !important;">Invoice</th>
							<td>:</td>
							<td>{{ cardData.items.invoice }}</td>
						</tr>
						<tr>
							<th>Berat Produk</th>
							<td>:</td>
							<td>{{ formatWeight(cardData.items.weight_product) }}</td>
						</tr>
						<tr>
							<th>Harga Produk</th>
							<td>:</td>
							<td>{{ formatCurrency(cardData.items.price_product) }}</td>
						</tr>
					</table>
				</div>
				<div v-else-if="cardData.code == 'B'" class="kit-contents card-body">
					<table style="width: 100% !important;">
						<tr
							v-for="item in cardData.items"
							:key="item.id"
							style="width: 100% !important;"
						>
							<td>{{ item.relationships.product.name }}</td>
							<td
								style="text-align: center !important; width: 50% !important;"
								class="text-center"
							>
								<img
									v-if="item.relationships.product.image"
									:src="item.relationships.product.image"
									class="img-fluid text-center"
									@error="imgErrorCondition"
									style="width: 250px; height: 250px;"
								/>
							</td>
						</tr>
					</table>
				</div>
				<div v-else-if="cardData.code == 'C'" class="kit-contents card-body">
					<table class="table table-hover">
						<tr>
							<th style="width: 30% !important;">Penerima</th>
							<td>:</td>
							<td>{{ formatName(cardData.items.invoice) }}</td>
						</tr>
						<tr>
							<th>Phone Penerima</th>
							<td>:</td>
							<td>{{ cardData.items.phone_number }}</td>
						</tr>
						<tr>
							<th>Alamat</th>
							<td>:</td>
							<td
								v-if="
									cardData.items.address.district &&
									cardData.items.address.city &&
									cardData.items.address.province
								"
							>
								<span>{{
									cardData.items.address.name
										? cardData.items.address.name + " , "
										: ""
								}}</span>
								<span>{{
									cardData.items.address.district
										? "Kec." + cardData.items.address.district + " , "
										: ""
								}}</span>
								<span>{{
									cardData.items.address.city
										? "Kab/Kota." + cardData.items.address.city + " , "
										: ""
								}}</span>
								<span>{{
									cardData.items.address.province
										? "Propinsi." + cardData.items.address.province + " , "
										: ""
								}}</span>
							</td>
							<td></td>
							<!-- { "id": "Rd", "name": "Soloraya", "province_id": 12, "city_id": 1202, "district_id": 1202011, "province": "SUMATERA UTARA", "city": "KABUPATEN MANDAILING NATAL", "district": "SINUNUKAN" } -->
						</tr>
						<tr>
							<th>Ongkir</th>
							<td>:</td>
							<td>{{ formatCurrency(cardData.items.shipping_cost) }}</td>
						</tr>
					</table>
				</div>
				<div
					v-else
					class="kit-contents card-body"
					style="height: 100% !important;"
				>
					<table class="table table-hover">
						<tr>
							<th style="width: 30% !important;">Ekspedisi</th>
							<td>:</td>
							<td>{{ cardData.items.ekspedisi_name }}</td>
						</tr>
						<tr>
							<th>Harga Produk</th>
							<td>:</td>
							<td>{{ formatCurrency(cardData.items.price_product) }}</td>
						</tr>
						<tr>
							<th>Ongkir</th>
							<td>:</td>
							<td>{{ formatCurrency(cardData.items.shipping_cost) }}</td>
						</tr>
						<tr>
							<th>Total Bayar</th>
							<td>:</td>
							<td>
								{{
									formatCurrency(
										parseInt(cardData.items.price_product) +
											parseInt(cardData.items.shipping_cost)
									)
								}}
							</td>
						</tr>
						<tr>
							<th>Bukti Bayar</th>
							<td>:</td>
							<td>
								<img
									v-if="cardData.items.payment_image"
									:src="cardData.items.payment_image"
									class="img-fluid text-center"
									@error="imgErrorCondition"
									style="width: 250px; height: 250px;"
								/>
							</td>
						</tr>
						<tr>
							<th>Ekspedisi</th>
							<td>:</td>
							<td>{{ cardData.items.ekspedisi_name }}</td>
						</tr>
						<tr>
							<th>Status Kirim</th>
							<td>:</td>
							<td>{{ cardData.items.delivery_status.name }}</td>
						</tr>
						<tr>
							<th>No.Pengiriman</th>
							<td>:</td>
							<td>{{ cardData.items.delivery_number }}</td>
						</tr>
					</table>
				</div>
			</div>
		</transition>
	</div>
</template>

<script>
const eventBus = new Vue();
export default {
	name: "accordioncard",
	template: "#accordioncards",
	props: {
		active: {
			type: Boolean,
		},
		cardData: {
			type: [Array, Object],
		},
	},
	data: function () {
		return {
			mutableActive: JSON.parse(this.active),
			addRemove: 1,
			itemDest: 1,
			addStores: true,
			removeStores: false,
		};
	},
	computed: {
		kitItem: function () {
			return [
				"item-count",
				"badge",
				"badge-pill",
				this.cardData.itemCount > 0 ? "badge-secondary" : "badge-danger",
			];
		},
		kitStatus: function () {
			return [
				"badge",
				"ml-2",
				this.cardData.status === "complete" ? "badge-success" : "badge-warning",
			];
		},
	},
	methods: {
		activeFalse() {
			console.log("activeFalse");
		},
		activeTrue() {
			console.log("activeTrue");
		},
		setActiveFalse() {
			console.log("activeFalse");
		},
	},
	watch: {
		active: {
			handler: function (value) {
				this.mutableActive = value;
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
