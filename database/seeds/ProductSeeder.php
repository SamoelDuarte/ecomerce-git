<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorias = [
            [
                'unique_id'     => '673ae13e7e249',
                'serial_number' => 1,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Móveis de Quarto',
                'color'         => 'F3E7EA',
                'slug'          => 'moveis-de-quarto',
                'image'         => '7c73cf9ed68b0de22f6460ed267dfc18d6a7a533.png',
                'status'        => 1,
                'is_feature'    => 0,
                'created_at'    => '2024-11-18 06:39:58',
                'updated_at'    => '2025-01-30 07:39:11',
            ],
            [
                'unique_id'     => '673ae263745f3',
                'serial_number' => 2,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Móveis de Jantar',
                'color'         => 'EADEEA',
                'slug'          => 'moveis-de-jantar',
                'image'         => '3f7b4bad628e03f18eac24f81779068843f821f1.png',
                'status'        => 1,
                'is_feature'    => 0,
                'created_at'    => '2024-11-18 06:50:49',
                'updated_at'    => '2025-01-30 07:39:16',
            ],
            [
                'unique_id'     => '673ae272be03d',
                'serial_number' => 3,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Móveis Infantis',
                'color'         => 'C9CFF8',
                'slug'          => 'moveis-infantis',
                'image'         => '10c8c6aaeb65111fc71d23460ce3c30abd1c1547.png',
                'status'        => 1,
                'is_feature'    => 0,
                'created_at'    => '2024-11-18 06:45:06',
                'updated_at'    => '2025-01-30 07:39:20',
            ],
            [
                'unique_id'     => '673ae39975e9a',
                'serial_number' => 4,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Móveis de Luxo',
                'color'         => 'C5D9CB',
                'slug'          => 'moveis-de-luxo',
                'image'         => 'cb16407a669152d658736b9a4100ecbb511878d6.png',
                'status'        => 1,
                'is_feature'    => 0,
                'created_at'    => '2024-11-18 06:50:01',
                'updated_at'    => '2025-01-30 07:39:24',
            ],
            [
                'unique_id'     => '673ae3a5ce1e4',
                'serial_number' => 5,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Móveis de Escritório',
                'color'         => 'D4EDFF',
                'slug'          => 'moveis-de-escritorio',
                'image'         => 'c7689e0a1610a1f10e2d3c0e98662b858c708b70.png',
                'status'        => 1,
                'is_feature'    => 0,
                'created_at'    => '2024-11-18 06:50:13',
                'updated_at'    => '2025-01-30 07:39:27',
            ],
            [
                'unique_id'     => '673ae3b916027',
                'serial_number' => 6,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Móveis de Cozinha',
                'color'         => 'C5C4FF',
                'slug'          => 'moveis-de-cozinha',
                'image'         => '7e162302fd79073bf558a8e42a5ac466a390ee68.png',
                'status'        => 1,
                'is_feature'    => 0,
                'created_at'    => '2024-11-18 06:50:33',
                'updated_at'    => '2025-01-30 07:39:30',
            ],
            [
                'unique_id'     => '673ae3c9c1168',
                'serial_number' => 7,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Móveis de Armazenamento',
                'color'         => 'FFC9C9',
                'slug'          => 'moveis-de-armazenamento',
                'image'         => '8e1c8ef1687799fb96c7fd87057b69dc23a48cd0.png',
                'status'        => 1,
                'is_feature'    => 0,
                'created_at'    => '2024-11-18 06:44:51',
                'updated_at'    => '2025-01-30 07:39:34',
            ],
        ];

        DB::table('user_item_categories')->insert($categorias);
        $categorias_ids = DB::table('user_item_categories')
            ->where('user_id', 11)
            ->where('language_id', 35)
            ->whereIn('unique_id', collect($categorias)->pluck('unique_id'))
            ->pluck('id', 'unique_id') // [unique_id => id]
            ->toArray();


        $subcategorias = [
            // Móveis de Quarto (673ae13e7e249)
            [
                'unique_id'     => '673ae9686ca1f',
                'serial_number' => 0,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Criados-mudos',
                'slug'          => 'criados-mudos',
                'status'        => 1,
                'category_uid'  => '673ae13e7e249',
            ],
            [
                'unique_id'     => '673ae970add57',
                'serial_number' => 0,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Camas',
                'slug'          => 'camas',
                'status'        => 1,
                'category_uid'  => '673ae13e7e249',
            ],
            [
                'unique_id'     => '673ae97aeccb7',
                'serial_number' => 0,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Cômodas e Gaveteiros',
                'slug'          => 'comodas-e-gaveteiros',
                'status'        => 1,
                'category_uid'  => '673ae13e7e249',
            ],

            // Móveis de Escritório (673ae3a5ce1e4)
            [
                'unique_id'     => '673aec4865606',
                'serial_number' => 0,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Mesas',
                'slug'          => 'mesas',
                'status'        => 1,
                'category_uid'  => '673ae3a5ce1e4',
            ],
            [
                'unique_id'     => '673aef7e14514',
                'serial_number' => 0,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Cadeiras',
                'slug'          => 'cadeiras',
                'status'        => 1,
                'category_uid'  => '673ae3a5ce1e4',
            ],
            [
                'unique_id'     => '673b06560bc65',
                'serial_number' => 0,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Mesa de Reunião',
                'slug'          => 'mesa-de-reuniao',
                'status'        => 1,
                'category_uid'  => '673ae3a5ce1e4',
            ],

            // Móveis de Luxo (673ae39975e9a)
            [
                'unique_id'     => '673b016374a6c',
                'serial_number' => 0,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Sofás',
                'slug'          => 'sofas',
                'status'        => 1,
                'category_uid'  => '673ae39975e9a',
            ],
            [
                'unique_id'     => '673b021600dca',
                'serial_number' => 0,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Mesas de Centro',
                'slug'          => 'mesas-de-centro',
                'status'        => 1,
                'category_uid'  => '673ae39975e9a',
            ],
            [
                'unique_id'     => '673b02c498c97',
                'serial_number' => 0,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Cadeiras',
                'slug'          => 'cadeiras',
                'status'        => 1,
                'category_uid'  => '673ae39975e9a',
            ],

            // Móveis de Armazenamento (673ae3c9c1168)
            [
                'unique_id'     => '673b071ff0941',
                'serial_number' => 0,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Armários',
                'slug'          => 'armarios',
                'status'        => 1,
                'category_uid'  => '673ae3c9c1168',
            ],
            [
                'unique_id'     => '673b07385d894',
                'serial_number' => 0,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Guarda-roupas',
                'slug'          => 'guarda-roupas',
                'status'        => 1,
                'category_uid'  => '673ae3c9c1168',
            ],
            [
                'unique_id'     => '673b07447d2df',
                'serial_number' => 0,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Gaveteiros',
                'slug'          => 'gaveteiros',
                'status'        => 1,
                'category_uid'  => '673ae3c9c1168',
            ],

            // Móveis Infantis (673ae272be03d)
            [
                'unique_id'     => '673b1ea013944',
                'serial_number' => 0,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Camas de Bebê',
                'slug'          => 'camas-de-bebe',
                'status'        => 1,
                'category_uid'  => '673ae272be03d',
            ],
            [
                'unique_id'     => '673c2eb2a0377',
                'serial_number' => 0,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Cadeirinhas',
                'slug'          => 'cadeirinhas',
                'status'        => 1,
                'category_uid'  => '673ae272be03d',
            ],

            // Móveis de Cozinha (673ae3b916027)
            [
                'unique_id'     => '673c2d1f71d33',
                'serial_number' => 0,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Mesas',
                'slug'          => 'mesas-cozinha',
                'status'        => 1,
                'category_uid'  => '673ae3b916027',
            ],
            [
                'unique_id'     => '673c2d2829a8e',
                'serial_number' => 0,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Cadeiras',
                'slug'          => 'cadeiras-cozinha',
                'status'        => 1,
                'category_uid'  => '673ae3b916027',
            ],
            [
                'unique_id'     => '673c2d2f45c9e',
                'serial_number' => 0,
                'user_id'       => 11,
                'language_id'   => 35,
                'name'          => 'Banquinhos',
                'slug'          => 'banquinhos',
                'status'        => 1,
                'category_uid'  => '673ae3b916027',
            ],
        ];

        // Monta e insere
        foreach ($subcategorias as &$sub) {
            $sub['category_id'] = $categorias_ids[$sub['category_uid']] ?? null;
            $sub['created_at'] = now();
            $sub['updated_at'] = now();
            unset($sub['category_uid']);
        }
        DB::table('user_item_sub_categories')->insert($subcategorias);

        // Salva os IDs gerados para usar nos produtos
        $subcategorias_ids = DB::table('user_item_sub_categories')
            ->where('user_id', 11)
            ->where('language_id', 35)
            ->pluck('id', 'unique_id')
            ->toArray();

        $produtos = [
            [
                'item_id' => 21,
                'category_uid' => '673ae13e7e249', // Móveis de Quarto
                'subcategory_uid' => '673ae9686ca1f', // Criados-mudos
                'title' => 'Criado-mudo Moderno Mid-Century',
                'slug' => 'criado-mudo-moderno-mid-century',
                'summary' => 'Este Criado-mudo Mid-Century combina estilo atemporal com um design funcional. Ideal para qualquer quarto, oferece armazenamento conveniente e uma estética minimalista e elegante.',
                'description' => '<p>O Criado-mudo Moderno Mid-Century traz um visual clássico e atemporal para o seu quarto com seu design elegante e minimalista. Feito com madeira de alta qualidade, possui uma gaveta espaçosa para armazenar itens essenciais e uma prateleira aberta para livros ou decoração. Suas linhas limpas e tons de madeira quente o tornam perfeito para ambientes contemporâneos e tradicionais.</p><p>Projetado com estilo e praticidade em mente, o criado-mudo oferece uma gaveta deslizante para manter seus objetos organizados e uma prateleira inferior para itens decorativos, plantas ou livros favoritos.</p>',
            ],
            [
                'item_id' => 22,
                'category_uid' => '673ae3a5ce1e4', // Móveis de Escritório
                'subcategory_uid' => '673aef7e14514', // Cadeiras
                'title' => 'Armazenamento Compacto para Computador',
                'slug' => 'armazenamento-compacto-para-computador',
                'summary' => 'Maximize seu espaço com este Armazenamento Compacto para Computador. Projetado para caber embaixo da mesa, oferece armazenamento prático para acessórios de escritório.',
                'description' => '<p>O <strong>Armazenamento Compacto para Computador</strong> é perfeito para escritórios domésticos. Cabe sob a mesa e mantém seu espaço de trabalho organizado. Oferece espaço para teclado, mouse, cabos e suprimentos de escritório.</p><p>Feito com madeira de alta qualidade e acabamento fosco, conta com gavetas deslizantes e compartimentos abertos para facilitar o acesso e armazenamento de documentos e eletrônicos.</p>',
            ],
            [
                'item_id' => 23,
                'category_uid' => '673ae3a5ce1e4', // Móveis de Escritório
                'subcategory_uid' => '673b06560bc65', // Mesa de Reunião
                'title' => 'Cadeira Executiva Ergonômica',
                'slug' => 'cadeira-executiva-ergonomica',
                'summary' => 'Aumente sua produtividade e conforto com esta Cadeira Executiva Ergonômica. Suporte lombar, ajustes e materiais premium para longas horas de trabalho.',
                'description' => '<p>A <strong>Cadeira Executiva Ergonômica</strong> é ideal para escritórios e home office. Oferece suporte ergonômico com encosto curvo, ajuste de altura e apoios de braço ajustáveis.</p><p>Seu design moderno com tecido respirável mantém o conforto durante todo o dia.</p>',
            ],
            [
                'item_id' => 24,
                'category_uid' => '673ae13e7e249', // Móveis de Quarto
                'subcategory_uid' => '673ae970add57', // Camas
                'title' => 'Cama King Estofada Premium',
                'slug' => 'cama-king-estofada-premium',
                'summary' => 'Transforme seu quarto em um santuário de luxo com esta Cama King Estofada. Design moderno, materiais de alta qualidade e conforto supremo.',
                'description' => '<p>A <strong>Cama King Estofada Premium</strong> combina estilo, conforto e durabilidade. O painel estofado oferece um toque sofisticado, com tecido suave e acabamento tufted clássico.</p><p>A estrutura de madeira maciça garante estabilidade, enquanto a base baixa elimina a necessidade de estrado adicional.</p>',
            ],
            [
                'item_id' => 25,
                'category_uid' => '673ae39975e9a', // Móveis de Luxo
                'subcategory_uid' => '673b016374a6c', // Sofás
                'title' => 'Sofá Luxuoso Moderno de 3 Lugares',
                'slug' => 'sofa-luxuoso-moderno-3-lugares',
                'summary' => 'Eleve sua sala com o Sofá Luxuoso Moderno de 3 Lugares. Conforto, estilo contemporâneo e materiais premium se combinam em um design sofisticado.',
                'description' => '<p>O <strong>Sofá Luxuoso Moderno de 3 Lugares</strong> é a união perfeita entre conforto, estilo e funcionalidade. Ideal para receber amigos ou relaxar, é estofado com tecido de alta qualidade e possui design limpo que harmoniza com decorações modernas ou tradicionais.</p><p>Com assentos espaçosos para até três pessoas, espuma de alta densidade para suporte ideal, estrutura de madeira robusta e pés metálicos elegantes, este sofá oferece durabilidade e será o destaque da sua sala por muitos anos.</p>',
            ],
            [
                'item_id' => 26,
                'category_uid' => '673ae39975e9a', // Móveis de Luxo
                'subcategory_uid' => '673b021600dca', // Mesas de Centro
                'title' => 'Mesa de Centro com Tampo de Vidro Elegante',
                'slug' => 'mesa-centro-tampo-vidro-elegante',
                'summary' => 'Adicione elegância ao seu ambiente com esta Mesa de Centro com Tampo de Vidro. Design moderno e estrutura durável em perfeita harmonia.',
                'description' => '<p>A <strong>Mesa de Centro com Tampo de Vidro Elegante</strong> é uma peça sofisticada para complementar sua sala de estar. Seu design minimalista inclui tampo de vidro transparente e base metálica robusta, proporcionando um visual leve e moderno.</p><p>Com 1,20m de comprimento, oferece espaço amplo para itens decorativos, bebidas ou livros. O vidro é fácil de limpar e a estrutura metálica confere um toque industrial refinado que combina com diversos estilos de decoração.</p>',
            ],
            [
                'item_id' => 27,
                'category_uid' => '673ae39975e9a', // Móveis de Luxo
                'subcategory_uid' => '673b02c498c97', // Cadeiras
                'title' => 'Mesa Lateral com Poltrona Moderna',
                'slug' => 'mesa-lateral-com-poltrona-moderna',
                'summary' => 'A união de estilo e funcionalidade: Mesa Lateral com Poltrona Moderna. Ideal para espaços compactos com design inteligente e confortável.',
                'description' => '<p>A <strong>Mesa Lateral com Poltrona Moderna</strong> é uma peça inovadora e funcional para ambientes compactos. Com estrutura de madeira e estofado confortável, oferece assento de qualidade aliado a uma mesa lateral integrada.</p><p>Perfeita para leitura, descanso ou apoio de objetos, combina linhas limpas com praticidade. Adequada para salas, quartos ou home office modernos.</p>',
            ],
            [
                'item_id' => 28,
                'category_uid' => '673ae3c9c1168', // Móveis de Armazenamento
                'subcategory_uid' => '673b07447d2df', // Gaveteiros
                'title' => 'Guarda-Roupa Moderno de 3 Portas',
                'slug' => 'guarda-roupa-moderno-3-portas',
                'summary' => 'Maximize seu espaço com este Guarda-Roupa Moderno de 3 Portas. Design contemporâneo e amplo espaço interno com prateleiras e cabideiros.',
                'description' => '<p>O <strong>Guarda-Roupa Moderno de 3 Portas</strong> oferece elegância e praticidade ao quarto. Com portas lisas, linhas retas e acabamento refinado, combina com estilos modernos ou clássicos.</p><p>Seu interior é funcional: espaço para cabides, prateleiras para roupas dobradas e ótima organização. Estrutura resistente garante durabilidade e excelente custo-benefício.</p>',
            ],
            [
                'item_id' => 29,
                'category_uid' => '673ae39975e9a', // Móveis de Luxo
                'subcategory_uid' => '673b02c498c97', // Camas Beliche
                'title' => 'Beliche Twin/Full Salva Espaço',
                'slug' => 'beliche-twin-full-salva-espaco',
                'summary' => 'Maximize o espaço e a funcionalidade no quarto das crianças com este beliche Twin sobre Full. Ideal para ambientes compactos ou quartos compartilhados.',
                'description' => '<p>O <strong>Beliche Twin/Full Salva Espaço</strong> oferece estilo e praticidade em uma peça compacta. Ideal para quartos infantis, de hóspedes ou espaços pequenos, combina uma cama de solteiro em cima com uma de casal embaixo.</p><p>Feito em madeira de alta qualidade, possui estrutura resistente, laterais com proteção contra quedas e escada integrada para acesso seguro à parte superior. Seu design neutro combina com qualquer decoração e proporciona conforto e segurança.</p>',
            ],
            [
                'item_id' => 30,
                'category_uid' => '673ae3e02c5b6', // Escritório
                'subcategory_uid' => '673b021600dca', // Mesas de Escritório
                'title' => 'Mesa Executiva Ergonômica de Escritório',
                'slug' => 'mesa-executiva-ergonomica-escritorio',
                'summary' => 'Atualize seu espaço de trabalho com esta mesa executiva ergonômica, ideal para conforto, produtividade e estilo moderno.',
                'description' => '<p>A <strong>Mesa Executiva Ergonômica de Escritório</strong> une funcionalidade e estilo moderno. Com amplo espaço para computador, documentos e materiais de escritório, ela é ideal para ambientes corporativos ou home office.</p><p>Fabricada com madeira de alta qualidade e componentes metálicos duráveis, oferece longa vida útil. Sua superfície laminada proporciona aparência elegante e é fácil de limpar. O design ergonômico contribui para uma postura saudável e maior eficiência no dia a dia.</p>',
            ],
            [
                'item_id' => 31,
                'category_uid' => '673ae3e02c5b6', // Escritório
                'subcategory_uid' => '673b0a6504691', // Mesas de Reunião
                'title' => 'Mesa de Reunião Executiva Premium',
                'slug' => 'mesa-reuniao-executiva-premium',
                'summary' => 'Leve sofisticação e funcionalidade à sua sala de reuniões com esta mesa executiva premium para até 8 pessoas, com design moderno e estrutura resistente.',
                'description' => '<p>A <strong>Mesa de Reunião Executiva Premium</strong> é o centro ideal para salas de reunião profissionais. Projetada para comportar até 8 pessoas, oferece amplo espaço e aparência elegante, perfeita para apresentações e reuniões estratégicas.</p><p>Feita em madeira de alta qualidade com acabamento polido, possui durabilidade excepcional. Inclui sistema de organização de cabos com passadores embutidos para manter o espaço limpo e funcional, sem perder a estética refinada.</p>',
            ],
            [
                'item_id' => 32,
                'category_uid' => '673ae3c9c1168', // Armazenamento
                'subcategory_uid' => '673b0b4a0501b', // Armários
                'title' => 'Armário Contemporâneo com 4 Portas',
                'slug' => 'armario-contemporaneo-4-portas',
                'summary' => 'Armário versátil e moderno com 4 portas. Ideal para escritórios, salas ou quartos, oferecendo amplo espaço para organização com estilo.',
                'description' => '<p>O <strong>Armário Contemporâneo com 4 Portas</strong> une design moderno e praticidade. Conta com quatro compartimentos espaçosos para livros, documentos, roupas ou objetos decorativos. Seu estilo minimalista se adapta a qualquer ambiente.</p><p>Produzido com madeira de alta durabilidade e acabamento liso, possui prateleiras ajustáveis e portas de fácil abertura. Uma peça funcional para quem busca organização sem abrir mão da elegância.</p>',
            ],
            [
                'item_id' => 33,
                'category_uid' => '673ae3c9c1168', // Armazenamento
                'subcategory_uid' => '673b0b4a0501b', // Cômodas
                'title' => 'Cômoda Elegante com 5 Gavetas',
                'slug' => 'comoda-elegante-5-gavetas',
                'summary' => 'Organização e elegância em uma só peça. A cômoda com 5 gavetas é ideal para quartos e salas, oferecendo amplo espaço e design sofisticado.',
                'description' => '<p>A <strong>Cômoda Elegante com 5 Gavetas</strong> foi projetada para aliar organização e estilo. Seu design contemporâneo e acabamento liso permitem integrar facilmente a diversos estilos de interiores.</p><p>Com estrutura em madeira de alta qualidade, possui gavetas espaçosas e deslizamento suave. Ideal para armazenar roupas, acessórios ou itens domésticos com praticidade e bom gosto.</p>',
            ],
            [
                'item_id' => 34,
                'category_uid' => '673ae3a9c8994', // Infantil
                'subcategory_uid' => '673b0da0105e3', // Berços
                'title' => 'Berço Conversível Cozy Dream',
                'slug' => 'berco-conversivel-cozy-dream',
                'summary' => 'Ofereça conforto e segurança ao seu bebê com o Berço Conversível Cozy Dream. Com design atemporal e estrutura durável, ele se transforma em cama infantil e acompanha o crescimento da criança.',
                'description' => '<p>O <strong>Berço Conversível Cozy Dream</strong> foi criado para proporcionar um ambiente de sono seguro e confortável, com um design clássico que combina com qualquer quarto de bebê.</p><p>Fabricado com materiais atóxicos e de alta qualidade, possui regulagem de altura do colchão e se converte facilmente em cama infantil, garantindo uso prolongado. Segurança, estilo e funcionalidade em uma única peça.</p>',
            ],
            [
                'item_id' => 35,
                'category_uid' => '673ae3a9c8994', // Infantil
                'subcategory_uid' => '673b0da0105e3', // Beliches Infantis
                'title' => 'Beliche Infantil com Escada Loft',
                'slug' => 'beliche-infantil-com-escada-loft',
                'summary' => 'Transforme o quarto infantil com o Beliche Loft, uma solução divertida e funcional. Ideal para irmãos ou visitas, combina conforto e segurança em um só móvel.',
                'description' => '<p>O <strong>Beliche Infantil com Escada Loft</strong> é perfeito para economizar espaço e oferecer duas camas em uma estrutura segura e divertida. O design twin sobre twin é ideal para quartos compartilhados ou para receber visitas.</p><p>Feito em madeira sólida com acabamento durável, inclui escada integrada com degraus largos e bordas de proteção na cama superior. Funcionalidade e estilo para o quarto das crianças.</p>',
            ],
            [
                'item_id' => 36,
                'category_uid' => '673ae3d27d6f8', // Cozinha
                'subcategory_uid' => '673b095c03fd0', // Mesas de Cozinha
                'title' => 'Mesa de Cozinha Conforto Moderno',
                'slug' => 'mesa-cozinha-conforto-moderno',
                'summary' => 'Com design ergonômico e materiais duráveis, a Mesa de Cozinha Conforto Moderno une estilo e praticidade para refeições confortáveis no dia a dia.',
                'description' => '<p>Eleve sua cozinha com a <strong>Mesa de Cozinha Conforto Moderno</strong>, ideal para refeições, encontros e momentos em família. Possui estrutura resistente e visual moderno.</p><p>Construída em madeira de alta qualidade com reforços metálicos, conta com assento estofado, encosto anatômico e tecido resistente à água. Os pés antiderrapantes protegem o piso e garantem estabilidade.</p>',
            ],
            [
                'item_id' => 37,
                'category_uid' => '673ae3d27d6f8', // Cozinha
                'subcategory_uid' => '673b0dfb0640b', // Estantes de Cozinha
                'title' => 'Estante de Cozinha Rústica com Múltiplos Níveis',
                'slug' => 'estante-cozinha-rustica-multiplos-niveis',
                'summary' => 'Organize sua cozinha com estilo usando a Estante Rústica Multi Níveis. Ideal para panelas, mantimentos e decoração, une praticidade e charme.',
                'description' => '<p>A <strong>Estante de Cozinha Rústica com Múltiplos Níveis</strong> oferece amplo espaço para armazenar utensílios, alimentos e itens decorativos, mantendo sua cozinha organizada com elegância rústica.</p><p>Fabricada em madeira e aço, combina resistência com visual aconchegante. Suas prateleiras suportam peso e são ideais para eletros pequenos, potes e livros de receitas.</p>',
            ],
            [
                'item_id' => 38,
                'category_uid' => '673ae3d27d6f8', // Cozinha
                'subcategory_uid' => '673b0e7602b60', // Armários de Cozinha
                'title' => 'Armários Modulares Premium de Cozinha',
                'slug' => 'armarios-modulares-premium-cozinha',
                'summary' => 'Otimize sua cozinha com os Armários Modulares Premium. Design moderno, materiais resistentes e soluções inteligentes para armazenar com praticidade.',
                'description' => '<p>Os <strong>Armários Modulares Premium de Cozinha</strong> são ideais para quem busca um ambiente funcional, moderno e bem organizado. Seu design elegante se adapta a qualquer estilo de cozinha.</p><p>Feitos em MDF com acabamento laminado, são resistentes a riscos e fáceis de limpar. Possuem prateleiras ajustáveis, gavetas espaçosas, amortecedores soft-close e soluções como bandejas deslizantes e cantos otimizados. Personalize a disposição conforme suas necessidades.</p>',
            ],
            [
                'item_id' => 39,
                'category_uid' => '673ae3d27d6f8',
                'subcategory_uid' => '673b0dfb0640b',
                'title' => 'Guarda-Roupa de Duas Portas com Prateleiras',
                'slug' => 'guarda-roupa-duas-portas-com-prateleiras',
                'summary' => 'Mantenha seu quarto organizado com o Guarda-Roupa de Duas Portas com Prateleiras. Com amplo espaço e estilo moderno, é perfeito para roupas e acessórios.',
                'description' => '<p>O <strong>Guarda-Roupa de Duas Portas com Prateleiras</strong> oferece espaço amplo com prateleiras ajustáveis, cabideiro resistente e visual elegante. Feito com madeira engenheirada de alta qualidade, possui acabamento resistente a riscos e estrutura durável.</p>',
            ],
            [
                'item_id' => 40,
                'category_uid' => '673ae3d27d6f8',
                'subcategory_uid' => '673b0dfb0640b',
                'title' => 'Cômoda Clássica com Seis Gavetas',
                'slug' => 'comoda-classica-com-seis-gavetas',
                'summary' => 'Organize seu quarto com estilo usando a Cômoda Clássica de Seis Gavetas. Elegante e funcional, oferece amplo espaço para roupas, roupas de cama e mais.',
                'description' => '<p>A <strong>Cômoda Clássica com Seis Gavetas</strong> combina design sofisticado e funcionalidade. Construída com madeira de qualidade e acabamento durável, suas gavetas são espaçosas, com corrediças suaves e puxadores modernos.</p>',
            ],
            [
                'item_id' => 41,
                'category_uid' => '673ae3d27d6f8',
                'subcategory_uid' => '673b0e7602b60',
                'title' => 'Conjunto de Cadeiras Elegantes (com 2 unidades)',
                'slug' => 'conjunto-cadeiras-elegantes-2-unidades',
                'summary' => 'Adicione conforto e estilo à sua sala de jantar com o Conjunto de Cadeiras Elegantes. Ideal para refeições casuais ou ocasiões especiais.',
                'description' => '<p>O <strong>Conjunto de Cadeiras Elegantes</strong> traz conforto e sofisticação para sua sala de jantar. Com estrutura em madeira maciça, estofado resistente a manchas e encosto ergonômico, oferece durabilidade e elegância.</p>',
            ],
            [
                'item_id' => 42,
                'category_uid' => '673ae3d27d6f8',
                'subcategory_uid' => '673b0e7602b60',
                'title' => 'Mesa de Jantar Extensível',
                'slug' => 'mesa-de-jantar-extensivel',
                'summary' => 'A Mesa de Jantar Extensível adapta-se a jantares íntimos ou reuniões maiores com elegância e funcionalidade.',
                'description' => '<p>A <strong>Mesa de Jantar Extensível</strong> possui estrutura robusta em madeira, acabamento polido e sistema de extensão simples, ideal para acomodar de forma prática diferentes números de convidados. Design atemporal e resistente a riscos.</p>',
            ],
            [
                'item_id' => 43,
                'category_uid' => '673ae3d27d6f8',
                'subcategory_uid' => '673b0e7602b60',
                'title' => 'Banquetas Contemporâneas para Balcão',
                'slug' => 'banquetas-contemporaneas-para-balcao',
                'summary' => 'As Banquetas Contemporâneas unem design moderno e conforto, ideais para bancadas e mesas altas.',
                'description' => '<p>As <strong>Banquetas Contemporâneas</strong> apresentam estrutura metálica elegante, assento acolchoado com revestimento em couro sintético e apoio para os pés. São compactas, fáceis de limpar e combinam com cozinhas modernas e espaços gourmet.</p>',
            ],
            [
                'item_id' => 44,
                'category_uid' => '673ae3a9c8994',
                'subcategory_uid' => '673b0e7602b60',
                'title' => 'Cadeirinha ComfortFit para Bebês e Crianças',
                'slug' => 'cadeirinha-comfortfit-bebes-criancas',
                'summary' => 'A ComfortFit é ideal para refeições, brincadeiras ou estudos, combinando conforto, segurança e um design lúdico.',
                'description' => '<p>A <strong>Cadeirinha ComfortFit</strong> é feita com materiais atóxicos e resistentes, possui encosto ergonômico e design leve, com bordas arredondadas para maior segurança. Colorida e fácil de limpar, acompanha o crescimento da criança com praticidade e charme.</p>',
            ],

        ];

        foreach ($produtos as $produto) {
            DB::table('user_item_contents')->insert([
                'user_id'        => 11,
                'item_id'        => $produto['item_id'],
                'language_id'    => 35,
                'currency_id'    => 31,
                'category_id'    => $categorias_ids[$produto['category_uid']] ?? null,
                'subcategory_id' => $subcategorias_ids[$produto['subcategory_uid']] ?? null,
                'title'          => $produto['title'],
                'slug'           => $produto['slug'],
                'summary'        => $produto['summary'],
                'description'    => $produto['description'],
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
