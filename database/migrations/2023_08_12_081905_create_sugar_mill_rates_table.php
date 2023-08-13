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
        Schema::create('sugar_mill_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('sugar_mill_id');
            $table->unsignedSmallInteger('min_price');
            $table->unsignedSmallInteger('max_price');
            $table->unsignedSmallInteger('min_price_last');
            $table->unsignedSmallInteger('max_price_last');
            $table->timestamps();
            $table->foreign('sugar_mill_id')->references('id')->on('sugar_mills')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sugar_mill_rates');
    }
};
