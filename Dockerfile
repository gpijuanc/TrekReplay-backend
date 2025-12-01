# Utilitzem la imatge oficial de PHP amb Apache
FROM php:8.2-apache

# 1. Instal·lem dependències del sistema i llibreries per a la BD (PostgreSQL)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev  

# 2. Habilitem el mòdul rewrite d'Apache (necessari per a les rutes de Laravel)
RUN a2enmod rewrite

# 3. Instal·lem les extensions de PHP necessàries (incloent pdo_pgsql per a Render)
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# 4. Configurem la carpeta de treball
WORKDIR /var/www/html

# 5. Copiem els fitxers del projecte al contenidor
COPY . .

# 6. Instal·lem Composer i les dependències de Laravel
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 7. Donem permisos a les carpetes d'emmagatzematge
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Configurem Apache perquè apunti a la carpeta 'public'
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 9. Exposem el port 80 (estàndard web)
EXPOSE 80
# 10. Executem migracions i arrenquem Apache
CMD sh -c "php artisan migrate:fresh --seed --force && apache2-foreground"