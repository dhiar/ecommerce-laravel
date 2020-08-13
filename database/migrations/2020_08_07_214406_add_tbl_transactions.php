<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTblTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice', 50);
            $table->string('email')->nullable();
            $table->integer('weight');

            $table->double('shipping_charges');
            $table->double('total');

            $table->text('alamat');
            $table->string('kabupaten', 25);
            $table->string('propinsi', 25);
            $table->string('kodepos', 8)->default('-');

            $table->unsignedBigInteger('id_delivery_status');
            $table->foreign('id_delivery_status')
                ->references('id')
                ->on('delivery_status');
            
            $table->text('token');
            $table->dateTime('token_created_at');
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
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['id_delivery_status']);
            $table->dropColumn('id_delivery_status');
        });
        Schema::dropIfExists('transactions');
    }
}
