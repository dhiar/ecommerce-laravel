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

            $table->unsignedBigInteger('id_product_type')->nullable();
            $table->foreign('id_product_type')
                ->references('id')
                ->on('product_types')
                ->onDelete('set null');

            $table->unsignedBigInteger('id_product_brand')->nullable();
            $table->foreign('id_product_brand')
                ->references('id')
                ->on('product_brands')
                ->onDelete('set null');

            $table->string('name', 15)->nullable(false);
            $table->integer('price')->default(0);
            $table->integer('stock')->default(0);
            $table->tinyInteger('discount')->default(0);
            $table->text('description');
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
