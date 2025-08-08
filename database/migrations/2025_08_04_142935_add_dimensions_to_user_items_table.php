<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDimensionsToUserItemsTable extends Migration
{
    public function up()
    {
        Schema::table('user_items', function (Blueprint $table) {
            $table->decimal('weight', 8, 3)->nullable()->after('currency_id'); // peso em kg
            $table->decimal('length', 8, 2)->nullable()->after('weight');     // comprimento em cm
            $table->decimal('height', 8, 2)->nullable()->after('length');     // altura em cm
            $table->decimal('width', 8, 2)->nullable()->after('height');      // largura em cm
        });
    }

    public function down()
    {
        Schema::table('user_items', function (Blueprint $table) {
            $table->dropColumn(['weight', 'length', 'height', 'width']);
        });
    }
}
