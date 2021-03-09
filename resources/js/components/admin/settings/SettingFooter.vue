<template>
	<!-- Begin Page Content -->
	<div class="container-fluid mb-5">
		<div
			class="modal fade"
			id="modalFooter"
			tabindex="-1"
			aria-labelledby="addNewLabel"
			aria-hidden="true"
			style="width: 100%;"
		>
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<div class="h5 text-gray-800 line-height-222">
							{{ form.id ? "Update " : "Tambah" }} Navigasi
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

					<form @submit.prevent="createFooter(form.id)">
						<div class="modal-body">
							<div class="form-group">
								<label for="page_id">Pilih Halaman</label>

								<select
									name="page_id"
									id="page_id"
									v-model="form.page_id"
									class="form-control"
									:class="{
										'is-invalid': submitted && $v.form.page_id.$error,
										'is-valid': !$v.form.page_id.$invalid,
									}"
								>
									<option
										v-for="page in pages"
										v-bind:key="page.id"
										v-bind:value="page.id"
									>
										{{ page.title }}
									</option>
								</select>
								<div class="valid-feedback">Halaman is valid.</div>
								<div
									v-if="submitted && !$v.form.page_id.required"
									class="invalid-feedback"
								>
									Halaman harus dipilih
								</div>
							</div>
							<div class="form-group">
								<label for="navigation_id">Pilih Kategori</label>
								<select
									name="navigation_id"
									id="navigation_id"
									v-model="form.navigation_id"
									class="form-control"
									:class="{
										'is-invalid': submitted && $v.form.navigation_id.$error,
										'is-valid': !$v.form.navigation_id.$invalid,
									}"
								>
									<option
										v-for="nav in navigations"
										v-bind:key="nav.id"
										v-bind:value="nav.id"
									>
										{{ nav.name }}
									</option>
								</select>
								<div class="valid-feedback">Kategori is valid.</div>
								<div
									v-if="submitted && !$v.form.navigation_id.required"
									class="invalid-feedback"
								>
									Kategori harus dipilih
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

		<go-back></go-back>
		<!-- Page Heading -->
		<h1 class="h3 mb-2 text-gray-800 mb-4 mt-3">Pengaturan</h1>
		<div class="row">
			<div class="col-md-3">
				<div class="card shadow">
					<div class="card-body">
						<setting-menu></setting-menu>
					</div>
				</div>
			</div>

			<div class="col-md-9">
				<div class="card shadow">
					<div class="card-header">
						<div class="row">
							<div class="col-md-4 align-self-center">
								<h2 class="lead text-dark mb-0">Footer Navigasi</h2>
							</div>
							<div class="col-sm-8 float-right text-right">
								<button @click="showModalFooter()" class="btn btn-primary">
									Tambah Navigasi
								</button>
							</div>
						</div>
					</div>
					<div class="card-body table-responsive">
						<div v-for="result in results" :key="result.navigation.id">
							<!-- {{results}} -->
							<table class="table table-hover ml-2">
								<thead>
									<tr>
										<th class="font-weight-bold text-dark h5">
											{{ result.navigation.name }}
										</th>
									</tr>
								</thead>
								<tbody v-for="page in result.pages" :key="page.id">
									<tr>
										<td style="width: 80% !important;" class="m-2">
											{{ page.title }}
										</td>
										<td class="text-center">
											<a
												class="btn btn-sm btn-info"
												href="#"
												@click="showModalFooter(page.footer.id)"
												><i class="fa fa-pen text-gray-100"></i
											></a>
											<a
												class="btn btn-sm btn-danger"
												href="#"
												@click="deleteFooter(page.footer.id, page.title)"
												><i class="fa fa-trash-alt text-gray-100"></i
											></a>
										</td>
									</tr>
								</tbody>
							</table>
							<hr />
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import GoBack from "../GoBack.vue";
import { required, minLength, maxLength } from "vuelidate/lib/validators";

export default {
	components: {
		GoBack,
	},
	data() {
		return {
			currentPage: 1,
			perPage: 10,
			totalItems: 50,
			results: [],
			submitted: false,
			page: "navigation",
			endpoint: "/api/footers",
			endpoint_nav: "/api/navigations",
			endpoint_pages: "/api/pages",

			pages: [],
			navigations: [],
			form: new Form({
				id: "",
				page_id: "",
				navigation_id: "",
			}),
		};
	},
	validations: {
		form: {
			navigation_id: {
				required,
			},
			page_id: {
				required,
			},
		},
	},
	mounted() {
		this.fetchData(1);
	},
	methods: {
		async showModalFooter(id) {
			this.submitted = false;

			const self = this;
			self.form.id = id;

			// get data navigations & pages
			const navigations = await axios.get(self.endpoint_nav).catch((error) => {
				this.showErrorMessage(error);
			});

			console.log("navigations = " + JSON.stringify(navigations));
			self.navigations = navigations.data.data;

			const pages = await axios.get(self.endpoint_pages).catch((error) => {
				this.showErrorMessage(error);
			});

			self.pages = pages.data.data;

			$("#modalFooter").modal("show");

			if (id) {
				const result = await axios
					.get(self.endpoint + "/" + id)
					.catch((error) => {
						this.showErrorMessage(error);
					});

				alert("result.data = " + JSON.stringify(result.data));
				this.form = result.data;
			} else {
				// clear form
				if (!this.submitted) {
					self.clearForm(self.form);
				}
			}
		},
		async createFooter(id) {
			this.submitted = true;
			const self = this;

			this.$v.$touch();
			if (this.$v.$error) {
				return;
			} else {
				if (id) {
					axios
						.put(self.endpoint + "/" + id, this.form)
						.then(({ data }) => {
							if (data.success) {
								Swal.fire("Success !", data.message, "success");
							} else {
								Swal.fire("Failed !", data.message, "error");
							}
							$("#modalFooter").modal("hide");
							this.fetchData();
						})
						.catch((error) => {
							this.showErrorMessage(error);
						});
				} else {
					await axios
						.post(self.endpoint, this.form)
						.then(({ data }) => {
							if (data.success) {
								Swal.fire("Success !", data.message, "success");
							} else {
								Swal.fire("Failed !", data.message, "error");
							}
							$("#modalFooter").modal("hide");
							this.fetchData();
						})
						.catch((error) => {
							this.showErrorMessage(error);
						});
				}
			}
			this.submitted = false;
		},
		deleteFooter(id, name) {
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
						.delete(self.baseURL + self.endpoint + "/" + id)
						.then(({ data }) => {
							if (data.success) {
								Swal.fire("Success !", data.message, "success");
							} else {
								Swal.fire("Failed !", data.message, "error");
							}
							$("#modalFooter").modal("hide");
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
			await axios.get(self.endpoint + "?page=" + page).then(({ data }) => {
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
				this.fetchData(value);
			},
		},
	},
};
</script>
