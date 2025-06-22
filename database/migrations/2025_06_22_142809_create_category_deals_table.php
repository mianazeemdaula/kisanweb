<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('category_deals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_category_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('accept_bid_id')->nullable();
            $table->string('note');
            $table->decimal('demand',12,2);
            $table->unsignedInteger('qty');
            $table->point('location');
            $table->string('address');
            $table->string('status')->default('open');
            $table->boolean('is_sell')->default(false);
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_deals');
    }
};
