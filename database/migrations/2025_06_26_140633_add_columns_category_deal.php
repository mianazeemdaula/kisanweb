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
        Schema::table('category_deals', function (Blueprint $table) {
            $table->unsignedBigInteger('packing_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('weight_type_id')->nullable()->after('packing_id');
            $table->foreign('packing_id')->references('id')->on('packings')->onDelete('cascade');
            $table->foreign('weight_type_id')->references('id')->on('weight_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_deals', function (Blueprint $table) {
            // drop foreign keys first
            $table->dropForeign(['packing_id']);
            $table->dropForeign(['weight_type_id']);
            $table->dropColumn(['packing_id', 'weight_type_id']);
        });
    }
};
