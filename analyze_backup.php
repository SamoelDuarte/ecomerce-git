<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== ANÁLISE DO ARQUIVO eco-pt.sql ===\n\n";

// Ler o arquivo SQL
$sqlContent = file_get_contents('eco-pt.sql');

// Procurar por padrões de INSERT com language_id
preg_match_all('/INSERT INTO `([^`]+)`[^(]*\([^)]*language_id[^)]*\) VALUES\s*\(([^;]+)\);/i', $sqlContent, $matches);

$tablesFound = [];
$languageStats = [];

echo "Analisando dados do backup...\n\n";

for ($i = 0; $i < count($matches[0]); $i++) {
    $tableName = $matches[1][$i];
    $valuesString = $matches[2][$i];
    
    // Dividir os valores por linhas (cada linha é um registro)
    $records = explode('),', $valuesString);
    
    foreach ($records as $record) {
        $record = trim($record, '(), ');
        if (empty($record)) continue;
        
        // Extrair valores separados por vírgula (considerando strings com aspas)
        $values = [];
        $inQuotes = false;
        $current = '';
        $chars = str_split($record . ','); // adicionar vírgula para processar último item
        
        foreach ($chars as $char) {
            if ($char === "'" && !$inQuotes) {
                $inQuotes = true;
                $current .= $char;
            } elseif ($char === "'" && $inQuotes && ($current[strlen($current)-1] !== '\\')) {
                $inQuotes = false;
                $current .= $char;
            } elseif ($char === ',' && !$inQuotes) {
                $values[] = trim($current);
                $current = '';
            } else {
                $current .= $char;
            }
        }
        
        // Encontrar a posição da coluna language_id
        // Para isso, vamos procurar na estrutura da tabela ou usar posição conhecida
        if (count($values) >= 2) {
            $languageId = trim($values[1], "'"); // language_id geralmente é a segunda coluna
            
            if (is_numeric($languageId)) {
                $languageStats[$languageId] = ($languageStats[$languageId] ?? 0) + 1;
                
                if (!isset($tablesFound[$tableName])) {
                    $tablesFound[$tableName] = [];
                }
                if (!isset($tablesFound[$tableName][$languageId])) {
                    $tablesFound[$tableName][$languageId] = 0;
                }
                $tablesFound[$tableName][$languageId]++;
            }
        }
    }
}

echo "=== ESTATÍSTICAS DE LANGUAGE_ID NO BACKUP ===\n";
foreach ($languageStats as $langId => $count) {
    echo "Language ID $langId: $count registros\n";
}

echo "\n=== TABELAS E DISTRIBUIÇÃO POR IDIOMA ===\n";
foreach ($tablesFound as $table => $langs) {
    echo "\nTabela: $table\n";
    foreach ($langs as $langId => $count) {
        $status = in_array($langId, [176, 999]) ? "✓ VÁLIDO" : "⚠ REMOVIDO";
        echo "  - Language ID $langId: $count registros ($status)\n";
    }
}

// Verificar quais tabelas do sistema atual têm poucos dados
echo "\n=== COMPARAÇÃO COM DADOS ATUAIS ===\n";

$currentTables = [
    'basic_extendeds', 'basic_settings', 'bcategories', 'blogs', 
    'counter_information', 'counter_sections', 'faqs', 'features',
    'headings', 'image_texts', 'labels', 'menus', 'pages', 
    'partners', 'popups', 'processes', 'seos', 'testimonials', 'ulinks'
];

foreach ($currentTables as $table) {
    try {
        if (DB::getSchemaBuilder()->hasTable($table)) {
            $currentCount = DB::table($table)->count();
            $backupCount = array_sum($tablesFound[$table] ?? []);
            $missing = $backupCount - $currentCount;
            
            if ($missing > 0) {
                echo "⚠ $table: $currentCount atuais vs $backupCount no backup (faltam $missing)\n";
            } else {
                echo "✓ $table: $currentCount registros (OK)\n";
            }
        }
    } catch (Exception $e) {
        // Ignora erros
    }
}

echo "\n=== RECOMENDAÇÕES ===\n";
echo "1. Registros com language_id 179 (árabe) podem ser convertidos para 176 (inglês) se necessário\n";
echo "2. Verificar se tabelas com poucos dados precisam de restauração\n";
echo "3. Priorizar restauração de: basic_settings, image_texts, headings, seos\n";
echo "4. Confirmar se o conteúdo em português (999) está completo\n\n";