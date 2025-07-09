<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Admin\UserCategory;
use App\Models\Language;

class FixUserCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:user-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica e corrige as categorias dos usuários';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Verificando categorias de usuários...');
        
        // Verificar idioma padrão
        $defaultLanguage = Language::where('is_default', 1)->first();
        if (!$defaultLanguage) {
            $this->error('❌ Nenhum idioma padrão encontrado!');
            return 1;
        }
        
        $this->info("✅ Idioma padrão: {$defaultLanguage->name} ({$defaultLanguage->code})");
        
        // Verificar categorias existentes
        $categoriesCount = UserCategory::where('language_id', $defaultLanguage->id)->count();
        $this->info("📂 Total de categorias: {$categoriesCount}");
        
        if ($categoriesCount == 0) {
            $this->warn('⚠️  Nenhuma categoria encontrada. Execute: php artisan db:seed --class=UserCategorySeeder');
        } else {
            $categories = UserCategory::where('language_id', $defaultLanguage->id)->get();
            $this->info('📋 Categorias disponíveis:');
            foreach ($categories as $category) {
                $this->line("   - {$category->name} (ID: {$category->unique_id})");
            }
        }
        
        // Verificar usuários
        $totalUsers = User::count();
        $usersWithCategory = User::whereNotNull('category_id')->count();
        $usersWithoutCategory = $totalUsers - $usersWithCategory;
        
        $this->info("👥 Total de usuários: {$totalUsers}");
        $this->info("✅ Usuários com categoria: {$usersWithCategory}");
        $this->info("❌ Usuários sem categoria: {$usersWithoutCategory}");
        
        // Verificar usuários com categorias inválidas
        $usersWithInvalidCategory = User::whereNotNull('category_id')
            ->whereNotIn('category_id', UserCategory::where('language_id', $defaultLanguage->id)->pluck('unique_id'))
            ->count();
            
        if ($usersWithInvalidCategory > 0) {
            $this->warn("⚠️  Usuários com categoria inválida: {$usersWithInvalidCategory}");
        }
        
        // Testar query do relacionamento
        $this->info('🔗 Testando relacionamento...');
        $userWithCategory = User::leftJoin('user_categories', function($join) use ($defaultLanguage) {
            $join->on('users.category_id', '=', 'user_categories.unique_id')
                 ->where('user_categories.language_id', '=', $defaultLanguage->id);
        })
        ->select('users.*', 'user_categories.name as category_name')
        ->whereNotNull('users.category_id')
        ->first();
        
        if ($userWithCategory && $userWithCategory->category_name) {
            $this->info("✅ Relacionamento funcionando! Exemplo: {$userWithCategory->username} -> {$userWithCategory->category_name}");
        } else {
            $this->warn('⚠️  Relacionamento não está funcionando ou não há dados de teste.');
        }
        
        // Sugestões
        $this->info('💡 Sugestões:');
        if ($categoriesCount == 0) {
            $this->line('   1. Execute: php artisan db:seed --class=UserCategorySeeder');
        }
        if ($usersWithoutCategory > 0) {
            $this->line('   2. Atribua categorias aos usuários através do painel admin');
        }
        if ($usersWithInvalidCategory > 0) {
            $this->line('   3. Corrija categorias inválidas dos usuários');
        }
        
        return 0;
    }
}
