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
        Schema::table('crop_rates', function (Blueprint $table) {
            $table->renameColumn('price','min_price');
            $table->decimal('max_price',12,2);
            $table->date('rate_date');
            $table->unsignedBigInteger('user_id');
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
        Schema::table('crop_rates', function (Blueprint $table) {
            $table->dropForeign(['user_id']); 
            $table->dropColumn(['max_price','rate_date','user_id']);
            $table->renameColumn('min_price','price');
        });
    }
};
