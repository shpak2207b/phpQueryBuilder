FROM php:8.2-apache

RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite

RUN docker-php-ext-configure exif
RUN docker-php-ext-install exif
RUN docker-php-ext-enable exif
RUN a2enmod rewrite
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf


WORKDIR /var/www/html/