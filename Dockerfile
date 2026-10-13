# Dockerfile
# Image dasar PHP 8.2 dengan Apache

FROM php:8.2-apache

# Install ekstensi PHP untuk koneksi ke MariaDB/MySQL
RUN docker-php-ext-install mysqli pdo_mysql

# Aktifkan mod rewrite Apache
RUN a2enmod rewrite

# Atur folder kerja default di dalam container
WORKDIR /var/www/html
