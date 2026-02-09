<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDueAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_due_amounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->date('order_date')->nullable();
            $table->date('date')->nullable();
            $table->decimal('due_amount', 10,2)->nullable();
            $table->decimal('collected_amount', 10,2)->nullable();
            $table->decimal('total_amount', 10,2)->nullable();
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
        Schema::dropIfExists('order_due_amounts');
    }
}
