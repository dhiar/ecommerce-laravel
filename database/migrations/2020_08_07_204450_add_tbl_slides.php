<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTblSlides extends Migration
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
            
            $table->unsignedBigInteger('id_menu');
            $table->foreign('id_menu')
                ->references('id')
                ->on('menus');

            $table->string('title', 30)->unique();
            $table->string('url', 40)->unique();
            $table->string('icon', 30)->unique();
            $table->boolean('active')->default(true);
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
