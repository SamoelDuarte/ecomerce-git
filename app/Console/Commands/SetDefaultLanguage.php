<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Language;

class SetDefaultLanguage extends Command
{
    protected $signature = 'language:set-default {code=pt-br : O código do idioma a ser definido como padrão}';
    protected $description = 'Define um idioma como padrão';

    public function handle()
    {
        $code = $this->argument('code');
        
        // Verificar se o idioma existe
        $language = Language::where('code', $code)->first();
        
        if (!$language) {
            $this->error("Idioma com código '$code' não encontrado!");
            return 1;
        }
        
        // Remover todos como padrão
        Language::where('is_default', 1)->update(['is_default' => 0]);
        
        // Definir o idioma escolhido como padrão
        $language->update(['is_default' => 1]);
        
        $this->info("Idioma '{$language->name}' ({$code}) definido como padrão com sucesso!");
        
        return 0;
    }
}