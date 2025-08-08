<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserOrdersAddressFields extends Migration
{
    public function up()
    {
        Schema::table('user_orders', function (Blueprint $table) {
            // Remove campos antigos
            $table->dropColumn([
                'billing_address',
                'shipping_address'
            ]);

            // Novos campos de endereço - billing
            $table->string('billing_zip')->nullable()->after('billing_number');
            $table->string('billing_street')->nullable()->after('billing_zip');
            $table->string('billing_number_home')->nullable()->after('billing_street');
            $table->string('billing_neighborhood')->nullable()->after('billing_number_home');
            $table->string('billing_reference')->nullable()->after('billing_neighborhood');

            // Novos campos de endereço - shipping
            $table->string('shipping_zip')->nullable()->after('shipping_number');
            $table->string('shipping_street')->nullable()->after('shipping_zip');
            $table->string('shipping_number_address')->nullable()->after('shipping_street');
            $table->string('shipping_neighborhood')->nullable()->after('shipping_number_address');
            $table->string('shipping_reference')->nullable()->after('shipping_neighborhood');

            // Campos de frete
            $table->string('shipping_service')->nullable()->after('shipping_reference'); // tipo do frete + prazo
            $table->decimal('shipping_price', 8, 2)->nullable()->after('shipping_service'); // valor do frete
        });
    }

    public function down()
    {
        Schema::table('user_orders', function (Blueprint $table) {
            // Reverte campos de frete
            $table->dropColumn([
                'shipping_service',
                'shipping_price',

                // campos de endereço novos
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
            ]);

            // Recria os campos antigos de endereço
            $table->string('billing_address')->nullable()->after('billing_lname');
            $table->string('shipping_address')->nullable()->after('shipping_lname');
        });
    }
}
