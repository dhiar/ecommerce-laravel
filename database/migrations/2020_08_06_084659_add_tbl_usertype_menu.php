<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTblUsertypeMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usertype_menu', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('id_user_type');
            $table->foreign('id_user_type')
                ->references('id')
                ->on('user_types');

            $table->unsignedBigInteger('id_menu');
            $table->foreign('id_menu')
                ->references('id')
                ->on('menus');
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
        Schema::table('usertype_menu', function (Blueprint $table) {
            $table->dropForeign(['id_user_type']);
            $table->dropColumn('id_user_type');

            $table->dropForeign(['id_menu']);
            $table->dropColumn('id_menu');
        });
        Schema::dropIfExists('user_access');
    }
}
