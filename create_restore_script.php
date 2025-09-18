<?php
echo "=== RESTAURAÇÃO DE DADOS TRADUZIDOS ===\n\n";

// Ler o arquivo SQL
$sqlContent = file_get_contents('eco-pt.sql');

// Tabelas prioritárias para restauração
$priorityTables = [
    'blogs' => ['179' => '176'], // árabe → inglês
    'counter_information' => ['179' => '176'], 
    'counter_sections' => ['178' => '176', '179' => '176'],
    'faqs' => ['179' => '176'],
    'features' => ['179' => '176'],
    'image_texts' => ['12345' => '999', '12346' => '176'], // IDs estranhos → pt-br e inglês
    'menus' => ['179' => '176'],
    'pages' => ['179' => '176'],
    'popups' => ['179' => '176'],
    'processes' => ['179' => '176'],
    'seos' => ['179' => '176'],
    'testimonials' => ['179' => '176'],
    'ulinks' => ['179' => '176']
];

$restoreSQL = "-- RESTAURAÇÃO DE DADOS TRADUZIDOS\n";
$restoreSQL .= "-- Gerado automaticamente para recuperar conteúdo perdido\n\n";

foreach ($priorityTables as $tableName => $langMapping) {
    echo "Processando tabela: $tableName\n";
    
    // Procurar INSERTs para esta tabela
    $pattern = "/INSERT INTO `$tableName`[^(]*\([^)]*\) VALUES\s*\(([^;]+)\);/i";
    if (preg_match($pattern, $sqlContent, $matches)) {
        $valuesString = $matches[1];
        
        echo "  Encontrado INSERT para $tableName\n";
        
        // Dividir os registros
        $records = [];
        $currentRecord = '';
        $parenLevel = 0;
        $inQuotes = false;
        $quoteChar = '';
        
        for ($i = 0; $i < strlen($valuesString); $i++) {
            $char = $valuesString[$i];
            
            if (($char === '"' || $char === "'") && !$inQuotes) {
                $inQuotes = true;
                $quoteChar = $char;
            } elseif ($char === $quoteChar && $inQuotes) {
                $inQuotes = false;
            } elseif ($char === '(' && !$inQuotes) {
                $parenLevel++;
            } elseif ($char === ')' && !$inQuotes) {
                $parenLevel--;
                if ($parenLevel === 0) {
                    $currentRecord .= $char;
                    $records[] = trim($currentRecord);
                    $currentRecord = '';
                    continue;
                }
            } elseif ($char === ',' && $parenLevel === 0 && !$inQuotes) {
                // Pula a vírgula entre registros
                continue;
            }
            
            $currentRecord .= $char;
        }
        
        echo "  Encontrados " . count($records) . " registros\n";
        
        foreach ($records as $record) {
            $record = trim($record, '() ');
            if (empty($record)) continue;
            
            // Parse simples dos valores
            $values = [];
            $current = '';
            $inQuotes = false;
            $quoteChar = '';
            
            for ($i = 0; $i < strlen($record); $i++) {
                $char = $record[$i];
                
                if (($char === '"' || $char === "'") && !$inQuotes) {
                    $inQuotes = true;
                    $quoteChar = $char;
                    $current .= $char;
                } elseif ($char === $quoteChar && $inQuotes) {
                    $inQuotes = false;
                    $current .= $char;
                } elseif ($char === ',' && !$inQuotes) {
                    $values[] = trim($current);
                    $current = '';
                } else {
                    $current .= $char;
                }
            }
            if (!empty($current)) {
                $values[] = trim($current);
            }
            
            if (count($values) >= 2) {
                $currentLangId = trim($values[1], "'");
                
                // Verificar se este language_id deve ser convertido
                if (isset($langMapping[$currentLangId])) {
                    $newLangId = $langMapping[$currentLangId];
                    $values[1] = $newLangId;
                    
                    echo "    Convertendo language_id $currentLangId → $newLangId\n";
                    
                    // Gerar o INSERT
                    $newRecord = "(" . implode(", ", $values) . ")";
                    $restoreSQL .= "INSERT IGNORE INTO `$tableName` VALUES $newRecord;\n";
                }
            }
        }
        
        $restoreSQL .= "\n";
    } else {
        echo "  ⚠ Não encontrado INSERT para $tableName\n";
    }
}

// Salvar o arquivo de restauração
file_put_contents('restore_translations.sql', $restoreSQL);

echo "\n=== ARQUIVO DE RESTAURAÇÃO CRIADO ===\n";
echo "Arquivo: restore_translations.sql\n";
echo "Execute este arquivo para restaurar as traduções perdidas.\n";
echo "\nPrévia do arquivo:\n";
echo substr($restoreSQL, 0, 1000) . "...\n";