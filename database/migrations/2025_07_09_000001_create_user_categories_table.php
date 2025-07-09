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
        if (!Schema::hasTable('user_categories')) {
            Schema::create('user_categories', function (Blueprint $table) {
                $table->id();
                $table->string('unique_id')->index();
                $table->unsignedBigInteger('language_id');
                $table->string('name');
                $table->string('slug')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->integer('serial_number')->default(0);
                $table->timestamps();
                
                $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
                $table->index(['language_id', 'status']);
                $table->index(['unique_id', 'language_id']);
            });
        } else {
            // Verificar se as colunas necessárias existem
            Schema::table('user_categories', function (Blueprint $table) {
                if (!Schema::hasColumn('user_categories', 'unique_id')) {
                    $table->string('unique_id')->after('id')->index();
                }
                if (!Schema::hasColumn('user_categories', 'language_id')) {
                    $table->unsignedBigInteger('language_id')->after('unique_id');
                }
                if (!Schema::hasColumn('user_categories', 'slug')) {
                    $table->string('slug')->nullable()->after('name');
                }
                if (!Schema::hasColumn('user_categories', 'status')) {
                    $table->tinyInteger('status')->default(1)->after('slug');
                }
                if (!Schema::hasColumn('user_categories', 'serial_number')) {
                    $table->integer('serial_number')->default(0)->after('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_categories');
    }
};
