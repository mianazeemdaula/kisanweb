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
        Schema::create('category_deal_bids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_deal_id');
            $table->unsignedBigInteger('buyer_id');
            $table->decimal('bid_price',12,2);
            $table->foreign('buyer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_deal_id')->references('id')->on('category_deals')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_deal_bids');
    }
};
