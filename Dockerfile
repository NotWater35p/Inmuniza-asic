FROM php:8.2-apache

# Instala dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip libpng-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configura Apache para servir desde la carpeta public de Laravel
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Copia todo el código (incluye assets ya compilados)
COPY . /var/www/html

# Instala dependencias de PHP ignorando advertencias de plataforma
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Establece permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Inicia Apache
CMD ["apache2-foreground"]