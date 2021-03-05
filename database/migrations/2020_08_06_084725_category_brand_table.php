<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CategoryBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_brand', function(Blueprint $table)
		{
			$table->id();
			$table->unsignedBigInteger('id_category')->index();
            $table->unsignedBigInteger('id_brand')->index();
            $table->foreign('id_category')->references('id')->on('product_categories');
			$table->foreign('id_brand')->references('id')->on('product_brands');
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
		Schema::dropIfExists('category_brand');
	}
}
