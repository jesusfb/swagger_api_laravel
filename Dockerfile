FROM php:8.1-alpine
WORKDIR /var/www/html

RUN apk update 
RUN curl -sS https://getcomposer.org/installer | php -- --version=2.4.3 --install-dir=/usr/local/bin --filename=composer

COPY . .
RUN composer install

cd /app
RUN php artisan config:clear
RUN php artisan migrate:fresh

CMD ["php","artisan","serve","--host=0.0.0.0"]
