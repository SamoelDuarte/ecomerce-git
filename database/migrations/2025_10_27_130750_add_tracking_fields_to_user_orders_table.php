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
        Schema::table('user_orders', function (Blueprint $table) {
            $table->string('tracking_code')->nullable()->after('order_status');
            $table->string('tracking_carrier')->nullable()->after('tracking_code');
            $table->text('tracking_url')->nullable()->after('tracking_carrier');
            $table->timestamp('tracking_updated_at')->nullable()->after('tracking_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_orders', function (Blueprint $table) {
            $table->dropColumn(['tracking_code', 'tracking_carrier', 'tracking_url', 'tracking_updated_at']);
        });
    }
};
