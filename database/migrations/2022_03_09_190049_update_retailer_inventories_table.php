<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRetailerInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('retailer_inventories', function (Blueprint $table) {
            $table->dropColumn('cost_price');
            $table->dropColumn('is_in_stock');
            $table->decimal('selling_price')->nullable(true)->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('retailer_inventories', function (Blueprint $table) {
            $table->string('cost_price');
            $table->string('selling_price');
            $table->boolean('is_in_stock')->default(false);
        });
    }
}
