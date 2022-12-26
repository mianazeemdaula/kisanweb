<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('mobile')->unique()->nullable();
            $table->string('cnic')->unique()->nullable();
            $table->string('image')->nullable();
            $table->string('password')->nullable();
            $table->char('type',10)->default('user');
            $table->string('fcm_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->timestamp('cnic_verified_at')->nullable();
            $table->string('firebase_uid')->nullable();
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
};
