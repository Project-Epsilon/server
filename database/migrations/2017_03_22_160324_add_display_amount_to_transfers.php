<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDisplayAmountToTransfers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfers', function (Blueprint $table){
            $table->string('amount_display');
        });
        Schema::table('transfers', function (Blueprint $table){
            $table->string('sender');
        });
        Schema::table('transfers', function (Blueprint $table){
            $table->string('receiver');
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
            $table->dropColumn('amount_display');
        });
        Schema::table('transfers', function (Blueprint $table){
            $table->dropColumn('sender');
        });
        Schema::table('transfers', function (Blueprint $table){
            $table->dropColumn('receiver');
        });
    }
}
