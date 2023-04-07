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
        Schema::create('commission_shop_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id');
            // $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('crop_type_id');
            $table->decimal('min_price',12,2);
            $table->decimal('max_price',12,2);
            $table->date('rate_date');
            $table->timestamps();
            $table->foreign('commission_shop_id')->references('id')->on('commission_shops')->onDelete('cascade');
            $table->foreign('crop_type_id')->references('id')->on('crop_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commission_shop_rates');
    }
};
