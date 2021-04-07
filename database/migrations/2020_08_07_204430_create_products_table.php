<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
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

            $table->unsignedBigInteger('id_category_brand');
            $table->foreign('id_category_brand')
                ->references('id')
                ->on('category_brand');

            // Untuk ditampilkan no HP setiap product
            $table->unsignedBigInteger('id_admin');
            $table->foreign('id_admin')
                ->references('id')
                ->on('admins');

            $table->string('name', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->string('image', 100);
            $table->integer('price')->default(0);
            $table->integer('weight')->default(0);
            $table->integer('stock')->default(0);
            $table->enum('condition', ['New', 'Second'])->default('New');
            $table->text('description');

            // 1 is publish, 0 is draft
            $table->enum('is_published', ['0', '1'])->default('1');

            // 0 tampilkan harga normal pada front-end
            $table->enum('is_promo', ['0', '1'])->default('0');
            
            $table->integer('transaction')->default(0);
            $table->integer('promo_price')->default(0);
            $table->integer('count_view')->default(0);
            $table->integer('count_like')->default(0);
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
            $table->dropForeign(['id_category_brand']);
            $table->dropColumn('id_category_brand');
            $table->dropForeign(['id_admin']);
            $table->dropColumn('id_admin');
        });
        Schema::dropIfExists('products');
    }
}
