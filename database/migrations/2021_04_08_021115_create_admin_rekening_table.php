<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminRekeningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_rekening', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_admin');
            $table->foreign('id_admin')->references('id')->on('admins');
            $table->unsignedBigInteger('id_rekening');
            $table->foreign('id_rekening')->references('id')->on('rekenings');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_rekening', function (Blueprint $table) {
            $table->dropForeign(['id_admin']);
            $table->dropColumn('id_admin');
            $table->dropForeign(['id_rekening']);
            $table->dropColumn('id_rekening');
        });
        Schema::dropIfExists('admin_rekening');
    }
}
