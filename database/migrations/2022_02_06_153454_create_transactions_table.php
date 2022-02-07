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
            $table->string('type');
            $table->double('amount');
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->boolean('verified')->default(false);
            $table->string('reference')->unique();
            $table->string('card_id')->nullable()->default(null);
            $table->foreign('card_id')->references('id')->on('cards');
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
        Schema::dropIfExists('transactions');
    }
}
