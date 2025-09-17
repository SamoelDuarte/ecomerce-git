<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

if (!function_exists('trans_db')) {
    /**
     * Obter tradução do banco de dados
     * 
     * @param string $key Chave da tradução
     * @param array $replace Valores para substituição
     * @param string|null $locale Código do idioma (opcional)
     * @param string $type Tipo de tradução: 'customer' ou 'admin'
     * @return string
     */
    function trans_db($key, $replace = [], $locale = null, $type = 'customer') {
        // Se locale não fornecido, usar o atual ou padrão
        if (!$locale) {
            $locale = app()->getLocale();
            
            // Se for admin_ ou user_, extrair o código real
            if (strpos($locale, 'admin_') === 0) {
                $locale = str_replace('admin_', '', $locale);
                $type = 'admin';
            } elseif (strpos($locale, 'user_') === 0) {
                $locale = str_replace('user_', '', $locale);
            }
        }
        
        // Cache key para performance
        $cacheKey = "translations_{$locale}_{$type}";
        
        // Tentar buscar do cache primeiro
        $translations = Cache::remember($cacheKey, 3600, function() use ($locale, $type) {
            $column = $type === 'admin' ? 'admin_keywords' : 'customer_keywords';
            
            $language = DB::table('languages')
                ->where('code', $locale)
                ->first();
            
            if (!$language || !$language->$column) {
                // Fallback para idioma padrão
                $language = DB::table('languages')
                    ->where('is_default', 1)
                    ->first();
                
                if (!$language || !$language->$column) {
                    return [];
                }
            }
            
            $keywords = json_decode($language->$column, true);
            return is_array($keywords) ? $keywords : [];
        });
        
        // Buscar tradução usando dot notation
        $translation = data_get($translations, $key);
        
        // Se não encontrou, tentar fallback
        if (!$translation) {
            // Fallback para inglês se não for inglês
            if ($locale !== 'en') {
                return trans_db($key, $replace, 'en', $type);
            }
            
            // Se ainda não encontrou, retornar a chave
            return $key;
        }
        
        // Fazer substituições se fornecidas
        if (!empty($replace)) {
            foreach ($replace as $search => $replacement) {
                $translation = str_replace(':' . $search, $replacement, $translation);
            }
        }
        
        return $translation;
    }
}

if (!function_exists('__db')) {
    /**
     * Alias para trans_db
     */
    function __db($key, $replace = [], $locale = null) {
        return trans_db($key, $replace, $locale);
    }
}

if (!function_exists('admin_trans')) {
    /**
     * Tradução específica para admin
     */
    function admin_trans($key, $replace = [], $locale = null) {
        return trans_db($key, $replace, $locale, 'admin');
    }
}

if (!function_exists('clear_translations_cache')) {
    /**
     * Limpar cache de traduções
     */
    function clear_translations_cache() {
        $languages = DB::table('languages')->pluck('code');
        
        foreach ($languages as $code) {
            Cache::forget("translations_{$code}_customer");
            Cache::forget("translations_{$code}_admin");
        }
        
        return true;
    }
}