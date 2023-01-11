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
            $table->unsignedBigInteger('packing_id')->nullable();
            $table->unsignedBigInteger('weight_type_id');
            $table->string('note');
            $table->decimal('demand',12,2);
            $table->unsignedInteger('qty');
            $table->point('location');
            $table->string('address');
            $table->string('status')->default('open');
            $table->unsignedBigInteger('accept_bid_id')->nullable();
            $table->timestamps();
            $table->foreign('crop_type_id')->references('id')->on('crop_types')->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('packing_id')->references('id')->on('packings')->onDelete('cascade');
            $table->foreign('weight_type_id')->references('id')->on('weight_types')->onDelete('cascade');
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
