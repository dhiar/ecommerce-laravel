<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('admins', function (Blueprint $table) {
				$table->id();
				$table->string('name');
				$table->unsignedBigInteger('id_user_type');
				$table->foreign('id_user_type')
						->references('id')
						->on('user_types');

				$table->unsignedBigInteger('id_address');
				$table->foreign('id_address')
                ->references('id')
                ->on('address');

				$table->string('email')->unique();
				$table->string('job_title')->default("Manage & monitoring app.");
				$table->string('password');
				$table->string('phone',15);
				$table->string('photo', 100)->nullable()->default('https://www.travelcontinuously.com/wp-content/uploads/2018/04/empty-avatar.png');
				$table->rememberToken();
				$table->timestamps();
				$table->softDeletes();
			});
		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down()
		{
				Schema::dropIfExists('admins');
		}
}
