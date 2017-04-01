<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sender_wallet_id')->unsigned();
            $table->integer('receiver_wallet_id')->unsigned()->nullable();
            $table->string('sender');
            $table->string('receiver');

            $table->string('amount');
            $table->string('amount_display');

            $table->string('receiver_email')->nullable();
            $table->string('receiver_phone_number')->nullable();
            $table->string('message')->nullable();

            $table->string('status');
            $table->string('token');
            $table->dateTime('received_at')->nullable();

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
        Schema::dropIfExists('transfers');
    }
}
