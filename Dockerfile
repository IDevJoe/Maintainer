FROM node:lts AS builder
WORKDIR /app
RUN git clone https://github.com/IDevJoe/Maintainer.git /app
RUN npm install && npm run prod

FROM composer:latest AS composer
WORKDIR /app
COPY --from=builder /app /app
RUN composer install

FROM php:8.0-apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN apt-get update && \
    docker-php-ext-install pdo
COPY --from=composer /app /var/www/html
RUN chown -R www-data:www-data /var/www/html/storage
