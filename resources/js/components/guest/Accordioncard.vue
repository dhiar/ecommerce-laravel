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
					Component Types:
					<span :class="kitItem">{{ cardData.itemCount }}</span>
				</div>
			</a>
		</div>
		<transition name="collapse">
			<div v-show="mutableActive">
				<div class="kit-contents card-body" style="height:100% !important;">
					alamat
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
				this.cardData.status === "active" ? "badge-primary" : "badge-danger",
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
