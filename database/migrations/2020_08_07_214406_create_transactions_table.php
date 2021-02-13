<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
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
            $table->double('shipping_charges');
            $table->integer('total_weight');
            $table->double('total_price');

            $table->unsignedBigInteger('id_address');
            $table->foreign('id_address')
                ->references('id')
                ->on('address');

            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')
                ->references('id')
                ->on('users');

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
            $table->dropForeign(['id_address']);
            $table->dropColumn('id_address');

            $table->dropForeign(['id_user']);
            $table->dropColumn('id_user');

            $table->dropForeign(['id_delivery_status']);
            $table->dropColumn('id_delivery_status');
        });
        Schema::dropIfExists('transactions');
    }
}
