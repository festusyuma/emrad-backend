<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('inventory_id')->unsigned();
            $table->string('stock_balance');
            $table->string('new_stock_balance');
            $table->timestamps();

            $table->foreign('inventory_id')->references('id')->on('retailer_inventories')
                    ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_balances');
    }
}
