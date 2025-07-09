<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\UserCategory;
use App\Models\Language;

class UserCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buscar o idioma padrão
        $defaultLanguage = Language::where('is_default', 1)->first();
        
        if (!$defaultLanguage) {
            $this->command->info('Nenhum idioma padrão encontrado. Criando idioma inglês...');
            $defaultLanguage = Language::create([
                'name' => 'English',
                'code' => 'en',
                'is_default' => 1,
                'dashboard_default' => 1,
                'rtl' => 0,
                'type' => 'admin'
            ]);
        }

        // Categorias de exemplo
        $categories = [
            [
                'name' => 'Tecnologia',
                'slug' => 'tecnologia',
                'status' => 1,
                'serial_number' => 1
            ],
            [
                'name' => 'Moda',
                'slug' => 'moda',
                'status' => 1,
                'serial_number' => 2
            ],
            [
                'name' => 'Alimentação',
                'slug' => 'alimentacao',
                'status' => 1,
                'serial_number' => 3
            ],
            [
                'name' => 'Serviços',
                'slug' => 'servicos',
                'status' => 1,
                'serial_number' => 4
            ],
            [
                'name' => 'Educação',
                'slug' => 'educacao',
                'status' => 1,
                'serial_number' => 5
            ],
            [
                'name' => 'Saúde e Bem-estar',
                'slug' => 'saude-bem-estar',
                'status' => 1,
                'serial_number' => 6
            ],
            [
                'name' => 'Casa e Decoração',
                'slug' => 'casa-decoracao',
                'status' => 1,
                'serial_number' => 7
            ],
            [
                'name' => 'Esportes',
                'slug' => 'esportes',
                'status' => 1,
                'serial_number' => 8
            ]
        ];

        foreach ($categories as $categoryData) {
            // Verificar se a categoria já existe
            $existingCategory = UserCategory::where('name', $categoryData['name'])
                                           ->where('language_id', $defaultLanguage->id)
                                           ->first();
            
            if (!$existingCategory) {
                $unique_id = uniqid();
                
                UserCategory::create([
                    'unique_id' => $unique_id,
                    'language_id' => $defaultLanguage->id,
                    'name' => $categoryData['name'],
                    'slug' => $categoryData['slug'],
                    'status' => $categoryData['status'],
                    'serial_number' => $categoryData['serial_number']
                ]);
                
                $this->command->info("Categoria '{$categoryData['name']}' criada com sucesso!");
            } else {
                $this->command->info("Categoria '{$categoryData['name']}' já existe.");
            }
        }
        
        $this->command->info('Seeder de categorias de usuários executado com sucesso!');
    }
}
