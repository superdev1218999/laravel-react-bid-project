<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidLineItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bid_line_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ref_id')->unique();
            $table->string('title');
            $table->bigInteger('cost');
            $table->bigInteger('price');
            $table->string('config');
            $table
                ->bigInteger('actual_cost')
                ->nullable()
                ->default(null);
            $table
                ->bigInteger('actual_cost_confidence_factor')
                ->nullable()
                ->default(null);
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
        Schema::dropIfExists('bid_line_items');
    }
}
