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
        Schema::create('tags_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tag_id'); // ID da tag
            $table->unsignedBigInteger('user_item_id'); // ID do produto (user_items)
            $table->timestamps();

            // Chaves estrangeiras
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('user_item_id')->references('id')->on('user_items')->onDelete('cascade');
            
            // Índice único para evitar duplicatas
            $table->unique(['tag_id', 'user_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags_product');
    }
};
