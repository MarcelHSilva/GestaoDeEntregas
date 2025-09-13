#!/bin/bash

# Criar diretório para o banco de dados se não existir
mkdir -p /tmp

# Criar arquivo de banco SQLite se não existir
touch /tmp/database.sqlite

# Executar migrações
php artisan migrate --force

# Iniciar servidor
vendor/bin/heroku-php-apache2 public/