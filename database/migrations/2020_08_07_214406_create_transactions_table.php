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
            $table->string('invoice', 50); // bila via WA isi dengan kode, wa_10digits
            $table->double('shipping_cost')->default(0);
            $table->integer('total_weight');
            $table->double('total_price');

            $table->unsignedBigInteger('id_address');
            $table->foreign('id_address')
                ->references('id')
                ->on('address');
            // bila menggunakan transaksi transfer, maka akan masuk ke pihak ke-3 (superadmin)
            $table->unsignedBigInteger('id_admin_owner')->nullable();
            $table->foreign('id_admin_owner')
                ->references('id')
                ->on('admins');

            $table->unsignedBigInteger('id_delivery_status');
            $table->foreign('id_delivery_status')
                ->references('id')
                ->on('delivery_status');
            
            $table->text('token')->nullable();
            $table->dateTime('token_created_at')->nullable();
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

            $table->dropForeign(['id_admin_owner']);
            $table->dropColumn('id_admin_owner');

            $table->dropForeign(['id_delivery_status']);
            $table->dropColumn('id_delivery_status');
        });
        Schema::dropIfExists('transactions');
    }
}
