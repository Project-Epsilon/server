<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddColumnsToTransfers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfers', function (Blueprint $table){
            $table->dateTime('received_at')->nullable();
        });
        Schema::table('transfers', function (Blueprint $table){
            $table->string('receiver_email')->nullable();
        });
        Schema::table('transfers', function (Blueprint $table){
            $table->string('receiver_phone_number')->nullable();
        });
        Schema::table('transfers', function (Blueprint $table){
            $table->string('message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('transfers', function (Blueprint $table){
            $table->dropColumn('received_at');
        });
        Schema::table('transfers', function (Blueprint $table){
            $table->dropColumn('receiver_email');
        });
        Schema::table('transfers', function (Blueprint $table){
            $table->dropColumn('receiver_phone_number');
        });
        Schema::table('transfers', function (Blueprint $table){
            $table->dropColumn('message');
        });
    }
}
