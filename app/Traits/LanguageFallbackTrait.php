<?php

namespace App\Traits;

use App\Models\User\Language;
use Illuminate\Support\Facades\Auth;

trait LanguageFallbackTrait
{
    /**
     * Helper method to get language with fallbacks to pt-BR
     */
    protected function getLanguageWithFallback($languageCode = null, $userId = null)
    {
        $userId = $userId ?? Auth::guard('web')->user()->id;
        
        // Buscar idioma específico se fornecido
        if ($languageCode) {
            $lang = Language::where('code', $languageCode)->where('user_id', $userId)->first();
            if ($lang) return $lang;
        }
        
        // Fallback 1: Buscar idioma padrão do usuário
        $lang = Language::where('user_id', $userId)->where('is_default', 1)->first();
        if ($lang) return $lang;
        
        // Fallback 2: Buscar qualquer idioma do usuário
        $lang = Language::where('user_id', $userId)->first();
        if ($lang) return $lang;
        
        // Fallback 3: Criar objeto padrão se nenhum idioma encontrado
        return (object) [
            'id' => 999,
            'code' => 'pt-BR',
            'name' => 'Português',
            'is_default' => 1
        ];
    }
    
    /**
     * Get safe language ID with fallback
     */
    protected function getSafeLanguageId($languageCode = null, $userId = null)
    {
        return $this->getLanguageWithFallback($languageCode, $userId)->id;
    }
}