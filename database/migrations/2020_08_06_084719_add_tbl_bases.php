<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTblBases extends Migration
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
            $table->string('shop_name', 30);
            $table->string('province', 25);
            $table->string('city', 25);
            $table->text('address');
            $table->string('phone', 15);
            $table->string('logo', 30);
            $table->text('description');
            $table->string('facebook', 100);
            $table->string('twitter', 100);
            $table->string('linkedin', 100);
            $table->string('youtube', 100);
            $table->string('rajaongkir',150)->default('0b639a34e05261cd03dbd3ae40c4b197');
            $table->string('midtrans',150)->default('SB-Mid-server-zQ2Q-hnATMAf98PmTDHow61y');
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
