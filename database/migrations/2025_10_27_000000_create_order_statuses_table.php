<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderStatusesTable extends Migration
{
    public function up()
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Ex: pending, faturado, separacao, transporte, concluido, cancelado
            $table->string('name'); // Nome amigável para exibir
            $table->string('area')->nullable(); // Ex: cliente, lojista, admin
            $table->string('description')->nullable();
            $table->integer('order')->default(0); // Para ordenação
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Insere os status padronizados
        \DB::table('order_statuses')->insert([
            [
                'code' => 'pending',
                'name' => 'Pagamento Pendente',
                'area' => 'cliente,lojista',
                'description' => 'Aguardando pagamento',
                'order' => 1,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'code' => 'aprovado',
                'name' => 'Pagamento Aprovado',
                'area' => 'cliente,lojista',
                'description' => 'Pagamento aprovado',
                'order' => 2,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'faturado',
                'name' => 'Pedido Faturado',
                'area' => 'cliente,lojista',
                'description' => 'Pedido faturado',
                'order' => 3,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'separacao',
                'name' => 'Pedido em Separação',
                'area' => 'cliente,lojista',
                'description' => 'Pedido em separação',
                'order' => 4,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'transporte',
                'name' => 'Em Transporte',
                'area' => 'cliente,lojista',
                'description' => 'Pedido em transporte',
                'order' => 5,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'concluido',
                'name' => 'Concluído',
                'area' => 'cliente,lojista',
                'description' => 'Pedido concluído/entregue',
                'order' => 6,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'cancelado',
                'name' => 'Pedido Cancelado',
                'area' => 'cliente,lojista',
                'description' => 'Pedido cancelado',
                'order' => 7,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Adiciona coluna order_status_id na tabela user_orders
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
        Schema::dropIfExists('order_statuses');
    }
}
