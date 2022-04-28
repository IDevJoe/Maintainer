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
    apt-get -y install tzdata cron && \
    docker-php-ext-install pdo
RUN cp /usr/share/zoneinfo/America/Chicago /etc/localtime && \
    echo "America/Chicago" > /etc/timezone
COPY --from=composer /app /var/www/html
RUN chown -R www-data:www-data /var/www/html/storage
ADD .docker/cron /etc/cron.d/cron
RUN chmod 0644 /etc/cron.d/cron
RUN crontab /etc/cron.d/cron
RUN mkdir -p /var/log/cron
RUN sed -i 's/^exec /service cron start\n\nexec /' /usr/local/bin/apache2-foreground
