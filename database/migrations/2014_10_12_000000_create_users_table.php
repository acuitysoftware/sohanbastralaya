<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('gender',['M','F'])->nullable();
            $table->date('dob')->nullable();
            $table->enum('type',['A','S'])->nullable();
            $table->date('reg_date')->nullable();
            $table->datetime('last_login')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('parent_menu')->nullable();
            $table->string('chile_menu')->nullable();
            $table->text('address')->nullable();
            $table->string('latitute')->nullable();
            $table->string('longitute')->nullable();
            $table->enum('status',['1','0'])->default('0');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
