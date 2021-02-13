<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_transaction');
            $table->foreign('id_transaction')
                ->references('id')
                ->on('transactions');

            $table->unsignedBigInteger('id_product');
            $table->foreign('id_product')
                ->references('id')
                ->on('products');

            $table->integer('count');
            $table->double('subtotal_weight');
            $table->double('subtotal_price');
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
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropForeign(['id_transaction']);
            $table->dropColumn('id_transaction');
            $table->dropForeign(['id_product']);
            $table->dropColumn(['id_product']);
        });
        Schema::dropIfExists('transaction_details');
    }
}
