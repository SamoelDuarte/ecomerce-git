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
        Schema::table('digital_product_codes', function (Blueprint $table) {
            $table->dropColumn(['name', 'price']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('digital_product_codes', function (Blueprint $table) {
            $table->string('name')->after('user_item_id'); // Restaura a coluna name
            $table->decimal('price', 8, 2)->nullable()->after('used_at'); // Restaura a coluna price
        });
    }
};
