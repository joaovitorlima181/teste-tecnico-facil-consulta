# Script para instalar o sistema

# Criação .env
mv .env.example .env

#Instala as dependências
composer install
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan jwt:secret

# Build dos containers
./vendor/bin/sail up -d

# Migração do banco de dados
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed

