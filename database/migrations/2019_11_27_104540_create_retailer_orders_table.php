<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetailerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retailer_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('company_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('quantity')->unsigned()->default(1);
            $table->string('unit_price');
            $table->string('selling_price');
            $table->string('order_amount');
            $table->integer('created_by');
            $table->boolean('is_confirmed')->default(false);
            $table->softDeletes();
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
        Schema::dropIfExists('retailer_orders');
    }
}
