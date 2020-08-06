<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTblSubMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_menu')->nullable();
            $table->foreign('id_menu')
                ->references('id')
                ->on('menus')
                ->onDelete('set null');

            $table->string('title', 20)->nullable(false);
            $table->string('url', 50)->nullable(false);
            $table->string('icon', 30)->nullable(false);
            $table->string('active')->default(true);
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
        Schema::table('sub_menus', function (Blueprint $table) {
            $table->dropForeign(['id_menu']);
            $table->dropColumn('id_menu');
        });
        
        Schema::dropIfExists('sub_menus');
    }
}
