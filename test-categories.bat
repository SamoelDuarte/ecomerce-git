@echo off
echo =========================================
echo    TESTE DA FUNCIONALIDADE DE CATEGORIAS
echo =========================================
echo.

echo [1/5] Executando migração da tabela user_categories...
php artisan migrate --force
if %errorlevel% neq 0 (
    echo ERRO: Falha na migração
    pause
    exit /b 1
)

echo.
echo [2/5] Executando seeder das categorias...
php artisan db:seed --class=UserCategorySeeder --force
if %errorlevel% neq 0 (
    echo ERRO: Falha no seeder
    pause
    exit /b 1
)

echo.
echo [3/5] Limpando caches...
php artisan cache:clear >nul 2>&1
php artisan config:clear >nul 2>&1
php artisan route:clear >nul 2>&1

echo.
echo [4/5] Verificando estrutura das categorias...
php artisan fix:user-categories

echo.
echo [5/5] Teste concluído!
echo.
echo =========================================
echo           PRÓXIMOS PASSOS
echo =========================================
echo 1. Acesse: http://seu-site/admin/register-users
echo 2. Verifique se a coluna "Category" aparece
echo 3. Crie um novo usuário para testar
echo 4. Para gerenciar categorias: /admin/register-users/categories
echo =========================================
echo.
pause
