<?php
require_once 'vendor/autoload.php';

// Carrega as configurações do Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== INVESTIGAÇÃO DE IDIOMAS ===\n\n";

try {
    // 1. Verificar todas as tabelas que contêm 'lang' no nome
    echo "1. TABELAS RELACIONADAS A IDIOMAS:\n";
    $tables = DB::select('SHOW TABLES');
    foreach($tables as $table) {
        $tableName = array_values((array)$table)[0];
        if(stripos($tableName, 'lang') !== false || stripos($tableName, 'language') !== false) {
            echo "   - $tableName\n";
        }
    }
    
    echo "\n2. ESTRUTURA DA TABELA LANGUAGES (se existir):\n";
    try {
        if(Schema::hasTable('languages')) {
            $columns = DB::select("DESCRIBE languages");
            foreach($columns as $column) {
                echo "   - {$column->Field} ({$column->Type})\n";
            }
            
            echo "\n3. DADOS NA TABELA LANGUAGES:\n";
            $languages = DB::select("SELECT * FROM languages LIMIT 10");
            foreach($languages as $lang) {
                $langArray = (array)$lang;
                echo "   ID: {$langArray['id']} | ";
                if(isset($langArray['name'])) echo "Nome: {$langArray['name']} | ";
                if(isset($langArray['code'])) echo "Código: {$langArray['code']} | ";
                if(isset($langArray['locale'])) echo "Locale: {$langArray['locale']} | ";
                if(isset($langArray['status'])) echo "Status: {$langArray['status']} | ";
                echo "\n";
            }
        } else {
            echo "   Tabela 'languages' não existe\n";
        }
    } catch(Exception $e) {
        echo "   Erro ao acessar tabela languages: " . $e->getMessage() . "\n";
    }
    
    echo "\n4. BUSCAR TABELAS COM COLUNA language_id:\n";
    $allTables = DB::select('SHOW TABLES');
    foreach($allTables as $table) {
        $tableName = array_values((array)$table)[0];
        try {
            $columns = DB::select("DESCRIBE $tableName");
            foreach($columns as $column) {
                if($column->Field === 'language_id') {
                    echo "   - Tabela: $tableName tem coluna language_id\n";
                    
                    // Verificar alguns valores únicos desta coluna
                    $uniqueValues = DB::select("SELECT DISTINCT language_id FROM $tableName WHERE language_id IS NOT NULL ORDER BY language_id LIMIT 5");
                    $values = array_map(function($v) { return $v->language_id; }, $uniqueValues);
                    echo "     Valores únicos: " . implode(', ', $values) . "\n";
                    break;
                }
            }
        } catch(Exception $e) {
            // Ignora erros de tabelas que não conseguimos acessar
        }
    }
    
    echo "\n5. VERIFICAR SE EXISTE TABELA DE CONFIGURAÇÃO DE IDIOMAS:\n";
    try {
        if(Schema::hasTable('language_settings') || Schema::hasTable('locale_settings')) {
            $tableName = Schema::hasTable('language_settings') ? 'language_settings' : 'locale_settings';
            echo "   Encontrada tabela: $tableName\n";
            $data = DB::select("SELECT * FROM $tableName LIMIT 5");
            foreach($data as $row) {
                print_r($row);
            }
        } else {
            echo "   Não encontradas tabelas de configuração específicas\n";
        }
    } catch(Exception $e) {
        echo "   Erro: " . $e->getMessage() . "\n";
    }

} catch(Exception $e) {
    echo "Erro geral: " . $e->getMessage() . "\n";
}