<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $oldColumnsToRemove = [
                'address',
                'shipping_address',
                'billing_address',
                'shipping_country',
                'billing_country',
            ];
            foreach ($oldColumnsToRemove as $col) {
                if (Schema::hasColumn('customers', $col)) {
                    $table->dropColumn($col);
                }
            }

            $newColumnsToAdd = [
                'billing_zip',
                'billing_street',
                'billing_number_home',
                'billing_neighborhood',
                'billing_reference',

                'shipping_zip',
                'shipping_street',
                'shipping_number_address',
                'shipping_neighborhood',
                'shipping_reference',
            ];
            foreach ($newColumnsToAdd as $col) {
                if (!Schema::hasColumn('customers', $col)) {
                    $table->string($col)->nullable();
                }
            }
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $oldColumnsToRemove = [
                'address',
                'shipping_address',
                'billing_address',
                'shipping_country',
                'billing_country',
            ];
            foreach ($oldColumnsToRemove as $col) {
                if (!Schema::hasColumn('customers', $col)) {
                    $table->string($col)->nullable();
                }
            }

            $newColumnsToAdd = [
                'billing_zip',
                'billing_street',
                'billing_number_home',
                'billing_neighborhood',
                'billing_reference',

                'shipping_zip',
                'shipping_street',
                'shipping_number_address',
                'shipping_neighborhood',
                'shipping_reference',
            ];
            foreach ($newColumnsToAdd as $col) {
                if (Schema::hasColumn('customers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
