<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderStatusIdToUserOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('user_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('order_status_id')->nullable()->after('order_status');
            $table->foreign('order_status_id')->references('id')->on('order_statuses');
        });
    }

    public function down()
    {
        Schema::table('user_orders', function (Blueprint $table) {
            $table->dropForeign(['order_status_id']);
            $table->dropColumn('order_status_id');
        });
    }
}
