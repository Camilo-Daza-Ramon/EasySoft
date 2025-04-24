# Usa una imagen base de PHP 7.3 con Apache
FROM php:7.3-apache

# Instala extensiones necesarias para Laravel 5.1
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring gd

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia los archivos del proyecto al contenedor
COPY . /var/www/html

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Da permisos a las carpetas de almacenamiento y caché
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Ejecuta comandos de configuración de Laravel
RUN composer install --no-dev --optimize-autoloader && \
    php artisan config:cache && \
    php artisan route:cache

# Expone el puerto 80
EXPOSE 80

# Inicia el servidor Apache
CMD ["apache2-foreground"]