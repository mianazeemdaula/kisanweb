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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crop_type_id');
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('packing_id');
            $table->string('note');
            $table->float('demand');
            $table->unsignedInteger('qty');
            $table->string('weight_scale')->default('mann');
            $table->point('location');
            $table->string('status')->default('open');
            $table->unsignedBigInteger('accept_bid_id')->nullable();
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
        Schema::dropIfExists('deals');
    }
};
