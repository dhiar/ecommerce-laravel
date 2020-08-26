<template>
	<div class="container">
		<div class="row mt-5">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Users</h3>

						<div class="card-tools">
							<button class="btn btn-success" data-toggle="modal" data-target="#addNewUser">
								Add New <i class="fas fa-user-plus fa-fw"></i>
							</button>
						</div>
					</div>
					<!-- /.card-header -->
					<div class="card-body table-responsive p-0">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Name</th>
									<th>User Type</th>
									<th>Phone</th>
									<th>Email</th>
									<th>Address</th>
									<th>Date</th>
									<th>Modify</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="user in users" :key="user.id">
									<td>{{ user.name | upText }}</td>
									<td>{{ user.id_user_type }}</td>
									<td>{{ user.phone }}</td>
									<td>{{ user.email }}</td>
									<td>{{ user.id_address }}</td>
									<td>{{ user.created_at | myDate }}</td>
									<td>
										<a href="#">
											<i class="fa fa-edit blue"></i>
											/
											<i class="fa fa-trash red"></i>
										</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="addNewUser" tabindex="-1" aria-labelledby="addNewLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="addNewLabel">Add New (Admin / Owner)</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<form @submit.prevent="createUser">
						<div class="modal-body">
							<div class="form-group">
								<label>Username</label>
								<input v-model="form.name" type="text" name="name" placeholder="Input your name here."
									class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
								<has-error :form="form" field="name"></has-error>
							</div>
							<div class="form-group">
								<label>Gender</label>
								<select name="gender" v-model="form.gender" id="gender"
									class="form-control" :class="{ 'is-invalid': form.errors.has('gender') }">
									<option value="L" selected>Laki-laki</option>
									<option value="P">Perempuan</option>
								</select>
								<has-error :form="form" field="gender"></has-error>
							</div>
							<div class="form-group">
								<label>Phone</label>
								<input v-model="form.phone" type="tel" name="phone" placeholder="08123456789"
									class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }">
								<has-error :form="form" field="phone"></has-error>
							</div>
							<div class="form-group">
								<label>Email</label>
								<input v-model="form.email" type="email" name="email" placeholder="ex: email@abc.co"
									class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
								<has-error :form="form" field="email"></has-error>
							</div>
							<div class="form-group">
								<label>Address</label>
									<textarea v-model="form.address" id="address" name="address"
										class="form-control" :class="{ 'is-invalid': form.errors.has('address') }"></textarea>
								<has-error :form="form" field="address"></has-error>
							</div>
							<div class="form-group">
								<label>Password</label>
								<input v-model="form.password"
									type="password"
									name="password"
									class="form-control" :class="{ 'is-invalid': form.errors.has('password') }"
									id="password"
									required autocomplete="new-password"
								>
								<has-error :form="form" field="password"></has-error>
							</div>
							<div class="form-group">
								<label>Password Confirmation</label>
								<input v-model="form.password_confirmation"
									type="password"
									name="password_confirmation"
									class="form-control"
									:class="{ 'is-invalid': form.errors.has('password_confirmation') }"
									id="password-confirm"
									required autocomplete="new-password"
								>
								<has-error :form="form" field="password_confirmation"></has-error>
							</div>
							<div class="form-group">
								<label>User Type</label>
								<select name="user_type" v-model="form.user_type" id="user_type"
									class="form-control" :class="{ 'is-invalid': form.errors.has('user_type') }">
									<option value="1">Admin</option>
									<option value="2" selected>Pemilik</option>
								</select>
								<has-error :form="form" field="user_type"></has-error>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
							<button type="submit" :disabled="form.busy" class="btn btn-primary">Save changes</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				users: [],
				form: new Form({
					name: '',
					gender: 'L',
					phone: '',
					email: '',
					address: '',
					password: '',
					password_confirmation: '',
					user_type: 2
				})
			}
		},
		methods : {
			loadUsers() {
				axios.get('api/user').then( ({data}) => (
					this.users = data.data
				))
			},
			async createUser() {
				//  start the progress bar
				this.$Progress.start()

				await this.form.post('api/user')
				.then(() => {
					Fire.$emit('AfterCreate')
					$("#addNewUser").modal("hide")

					Toast.fire({
						icon: 'success',
						title: 'Signed in successfully'
					})

					this.loadUsers();
					this.$Progress.finish();
				})
				.catch(() => {
					this.$Progress.fail();
					Toast.fire({
       			icon: 'error',
       			title: 'User not Created'
 					});
				})

				
			}
		},
		created() {
			this.loadUsers()
			Fire.$on('AfterCreate', () => {
				this.loadUsers()
			})
			// setInterval(() => this.loadUsers(), 3000)
		}
	}
</script>
