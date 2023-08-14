#FROM php:8.1-alpine
#WORKDIR /var/www/html

#RUN apk update 
#RUN curl -sS https://getcomposer.org/installer | php -- --version=2.4.3 --install-dir=/usr/local/bin --filename=composer

#COPY . .
#RUN composer install

#CMD ["php","artisan","serve","--host=0.0.0.0"]

FROM php:8.1-alpine

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
COPY . .
RUN composer install
RUN php artisan migrate
CMD php artisan serve --host=0.0.0.0 --port=8000
EXPOSE 8000
