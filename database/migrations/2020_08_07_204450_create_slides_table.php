<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 30)->unique();
            $table->string('url', 50)->nullable();
            $table->text('description');
            $table->string('image', 150);
            $table->enum('active', ['0', '1'])->default('1');

            $table->unsignedBigInteger('id_product');
            $table->foreign('id_product')
                ->references('id')
                ->on('products');
            
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
        Schema::table('slides', function (Blueprint $table) {
            $table->dropForeign(['id_menu']);
            $table->dropColumn('id_menu');
        });
        Schema::dropIfExists('slides');
    }
}
