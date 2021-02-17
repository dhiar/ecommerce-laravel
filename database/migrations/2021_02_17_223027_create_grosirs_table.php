<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrosirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grosirs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_product');
            $table->foreign('id_product')
                ->references('id')
                ->on('products');
            $table->integer('min');
            $table->integer('price');
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
        Schema::table('grosirs', function (Blueprint $table) {
            $table->dropForeign(['id_product']);
            $table->dropColumn('id_product');
        });
        Schema::dropIfExists('grosirs');
    }
}
