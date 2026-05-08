#!/bin/bash
# Instala dependencias de PHP y Node, compila assets y ejecuta migraciones
composer install --optimize-autoloader --no-interaction --no-dev
npm install
npm run build
php artisan migrate --force
# Inicia Apache en primer plano (requerido por Render)
apache2-foreground