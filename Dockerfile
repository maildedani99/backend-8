FROM php:8.1-fpm

# Instalar dependencias
RUN apt-get update && \
    apt-get install -y libzip-dev zip unzip

# Habilitar extensiones PHP
RUN docker-php-ext-install pdo_mysql zip

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de la aplicación al contenedor
COPY . /var/www/html

# Instalar dependencias de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# Permisos y configuración
RUN chown -R www-data:www-data /var/www/html/storage
RUN chmod -R 775 /var/www/html/storage
