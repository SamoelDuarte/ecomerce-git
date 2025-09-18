<?php
echo "=== EXTRAÇÃO MANUAL DE DADOS TRADUZIDOS ===\n\n";

// Ler linha por linha
$lines = file('eco-pt.sql', FILE_IGNORE_NEW_LINES);
$restoreSQL = "-- RESTAURAÇÃO DE DADOS TRADUZIDOS\n";
$restoreSQL .= "-- Extraído manualmente do backup eco-pt.sql\n\n";

$currentTable = '';
$inInsert = false;
$insertBuffer = '';

// Tabelas e mapeamento de language_id que queremos restaurar
$targetTables = [
    'blogs' => true,
    'counter_information' => true, 
    'counter_sections' => true,
    'faqs' => true,
    'features' => true,
    'image_texts' => true,
    'menus' => true,
    'pages' => true,
    'popups' => true,
    'processes' => true,
    'seos' => true,
    'testimonials' => true,
    'ulinks' => true
];

// Mapeamento de conversão de language_id
$langConversion = [
    '179' => '176', // árabe → inglês
    '178' => '176', // outro → inglês
    '12345' => '999', // estranho → pt-br
    '12346' => '176'  // estranho → inglês
];

foreach ($lines as $lineNum => $line) {
    $line = trim($line);
    
    // Detectar início de INSERT
    if (preg_match('/^INSERT INTO `([^`]+)`/', $line, $matches)) {
        $currentTable = $matches[1];
        $inInsert = isset($targetTables[$currentTable]);
        $insertBuffer = $line;
        
        if ($inInsert) {
            echo "Encontrado INSERT para tabela: $currentTable (linha " . ($lineNum + 1) . ")\n";
        }
    }
    // Continuar acumulando a linha se estamos em um INSERT de interesse
    elseif ($inInsert) {
        $insertBuffer .= "\n" . $line;
        
        // Verificar se terminou o INSERT (linha termina com ;)
        if (substr($line, -1) === ';') {
            // Processar o INSERT completo
            echo "  Processando INSERT completo...\n";
            
            // Extrair todos os VALUES
            if (preg_match('/VALUES\s*(.+);$/s', $insertBuffer, $matches)) {
                $valuesSection = $matches[1];
                
                // Dividir por registros (entre parênteses)
                preg_match_all('/\(([^)]+(?:\([^)]*\)[^)]*)*)\)/', $valuesSection, $recordMatches);
                
                foreach ($recordMatches[1] as $recordContent) {
                    // Dividir valores por vírgula (método simples)
                    $values = str_getcsv($recordContent);
                    
                    if (count($values) >= 2) {
                        $languageId = trim($values[1], "' ");
                        
                        // Verificar se precisa converter
                        if (isset($langConversion[$languageId])) {
                            $newLangId = $langConversion[$languageId];
                            $values[1] = $newLangId;
                            
                            echo "    Convertendo registro: language_id $languageId → $newLangId\n";
                            
                            // Recriar o INSERT
                            $newValues = '';
                            foreach ($values as $value) {
                                if ($newValues !== '') $newValues .= ', ';
                                // Se é número, não precisa aspas
                                if (is_numeric(trim($value, "' "))) {
                                    $newValues .= trim($value, "' ");
                                } else {
                                    // Manter as aspas
                                    if (substr($value, 0, 1) !== "'" && substr($value, 0, 1) !== '"') {
                                        $newValues .= "'" . addslashes(trim($value)) . "'";
                                    } else {
                                        $newValues .= $value;
                                    }
                                }
                            }
                            
                            $restoreSQL .= "INSERT IGNORE INTO `$currentTable` VALUES ($newValues);\n";
                        }
                    }
                }
            }
            
            $restoreSQL .= "\n";
            $inInsert = false;
            $insertBuffer = '';
        }
    }
}

// Salvar o arquivo
file_put_contents('restore_translations.sql', $restoreSQL);

echo "\n=== ARQUIVO DE RESTAURAÇÃO CRIADO ===\n";
echo "Arquivo: restore_translations.sql\n";
echo "Tamanho: " . strlen($restoreSQL) . " bytes\n";

if (strlen($restoreSQL) > 200) {
    echo "\nPrévia:\n";
    echo substr($restoreSQL, 0, 500) . "...\n";
} else {
    echo "\nConteúdo:\n$restoreSQL\n";
}