# Stage 1 – Build Frontend (Vite) con Node Alpine (ligero) y límite de memoria
FROM node:18-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .

# Limita la memoria de Node a 256 MB para evitar OOM en Render free
ENV NODE_OPTIONS=--max_old_space_size=256
RUN npm run build

# Stage 2 – Backend (Laravel + Apache)
FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

COPY . /var/www/html
COPY --from=frontend /app/public/dist /var/www/html/public/dist

RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["apache2-foreground"]