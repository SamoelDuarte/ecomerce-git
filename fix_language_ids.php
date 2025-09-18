<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== ANÁLISE E CORREÇÃO DE LANGUAGE_ID ===\n\n";

// IDs válidos conforme a tabela languages
$validLanguageIds = [176, 999]; // en e pt-br
$defaultLanguageId = 999; // pt-br como padrão

echo "IDs de idiomas válidos: " . implode(', ', $validLanguageIds) . "\n";
echo "ID padrão (pt-br): $defaultLanguageId\n\n";

// Lista das tabelas com language_id para análise
$tablesWithLanguageId = [
    'additional_section_contents',
    'basic_extendeds',
    'basic_settings', 
    'bcategories',
    'blogs',
    'counter_information',
    'counter_sections',
    'faqs',
    'features',
    'headings',
    'image_texts',
    'labels',
    'menus',
    'pages',
    'partners',
    'popups',
    'processes',
    'product_variant_option_contents',
    'product_variation_contents',
    'seos',
    'testimonials',
    'ulinks',
    'user_about_testimonials',
    'user_about_us',
    'user_about_us_features',
    'user_additional_section_contents',
    'user_banners',
    'user_basic_extendes',
    'user_blog_categories',
    'user_blog_contents',
    'user_call_to_actions',
    'user_categories',
    'user_category_variations',
    'user_contacts',
    'user_counter_information',
    'user_counter_sections',
    'user_currencies',
    'user_faqs',
    'user_footers',
    'user_headers',
    'user_headings',
    'user_hero_sliders',
    'user_howit_work_sections',
    'user_item_categories',
    'user_item_contents',
    'user_item_sub_categories',
    'user_item_variations',
    'user_menus',
    'user_offline_gateways',
    'user_page_contents',
    'user_sections',
    'user_seos',
    'user_shipping_charges',
    'user_sub_category_variations',
    'user_tabs',
    'user_ulinks',
    'variant_contents',
    'variant_option_contents'
];

$totalProblems = 0;
$totalFixed = 0;

foreach($tablesWithLanguageId as $tableName) {
    try {
        // Verificar se a tabela existe
        if(!DB::getSchemaBuilder()->hasTable($tableName)) {
            continue;
        }
        
        // Contar registros com language_id inválido
        $invalidCount = DB::table($tableName)
            ->whereNotIn('language_id', $validLanguageIds)
            ->whereNotNull('language_id')
            ->count();
            
        $nullCount = DB::table($tableName)
            ->whereNull('language_id')
            ->count();
            
        if($invalidCount > 0 || $nullCount > 0) {
            echo "TABELA: $tableName\n";
            echo "  - Registros com language_id inválido: $invalidCount\n";
            echo "  - Registros com language_id NULL: $nullCount\n";
            
            $totalProblems += ($invalidCount + $nullCount);
            
            // Mostrar alguns exemplos dos IDs inválidos
            if($invalidCount > 0) {
                $examples = DB::table($tableName)
                    ->select('language_id', DB::raw('COUNT(*) as count'))
                    ->whereNotIn('language_id', $validLanguageIds)
                    ->whereNotNull('language_id')
                    ->groupBy('language_id')
                    ->limit(5)
                    ->get();
                    
                echo "  - IDs inválidos encontrados: ";
                foreach($examples as $example) {
                    echo "{$example->language_id} ({$example->count}x), ";
                }
                echo "\n";
            }
            
            // Perguntar se deve corrigir
            echo "  Corrigir registros inválidos para o padrão ($defaultLanguageId)? (s/n): ";
            $handle = fopen("php://stdin", "r");
            $response = trim(fgets($handle));
            fclose($handle);
            
            if(strtolower($response) === 's' || strtolower($response) === 'sim') {
                // Corrigir IDs inválidos
                if($invalidCount > 0) {
                    $fixed1 = DB::table($tableName)
                        ->whereNotIn('language_id', $validLanguageIds)
                        ->whereNotNull('language_id')
                        ->update(['language_id' => $defaultLanguageId]);
                    echo "  ✓ Corrigidos $fixed1 registros com IDs inválidos\n";
                    $totalFixed += $fixed1;
                }
                
                // Corrigir NULLs
                if($nullCount > 0) {
                    $fixed2 = DB::table($tableName)
                        ->whereNull('language_id')
                        ->update(['language_id' => $defaultLanguageId]);
                    echo "  ✓ Corrigidos $fixed2 registros com NULL\n";
                    $totalFixed += $fixed2;
                }
            } else {
                echo "  - Pulando correção da tabela $tableName\n";
            }
            echo "\n";
        }
        
    } catch(Exception $e) {
        echo "ERRO na tabela $tableName: " . $e->getMessage() . "\n\n";
    }
}

echo "=== RESUMO ===\n";
echo "Total de problemas encontrados: $totalProblems\n";
echo "Total de registros corrigidos: $totalFixed\n";

if($totalFixed > 0) {
    echo "\n=== VERIFICAÇÃO FINAL ===\n";
    echo "Verificando se ainda existem language_id inválidos...\n";
    
    $remainingProblems = 0;
    foreach($tablesWithLanguageId as $tableName) {
        try {
            if(!DB::getSchemaBuilder()->hasTable($tableName)) {
                continue;
            }
            
            $count = DB::table($tableName)
                ->where(function($query) use ($validLanguageIds) {
                    $query->whereNotIn('language_id', $validLanguageIds)
                          ->orWhereNull('language_id');
                })
                ->count();
                
            if($count > 0) {
                echo "  - $tableName ainda tem $count problemas\n";
                $remainingProblems += $count;
            }
        } catch(Exception $e) {
            // Ignora
        }
    }
    
    if($remainingProblems === 0) {
        echo "✓ Todos os language_id estão corretos!\n";
    } else {
        echo "⚠ Ainda existem $remainingProblems problemas\n";
    }
}