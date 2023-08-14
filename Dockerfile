FROM php:8.1-alpine


RUN apk update 
RUN curl -sS https://getcomposer.org/installer | php -- --version=2.4.3 --install-dir=/usr/local/bin --filename=composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .
RUN composer install

 CMD bash -c "php artisan l5-swagger:generate"
CMD ["php","artisan","serve","--host=0.0.0.0"]

