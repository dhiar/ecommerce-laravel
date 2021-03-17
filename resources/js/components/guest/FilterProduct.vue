<template>
	<div>
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
		</div>
	</div>
</template>

<script>
import { BPagination } from "bootstrap-vue";
export default {
	components: {
		"b-pagination": BPagination,
	},
	data() {
		return {
			page: "product",
			endpoint: "/api/products",
			endpoint_brand: "/api/product_brand",
			endpoint_category: "/api/product_category",
			endpoint_tags: "/api/tags",
			product_tags: [],
			tags: [],

			category: { id: "0", name: "All Category", slug: "", icon: "" },
			categories: [],
			brand: { id: "0", name: "All Brand", slug: "" },
			brands: [],
		};
	},
	methods: {
		filterProduct() {
			this.$emit("fromChildSetModal", {
				brand_id: this.brand.id,
				category_id: this.category.id,
				product_tags: this.product_tags
			});
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
		fetchTags(query){
			axios
				.get(this.endpoint_tags + "?q=" + query)
				.then(({ data }) => {
					this.tags = data.data
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
