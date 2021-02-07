<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTblFooters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('footers', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('id_page');
            $table->foreign('id_page')
                ->references('id')
                ->on('pages');

            $table->unsignedBigInteger('id_navigation');
            $table->foreign('id_navigation')
                ->references('id')
                ->on('navigations');

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
        Schema::table('footers', function (Blueprint $table) {
            $table->dropForeign(['id_page']);
            $table->dropColumn('id_page');
            $table->dropForeign(['id_navigation']);
            $table->dropColumn('id_navigation');
        });
        Schema::dropIfExists('footers');
    }
}
