#!/bin/bash

echo "======================================"
echo "Corrigindo permissões Laravel no XAMPP"
echo "======================================"

# Diretório do projeto
PROJECT_DIR="/Applications/XAMPP/xamppfiles/htdocs/ecomerce-git"

echo "1. Alterando proprietário para daemon (usuário do Apache)..."
sudo chown -R daemon:daemon "$PROJECT_DIR/storage" "$PROJECT_DIR/bootstrap/cache"

echo "2. Configurando permissões de escrita..."
sudo chmod -R 775 "$PROJECT_DIR/storage" "$PROJECT_DIR/bootstrap/cache"

echo "3. Limpando caches do Laravel..."
cd "$PROJECT_DIR"
php artisan cache:clear
php artisan config:clear
php artisan view:clear

echo "4. Verificando permissões do arquivo de log..."
ls -la "$PROJECT_DIR/storage/logs/laravel.log"

echo "======================================"
echo "Permissões corrigidas com sucesso! ✅"
echo "Acesse: http://localhost:8080"
echo "======================================"