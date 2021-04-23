<style scoped>
.widget-user-header {
	background-position: "center center";
	background-size: cover;
	height: 250px;
}
</style>

<template>
	<div class="container">
		<div
			class="modal fade"
			id="modalAddress"
			tabindex="-1"
			aria-labelledby="addNewLabel"
			aria-hidden="true"
			style="width: 100%;"
		>
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<div class="h5 text-gray-800 line-height-222">
							Change Address
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

					<form @submit.prevent="updateAddress(formAddress.id)">
						<div class="modal-body">
							<div class="form-group">
								<label for="province">Propinsi</label>
								<multiselect
									v-model="province"
									:options="data_province"
									placeholder="Select one"
									label="name"
									track-by="id"
									:searchable="true"
									:max-height="150"
									:max="3"
									@select="onSelectProvince"
									:class="{
										'is-invalid':
											submitted && $v.formAddress.province_id.$error,
										'is-valid': !$v.formAddress.province_id.$invalid,
									}"
								></multiselect>
								<div class="valid-feedback">Propinsi is valid.</div>
								<div
									v-if="submitted && !$v.formAddress.province_id.required"
									class="invalid-feedback"
								>
									Propinsi harus diisi
								</div>
							</div>
							<div class="form-group">
								<label for="province">Kabupaten</label>
								<multiselect
									v-model="city"
									:options="data_city"
									placeholder="Select one"
									label="name"
									track-by="id"
									:searchable="true"
									:max-height="150"
									:max="3"
									@select="onSelectCity"
									:class="{
										'is-invalid': submitted && $v.formAddress.city_id.$error,
										'is-valid': !$v.formAddress.city_id.$invalid,
									}"
								></multiselect>

								<div class="valid-feedback">Kabupaten is valid.</div>
								<div
									v-if="submitted && !$v.formAddress.city_id.required"
									class="invalid-feedback"
								>
									Kabupaten harus diisi
								</div>
							</div>
							<div class="form-group">
								<label for="district">Kecamatan</label>
								<multiselect
									v-model="district"
									:options="data_district"
									placeholder="Select one"
									label="name"
									track-by="id"
									:searchable="true"
									:max-height="150"
									:max="3"
									@select="onSelectDistrict"
									:class="{
										'is-invalid':
											submitted && $v.formAddress.district_id.$error,
										'is-valid': !$v.formAddress.district_id.$invalid,
									}"
								></multiselect>
								<div class="valid-feedback">Kecamatan is valid.</div>
								<div
									v-if="submitted && !$v.formAddress.district_id.required"
									class="invalid-feedback"
								>
									Kecamatan harus diisi
								</div>
							</div>
							<div class="form-group">
								<label for="address">Desa (Jalan / Nomor / Gang)</label>
								<input
									v-model="formAddress.name"
									type="text"
									id="name"
									name="name"
									placeholder="name"
									class="form-control"
									:class="{
										'is-invalid': submitted && $v.formAddress.name.$error,

										'is-valid': !$v.formAddress.name.$invalid,
									}"
								/>
								<div class="valid-feedback">Address is valid.</div>
								<div
									v-if="submitted && !$v.formAddress.name.required"
									class="invalid-feedback"
								>
									Address harus diisi
								</div>
								<div
									v-if="submitted && !$v.formAddress.name.maxLength"
									class="invalid-feedback"
								>
									Address terlalu panjang ( maks :
									{{ $v.formAddress.name.$params.maxLength.max }} karakter )
								</div>
								<div
									v-if="submitted && !$v.formAddress.name.minLength"
									class="invalid-feedback"
								>
									Address terlalu pendek ( maks :
									{{ $v.formAddress.name.$params.minLength.min }} karakter )
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
								class="btn btn-primary"
							>
								Change
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div
			class="modal fade"
			id="modalRekening"
			tabindex="-1"
			aria-labelledby="addNewLabel"
			aria-hidden="true"
			style="width: 100%;"
		>
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<div class="h5 text-gray-800 line-height-222">
							Change Rekening User : {{ form.name }}
						</div>
					</div>
					<!-- modal header -->

					<form @submit.prevent="updateRekening(form.id)">
						<div class="modal-body">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">Nama Bank</div>
									<div class="col-sm-5">No. Rekening</div>
									<div class="col-sm-3">Aksi</div>
								</div>
							</div>
							<div
								class="form-group"
								v-for="(item, idx) in formAdminRekening.rekenings"
								:key="idx"
							>
								<div class="row">
									<div class="col-sm-4">
										<input
											v-model="formAdminRekening.rekenings[idx].rekening"
											type="text"
											class="form-control"
										/>
									</div>
									<div class="col-sm-5">
										<input
											v-model="formAdminRekening.rekenings[idx].number"
											type="text"
											class="form-control"
										/>
									</div>
									<div class="col-sm-3">
										<span>
											<!-- v-if="idx == formAdminRekening.rekenings.length - 1" -->
											<a
												class="btn btn-sm btn-primary"
												@click="addInputRekening"
												href="#"
												><i class="fa fa-plus text-gray-100"></i></a
										></span>
										<span>
											<a
												class="btn btn-sm btn-danger"
												@click="removeInputRekening(idx)"
												href="#"
												><i class="fa fa fa-times text-gray-100"></i
											></a>
										</span>
									</div>
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
								class="btn btn-primary"
							>
								Save
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card card-widget widget-user">
					<!-- Add the bg color to the header using any of the bg-* classes -->
					<div
						class="widget-user-header text-white"
						:style="{ 'background-image': `url(${backgroundUrl})` }"
					>
						<h3 class="widget-user-username text-right">{{ form.name }}</h3>
						<h5 class="widget-user-desc text-right">{{ form.job_title }}</h5>
					</div>
					<div class="widget-user-image">
						<img
							class="img-circle"
							:src="getProfilePhoto()"
							alt="User Avatar"
						/>
					</div>
				</div>

				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Profile</h3>
					</div>
					<!-- /.card-header -->
					<div class="card-body">
						<form class="form-horizontal">
							<div class="form-group row">
								<label for="inputName" class="col-sm-2 col-form-label"
									>Name</label
								>
								<div class="col-sm-10">
									<input
										v-model="form.name"
										type="text"
										name="name"
										placeholder="Input your name here."
										class="form-control"
										:class="{ 'is-invalid': form.errors.has('name') }"
									/>
									<has-error :form="form" field="name"></has-error>
								</div>
							</div>

							<div class="form-group row">
								<label for="inputName" class="col-sm-2 col-form-label"
									>Job Title</label
								>
								<div class="col-sm-10">
									<input
										v-model="form.job_title"
										type="text"
										name="job_title"
										placeholder="Input your job title here."
										class="form-control"
										:class="{ 'is-invalid': form.errors.has('job_title') }"
									/>
									<has-error :form="form" field="job_title"></has-error>
								</div>
							</div>

							<div class="form-group row">
								<label for="inputPhone" class="col-sm-2 col-form-label"
									>Phone</label
								>
								<div class="col-sm-10">
									<input
										v-model="form.phone"
										type="tel"
										name="phone"
										placeholder="08123456789"
										class="form-control"
										:class="{ 'is-invalid': form.errors.has('phone') }"
									/>
									<has-error :form="form" field="phone"></has-error>
								</div>
							</div>

							<div class="form-group row">
								<label for="inputEmail" class="col-sm-2 col-form-label"
									>Email</label
								>
								<div class="col-sm-10">
									<input
										v-model="form.email"
										type="email"
										name="email"
										placeholder="ex: email@abc.co"
										class="form-control"
										:class="{ 'is-invalid': form.errors.has('email') }"
									/>
									<has-error :form="form" field="email"></has-error>
								</div>
							</div>

							<div class="form-group row">
								<label for="inputPhoto" class="col-sm-2 col-form-label"
									>Photo</label
								>
								<div class="col-sm-10">
									<input
										type="file"
										name="photo"
										@change="updateProfile"
										class="form-control"
										:class="{ 'is-invalid': form.errors.has('photo') }"
									/>
									<has-error :form="form" field="photo"></has-error>
								</div>
							</div>

							<div class="form-group row">
								<label for="inputPassword" class="col-sm-2 col-form-label"
									>New Password</label
								>
								<div class="col-sm-10">
									<input
										v-model="form.password"
										type="password"
										name="password"
										placeholder="Allow empty password."
										class="form-control"
										:class="{ 'is-invalid': form.errors.has('password') }"
										autocomplete="new-password"
									/>
									<has-error :form="form" field="password"></has-error>
								</div>
							</div>

							<div class="form-group row">
								<label
									for="inputPasswordConfirmation"
									class="col-sm-2 col-form-label"
									>New Password confirm</label
								>
								<div class="col-sm-10">
									<input
										v-model="form.password_confirmation"
										type="password"
										name="password_confirmation"
										placeholder="Allow empty password confirm."
										class="form-control"
										:class="{ 'is-invalid': form.errors.has('password') }"
										autocomplete="new-password"
									/>
									<has-error
										:form="form"
										field="password_confirmation"
									></has-error>
								</div>
							</div>

							<div class="form-group row">
								<div class="offset-sm-2 col-sm-10">
									<button
										type="submit"
										@click.prevent="updateInfo"
										class="btn btn-success"
									>
										<b>Update Profile</b>
									</button>
									<button
										@click.prevent="showModalAddress"
										class="btn btn-outline-primary"
									>
										<b>Change Address</b>
									</button>
									<button
										@click.prevent="showModalRekening"
										class="btn btn-outline-danger"
									>
										<b>Change Rekening</b>
									</button>
								</div>
							</div>
						</form>
					</div>
					<!-- /.card-body -->
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { required, minLength, maxLength } from "vuelidate/lib/validators";
export default {
	data() {
		return {
			submitted: false,
			endpoint_address: "/api/address",
			backgroundUrl: "",
			form: new Form({
				id: "",
				password: "",
				password_confirmation: "",
				name: "",
				email: "",
				phone: "",
				job_title: "",
				photo: "",
				// id_user_type: 2
			}),
			formAddress: new Form({
				id: "",
				name: "",
				province_id: "",
				city_id: "",
				district_id: "",
			}),
			province: { id: null, name: "" },
			data_province: [],
			city: { id: null, name: "" },
			data_city: [],
			district: { id: null, name: "" },
			data_district: [],

			formAdminRekening: new Form({
				rekenings: [
					{
						id: null,
						rekening: "", // BCA, BRI , dll
						number: "",
					},
				],
			}),
		};
	},
	validations: {
		formAddress: {
			province_id: {
				required,
			},
			city_id: {
				required,
			},
			district_id: {
				required,
			},
			name: {
				required,
				minLength: minLength(10),
				maxLength: maxLength(150),
			},
		},
	},
	mounted() {
		this.fetchData();
	},
	methods: {
		addInputRekening() {
			this.formAdminRekening.rekenings.push({
				id: null,
				rekening: "",
				number: "",
			});
		},
		removeInputRekening(idx) {
			if (this.formAdminRekening.rekenings.length == 1) {
				Swal.fire(
					"Failed delete data rekening !",
					"Minimum has 1 data.",
					"warning"
				);
			} else {
				this.formAdminRekening.rekenings.splice(idx, 1);
			}
		},
		async fetchData() {
			this.backgroundUrl = process.env.MIX_APP_URL + "/img/user-cover.png";
			// axios.get("/api/profile").then(({ data }) => this.form.fill(data));

			const self = this;
			axios
				.get("/api/profile")
				.then(({ data }) => {
					this.form.fill(data);
					this.formAddress.fill(data.relationships.address);

					if (data.relationships.rekenings.length > 0) {
						this.formAdminRekening.rekenings = data.relationships.rekenings;
					}
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});

			// get exist data area-dropdown
			axios
				.get("/api/list-province")
				.then(({ data }) => {
					if (data.success) {
						self.data_province = data.data;
						self.province = _.find(self.data_province, function (obj) {
							return obj.id == self.formAddress.province_id;
						});

						if (!self.province) {
							self.province = { id: "", name: "" };
						} else {
							self.getDataCity();
							self.getDataDistrict();
						}
					} else {
						Swal.fire("Failed !", data.message, "error");
					}
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});
		},
		showModalAddress() {
			$("#modalAddress").modal("show");
		},
		showModalRekening() {
			$("#modalRekening").modal("show");
		},
		async updateInfo() {
			this.$Progress.start();

			if (this.form.password == "") {
				this.form.password == undefined;
			}

			await this.form
				.put(process.env.MIX_APP_URL + "/api/profile")
				.then(() => {
					Toast.fire({
						icon: "success",
						title: "Success update profile",
					});
					this.$Progress.finish();
				})
				.catch(() => {
					Toast.fire({
						icon: "error",
						title: "Failed update profile",
					});
					this.$Progress.fail();
				});
		},
		updateProfile(e) {
			let file = e.target.files[0];
			let reader = new FileReader();

			if (file["size"] < 2111775) {
				reader.onloadend = (file) => {
					this.form.photo = reader.result;
				};

				reader.readAsDataURL(file);
			} else {
				Swal.fire("Oops...!", "You are uploading a large file.", "error");
			}
		},
		getProfilePhoto() {
			let photo = "";
			if (this.form.photo) {
				photo =
					this.form.photo.length >= 50
						? this.form.photo
						: process.env.MIX_APP_URL + "/img/profile/" + this.form.photo;
			}
			return photo;
		},
		async updateAddress(id) {
			const self = this;
			this.submitted = true;
			this.$v.$touch();
			if (this.$v.$error) {
				return;
			} else {
				await this.formAddress
					.put("/api/address/" + this.formAddress.id)
					.then(({ data }) => {
						if (data.success) {
							Swal.fire("Success !", data.message, "success");
							self.fetchData();
						} else {
							Swal.fire("Failed !", data.message, "error");
						}
						$("#modalAddress").modal("hide");
					})
					.catch((error) => {
						this.showErrorMessage(error);
					});
			}
		},
		async updateRekening() {
			// input harus diisi semua
			let isValid = true;

			this.formAdminRekening.rekenings.forEach((object) => {
				if (object.rekening == "" || object.number == "") {
					isValid = false;
				}
			});

			if (!isValid || this.formAdminRekening.rekenings.length == 0) {
				Swal.fire("Failed !", "Data rekening harus diisi", "error");
				return;
			}

			await axios
				.put("/api/admin-rekening/" + this.form.id, this.formAdminRekening)
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
		},
		async getDataCity() {
			const self = this;
			await axios
				.get("/api/list-city/" + self.formAddress.province_id)
				.then(({ data }) => {
					if (data.success) {
						self.data_city = data.data;
						self.city = _.find(self.data_city, function (obj) {
							return obj.id == self.formAddress.city_id;
						});

						if (!self.city) {
							self.city = { id: "", name: "" };
						}
					} else {
						Swal.fire("Failed !", data.message, "error");
					}
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});
		},
		async getDataDistrict() {
			const self = this;
			await axios
				.get("/api/list-district/" + self.formAddress.city_id)
				.then(({ data }) => {
					if (data.success) {
						self.data_district = data.data;
						self.district = _.find(self.data_district, function (obj) {
							return obj.id == self.formAddress.district_id;
						});

						if (!self.district) {
							self.district = { id: "", name: "" };
						}
					} else {
						Swal.fire("Failed !", data.message, "error");
					}
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});
		},
		async onSelectProvince(option) {
			const self = this;
			self.formAddress.province_id = option.id;

			// clear city & district
			self.city = { id: "", name: "" };
			self.data_city = [];
			self.formAddress.city_id = "";

			self.district = { id: "", name: "" };
			self.data_district = [];
			self.formAddress.district_id = "";

			// select city
			axios
				.get("/api/list-city/" + option.id)
				.then(({ data }) => {
					if (data.success) {
						self.data_city = data.data;
					} else {
						Swal.fire("Failed !", data.message, "error");
					}
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});
		},
		async onSelectCity(option) {
			const self = this;
			self.formAddress.city_id = option.id;

			// clear district
			self.district = { id: "", name: "" };
			self.data_district = [];
			self.formAddress.district_id = "";

			// select district
			axios
				.get("/api/list-district/" + option.id)
				.then(({ data }) => {
					if (data.success) {
						self.data_district = data.data;
					} else {
						Swal.fire("Failed !", data.message, "error");
					}
				})
				.catch((error) => {
					this.showErrorMessage(error);
				});
		},
		async onSelectDistrict(option) {
			const self = this;
			self.formAddress.district_id = option.id;
		},
	},
};
</script>
