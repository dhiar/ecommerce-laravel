<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblBases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('shop_name', 30)->nullable();
            $table->integer('province_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('logo', 30)->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('bases');
    }
}
