<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTblProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('id_product_type');
            $table->foreign('id_product_type')
                ->references('id')
                ->on('product_types');

            $table->unsignedBigInteger('id_product_brand');
            $table->foreign('id_product_brand')
                ->references('id')
                ->on('product_brands');

            $table->string('name', 15)->nullable(false);
            $table->integer('price')->default(0);
            $table->integer('stock')->default(0);
            $table->tinyInteger('discount')->default(0);
            $table->text('description');

            $table->string('photo1', 30);
            $table->string('photo2', 30);
            $table->string('photo3', 30);
            $table->string('photo4', 30);

            $table->string('slug', 150)->nullable(false);
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
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['id_product_type']);
            $table->dropColumn('id_product_type');
            $table->dropForeign(['id_product_brand']);
            $table->dropColumn('id_product_brand');
        });
        Schema::dropIfExists('products');
    }
}
