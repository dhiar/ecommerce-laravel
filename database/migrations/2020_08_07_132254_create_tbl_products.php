<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblProducts extends Migration
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

            $table->unsignedBigInteger('id_product_category');
            $table->foreign('id_product_category')
                ->references('id')
                ->on('product_categories');

            // Untuk ditampilkan no HP setiap product
            $table->unsignedBigInteger('id_admin');
            $table->foreign('id_admin')
                ->references('id')
                ->on('admins');

            $table->string('name', 100)->unique();
            $table->string('image', 100)->unique();
            $table->integer('price')->default(0);
            $table->integer('weight')->default(0);
            $table->integer('stock')->default(0);
            $table->enum('condition', ['New', 'Second'])->default('New');
            $table->text('description');
            $table->enum('is_published', ['0', '1'])->default('1');
            $table->string('slug', 100)->unique();
            $table->integer('transaction')->default(0);
            $table->integer('promo_price')->default(0);
            $table->integer('viewer')->default(0);
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
            $table->dropForeign(['id_product_category']);
            $table->dropColumn('id_product_category');
        });
        Schema::dropIfExists('products');
    }
}
