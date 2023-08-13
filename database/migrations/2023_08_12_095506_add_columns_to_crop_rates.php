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
            $table->float('min_price_last', 8, 2)->default(0)->after('max_price');
            $table->float('max_price_last', 8, 2)->default(0)->after('min_price_last');
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
            $table->dropColumn(['min_price_last','max_price_last']);
        });
    }
};
