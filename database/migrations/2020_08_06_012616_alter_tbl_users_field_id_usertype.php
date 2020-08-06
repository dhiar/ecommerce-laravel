<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTblUsersFieldIdUsertype extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        // enum field gender
        // foreign key id_user_type
        // 

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user_type')->nullable(); // unsigned for foreign key.
            $table->foreign('id_user_type') // foreign key column name.
                ->references('id') // parent table primary key.
                ->on('user_types') // parent table name.
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_user_type']); // drop the foreign key.
            $table->dropColumn('id_user_type'); // drop the column
        });
    }
}
