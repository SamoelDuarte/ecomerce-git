<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDigitalProductCodesTable extends Migration
{
    public function up()
    {
        Schema::create('digital_product_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_item_id'); // ID do produto digital
            $table->string('name'); // Código digital (ex: chave de ativação)
            $table->string('code'); // Código digital (ex: chave de ativação)
            $table->boolean('is_used')->default(false); // Se já foi utilizado
            $table->timestamp('used_at')->nullable(); // Data/hora de uso
            $table->decimal('price', 8, 2)->nullable(); // valor do codigo
            $table->unsignedBigInteger('order_id')->nullable(); // ID do pedido (opcional)
            $table->timestamps();

            // Chave estrangeira (produto)
            $table->foreign('user_item_id')->references('id')->on('user_items')->onDelete('cascade');

            // ❌ REMOVIDO: índice único que causava erro
            // $table->unique(['user_item_id', 'code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('digital_product_codes');
    }
}
