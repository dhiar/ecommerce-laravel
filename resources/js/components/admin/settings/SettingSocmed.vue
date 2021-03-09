<template>
	<!-- Begin Page Content -->
	<div class="container-fluid mb-5">
		<div
			class="modal fade"
			id="modalSocmed"
			tabindex="-1"
			aria-labelledby="addNewLabel"
			aria-hidden="true"
			style="width: 100%;"
		>
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<div class="h5 text-gray-800 line-height-222">
							{{ form.id ? "Update " : "Tambah" }} Socmed
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

					<form @submit.prevent="createSocmed(form.id)">
						<div class="modal-body">
							<div class="form-group">
								<label for="name">Jenis Sosmed</label>
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
									Name terlalu pendek ( maks :
									{{ $v.form.name.$params.minLength.min }} karakter )
								</div>
							</div>
							<div class="form-group">
								<label for="icon">Icon Sosmed</label>
								<input
									v-model="form.icon"
									type="text"
									id="icon"
									name="icon"
									placeholder="fa fa-some-icon"
									class="form-control"
									:class="{
										'is-invalid': submitted && $v.form.icon.$error,
										'is-valid': !$v.form.icon.$invalid,
									}"
								/>
								<small class="text-muted">
									Buka link ini
									<a href="https://fontawesome.com/v4.7.0/icons">fontawesome</a>
									lalu cari nama sosmed. Misal icon untuk Facebook adalah
									facebook-f</small
								>
								<div class="valid-feedback">Icon is valid.</div>
								<div
									v-if="submitted && !$v.form.icon.required"
									class="invalid-feedback"
								>
									Icon harus diisi
								</div>
								<div
									v-if="submitted && !$v.form.icon.maxLength"
									class="invalid-feedback"
								>
									Icon terlalu panjang ( maks :
									{{ $v.form.icon.$params.maxLength.max }} karakter )
								</div>
								<div
									v-if="submitted && !$v.form.icon.minLength"
									class="invalid-feedback"
								>
									Icon terlalu pendek ( maks :
									{{ $v.form.icon.$params.minLength.min }} karakter )
								</div>
							</div>

							<div class="form-group">
								<label for="link">Link atau URL</label>
								<input
									v-model="form.link"
									type="text"
									id="link"
									name="link"
									placeholder="http://www.some-url.com"
									class="form-control"
									:class="{
										'is-invalid': submitted && $v.form.link.$error,

										'is-valid': !$v.form.link.$invalid,
									}"
								/>
								<div class="valid-feedback">Link is valid.</div>
								<div
									v-if="submitted && !$v.form.link.required"
									class="invalid-feedback"
								>
									Link harus diisi
								</div>
								<div
									v-if="submitted && !$v.form.link.maxLength"
									class="invalid-feedback"
								>
									Link terlalu panjang ( maks :
									{{ $v.form.link.$params.maxLength.max }} karakter )
								</div>
								<div
									v-if="submitted && !$v.form.link.minLength"
									class="invalid-feedback"
								>
									Link terlalu pendek ( maks :
									{{ $v.form.link.$params.minLength.min }} karakter )
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
								<h2 class="lead text-dark mb-0">Sosial Media</h2>
							</div>
							<div class="col-sm-8 float-right text-right">
								<button @click="showModalSocmed()" class="btn btn-primary">
									Tambah Socmed
								</button>
							</div>
						</div>
					</div>
					<div class="card-body table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th class="text-center" style="width: 8% !important;">No</th>
									<th style="width: 20% !important;">Jenis Socmed</th>
									<th style="width: 20% !important;">Icon</th>
									<th style="width: 35% !important;">Link atau URL</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(item, idx) in results.data" :key="item.id">
									<td class="text-center">{{ getNumber(currentPage, idx) }}</td>
									<td>{{ item.name }}</td>
									<td>{{ item.icon }}</td>
									<td>{{ item.link }}</td>
									<td>
										<a
											class="btn btn-sm btn-info"
											href="#"
											@click="showModalSocmed(item.id)"
											><i class="fa fa-pen text-gray-100"></i
										></a>
										<a
											class="btn btn-sm btn-danger"
											href="#"
											@click="deleteBanner(item.id, item.name)"
											><i class="fa fa-trash-alt text-gray-100"></i
										></a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="card-footer">
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
			</div>
		</div>
	</div>
</template>

<script>
import GoBack from "../GoBack.vue";
import { BPagination } from "bootstrap-vue";
import { required, minLength, maxLength } from "vuelidate/lib/validators";

export default {
	components: {
		GoBack,
		"b-pagination": BPagination,
	},
	data() {
		return {
			currentPage: 1,
			perPage: 10,
			totalItems: 50,
			results: {},
			submitted: false,
			page: "socmed",
			endpoint: "/api/base/socmeds",
			form: new Form({
				id: "",
				name: "",
				icon: "#",
				link: "",
			}),
		};
	},
	validations: {
		form: {
			name: {
				required,
				minLength: minLength(5),
				maxLength: maxLength(20),
			},
			icon: {
				required,
				minLength: minLength(5),
				maxLength: maxLength(20),
			},
			link: {
				required,
				minLength: minLength(1),
				maxLength: maxLength(200),
			},
		},
	},
	mounted() {
		this.fetchData(1);
	},
	methods: {
		async showModalSocmed(id) {
			this.submitted = false;
			$("#modalSocmed").modal("show");
			const self = this;
			this.form.id = id;
			if (id) {
				const result = await axios
					.get(self.endpoint + "/" + id)
					.catch((error) => {
						this.showErrorMessage(error);
					});

				this.form = result.data;
			} else {
				// clear form
				if (!this.submitted) {
					self.clearForm(self.form);
				}
			}
		},
		async createSocmed(id) {
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
							$("#modalSocmed").modal("hide");
							self.fetchData();
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
							$("#modalSocmed").modal("hide");
							self.fetchData();
						})
						.catch((error) => {
							this.showErrorMessage(error);
						});
				}
			}
			this.submitted = false;
		},
		deleteBanner(id, name) {
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
							$("#modalRekening").modal("hide");
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
