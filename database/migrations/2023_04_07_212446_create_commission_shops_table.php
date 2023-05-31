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
        Schema::create('commission_shops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('city_id');
            $table->string('name');
            $table->text('about')->nullable();
            $table->string('address');
            $table->point('location');
            $table->string('logo');
            $table->string('banner');
            $table->string('shop_number')->nullable();
            $table->boolean('active')->default(true);
            $table->json('social_links')->nullable();
            $table->unsignedMediumInteger('rating')->default(0);
            $table->unsignedMediumInteger('rating_count')->default(0);
            $table->unsignedSmallInteger('ad_index')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->spatialIndex('location');
            // $table->index(['city_id']);
            // $table->index(['active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commission_shops');
    }
};
