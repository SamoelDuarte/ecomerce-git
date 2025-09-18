<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== VERIFICAÇÃO DE ESTRUTURA DE TABELAS ===\n\n";

// Tabelas prioritárias
$tables = ['blogs', 'counter_information', 'counter_sections', 'faqs', 'features', 'image_texts', 'menus', 'pages', 'popups', 'processes', 'seos', 'testimonials', 'ulinks'];

foreach ($tables as $table) {
    echo "TABELA: $table\n";
    try {
        $columns = DB::select("DESCRIBE $table");
        foreach ($columns as $column) {
            echo "  - {$column->Field} ({$column->Type})\n";
        }
        
        // Contar registros atuais
        $count = DB::table($table)->count();
        echo "  Registros atuais: $count\n";
        
    } catch (Exception $e) {
        echo "  ERRO: " . $e->getMessage() . "\n";
    }
    echo "\n";
}