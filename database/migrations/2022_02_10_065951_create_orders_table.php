<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('amount');
            $table->boolean('payment_confirmed')->default(false);
            $table->string('reference')->nullable(true)->default(null);
            $table->string('payment_method');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('card_id')->nullable(true)->default(null);
            $table->foreign('card_id')->references('id')->on('cards');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('models_orders');
    }
}
