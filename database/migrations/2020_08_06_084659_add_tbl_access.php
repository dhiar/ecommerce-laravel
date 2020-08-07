<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTblUserAccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_access', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('id_user_type')->nullable();
            $table->foreign('id_user_type')
                ->references('id')
                ->on('user_types')
                ->onDelete('set null');

            $table->unsignedBigInteger('id_menu')->nullable();
            $table->foreign('id_menu')
                ->references('id')
                ->on('menus')
                ->onDelete('set null');
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
        Schema::table('user_access', function (Blueprint $table) {
            $table->dropForeign(['id_user_type']);
            $table->dropColumn('id_user_type');

            $table->dropForeign(['id_menu']);
            $table->dropColumn('id_menu');
        });
        Schema::dropIfExists('user_access');
    }
}
