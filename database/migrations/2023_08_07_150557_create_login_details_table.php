<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->date('date')->nullable();
            $table->time('login_time')->nullable();
            $table->time('logout_time')->nullable();
            $table->string('latitute')->nullable();
            $table->string('longitute')->nullable();
            $table->tinyInteger('shift')->nullable();
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
        Schema::dropIfExists('login_details');
    }
}
